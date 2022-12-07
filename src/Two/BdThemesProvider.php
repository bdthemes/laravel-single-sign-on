<?php

namespace BdThemes\SingleSignOn\Two;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BdThemesProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'basic-profile',
    ];


    protected function getApiUrl($endPoint)
    {
        if (!$apiBaseUrl = config('services.bdthemes.api_url')) {
            $apiBaseUrl = "https://account.bdthemes.com";
        }

        $apiBaseUrl = Str::of($apiBaseUrl)->rtrim('/')->toString();

        if (!$endPoint) {
            return $apiBaseUrl;
        }
        $endPoint = Str::of($endPoint)->start('/')->toString();

        return "{$apiBaseUrl}{$endPoint}";
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getApiUrl('/oauth/authorize'), $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getApiUrl('/oauth/token');
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getApiUrl('/api/user'), [
            RequestOptions::QUERY => [
                'prettyPrint' => 'false',
            ],
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
            'verify' => config('app.debug') ? false : true,
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        // Deprecated: Fields added to keep backwards compatibility in 4.0. These will be removed in 5.0
        $user['link'] = Arr::get($user, 'profile_url');

        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'id'),
            'nickname' => Arr::get($user, 'nickname'),
            'first_name' => Arr::get($user, 'first_name'),
            'last_name' => Arr::get($user, 'last_name'),
            'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
            'avatar' => $avatarUrl = Arr::get($user, 'avatar'),
            'avatar_original' => $avatarUrl,
        ]);
    }
}
