<?php

namespace AloPeyk\Api\RESTful\Exception;

use Exception;

class AloPeykApiException extends Exception
{
    /**
     * AloPeykApiException constructor.
     * @param string $message
     */
    public function __construct($message = 'An error occurred')
    {
        parent::__construct("AloPeyk API Handler: " . $message);
    }
}