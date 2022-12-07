<?php

namespace BdThemes\SingleSignOn;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
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

            $this->publishes([
                __DIR__.'/../database/migrations/add_bdthemes_account_id_field_to_users_table.php.stub' => $this->getMigrationFileName('add_bdthemes_account_id_field_to_users.php'),
            ], 'migrations');
            
        }
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
