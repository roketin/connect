<?php

namespace Roketin\Facades;

use Illuminate\Support\Facades\Facade;

class Roketin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'roketin';
    }
}
