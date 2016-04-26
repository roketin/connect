<?php

namespace Roketin\Facades;

use Illuminate\Support\Facades\Facade;

class RoketinFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'roketin';
    }
}
