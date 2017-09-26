<?php

namespace AloPeyk\Api\RESTful\Facade;

use Illuminate\Support\Facades\Facade;

class AloPeykApiHandlerFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'AloPeykApiHandler';
    }
}