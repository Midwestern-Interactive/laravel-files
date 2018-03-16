<?php

namespace MWI\LaravelFiles\Facades;

use Illuminate\Support\Facades\Facade;

class MWIFile extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mwifile';
    }
}
