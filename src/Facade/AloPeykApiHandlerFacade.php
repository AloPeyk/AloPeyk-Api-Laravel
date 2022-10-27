<?php

namespace AloPeyk\AloPeyk\Facade;

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
