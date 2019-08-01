<?php

namespace Developifynet\LeopardsCOD;

use Illuminate\Support\Facades\Facade;

class LeopardsCOD extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'leopardscod';
    }
}