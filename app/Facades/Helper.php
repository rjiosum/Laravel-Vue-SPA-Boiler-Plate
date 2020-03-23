<?php

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\Services\HelperService
 */
class Helper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'HelperService';
    }
}