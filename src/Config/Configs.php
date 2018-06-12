<?php

namespace AloPeyk\Api\RESTful\Config;

class Configs
{
    private $appConfig;

    public function __construct()
    {
        $this->setConfig();
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | PACKAGE CONSTANTS
    |-------------------------------------------------------------------------------------------------------------------
    |
    | Don't edit following values
    |
    */
    const URL = 'https://api.alopeyk.com/';
    const API_URL = 'https://api.alopeyk.com/api/v2/';
    const TRACKING_URL = 'https://tracking.alopeyk.com/';
    const PAYMENT_ROUTES = [
        'saman' => 'payments/saman/checkout',
        'zarinpal' => 'payments/zarinpal/checkout'
    ];
    const ADDRESS_TYPES = [
        'origin',
        'destination',
    ];
    const TRANSPORT_TYPES = [
        'motorbike' => [
            'label' => 'Motorbike',
            'delivery' => true
        ],
        'motor_taxi' => [
            'label' => 'Cart Bike',
            'delivery' => false
        ],
        'cargo' => [
            'label' => 'Cargo',
            'delivery' => true
        ],
        'cargo_s' => [
            'label' => 'Small Cargo',
            'delivery' => true
        ],
        'car' => [
            'label' => 'Car',
            'delivery' => true
        ],
    ];
    const CITIES = [
        'tehran',
        'shemiranat',
        'rey',
        'karaj',
        'isfahan',
        'tabriz',
        'mashhad',
        'shiraz',
    ];

    /**
     * Set appConfig attribute
     */
    private function setConfig()
    {
        $this->appConfig = AloPeykApiHandler::getAppConfig();
    }

    /**
     * Get appConfig attribute
     */
    public function getConfig()
    {
        return $this->appConfig;
    }

}

