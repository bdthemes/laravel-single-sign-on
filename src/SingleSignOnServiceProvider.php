<?php

namespace BdThemes\SingleSignOn;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use BdThemes\SingleSignOn\Contracts\Factory;

class SingleSignOnServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
              __DIR__.'/../config/bdthemes-sso.php', 'bdthemes-sso'
        );

        $this->app->singleton(Factory::class, function ($app) {
            return new SingleSignOnManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Factory::class];
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot() {
        $this->bootPublishing();
    }

    protected function bootPublishing(){
        if ( $this->app->runningInConsole() ) {

            $this->publishes( [
                __DIR__
                . '/../config/bdthemes-sso.php' => $this->app->configPath( 'bdthemes-sso.php' ),
            ], 'bdthemes-sso' );

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'bdthemes-sso-migrations');

        }
    }
}
