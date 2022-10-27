<?php

namespace AloPeyk\AloPeyk\Exception;

class InvalidAddressException extends AloPeykApiException
{
    /**
     * InvalidAddressException constructor.
     * @param string $cause
     * @param null $type
     */
    public function __construct($cause, $type = null)
    {
        if (!empty($type)) {
            $cause = "$type Address Is Not Valid! $cause";
        }
        parent::__construct($cause);
    }
}
