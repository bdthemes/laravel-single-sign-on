<?php

namespace BdThemes\SingleSignOn\Facades;

use Illuminate\Support\Facades\Facade;
use BdThemes\SingleSignOn\Contracts\Factory;

/**
 * @method static \BdThemes\SingleSignOn\Contracts\Provider driver(string $driver = null)
 *
 * @see \BdThemes\SingleSignOn\SingleSignOnManager
 */
class BdThemesSingleSignOn extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
