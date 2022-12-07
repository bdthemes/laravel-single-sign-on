
# The Package is not for public use.

Install Package
```
composer require bdthemes/laravel-single-sign-on
```

Published migrations
```
php artisan vendor:publish --provider="BdThemes\SingleSignOn\SingleSignOnServiceProvider"
```


In the services config
```
'bdthemes' => [
    'client_id'         => env('BDTHEMES_CLIENT_ID'),
    'client_secret'     => env('BDTHEMES_CLIENT_SECRET'),
    'redirect'          => env('BDTHEMES_REDIRECT_URI'),
    'api_url'           => env('BDTHEMES_SSO_API_URL', 'https://account.bdthemes.com'),
],
