<?php

namespace BdThemes\SingleSignOn\Contracts;

interface Factory
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param  string  $driver
     * @return \BdThemes\SingleSignOn\Contracts\Provider
     */
    public function driver($driver = null);
}
