<?php

namespace AloPeyk\AloPeyk\Exception;

class InvalidOrderException extends AloPeykApiException
{
    /**
     * InvalidOrderException constructor.
     * @param string $cause
     */
    public function __construct($cause)
    {
        parent::__construct($cause);
    }
}
