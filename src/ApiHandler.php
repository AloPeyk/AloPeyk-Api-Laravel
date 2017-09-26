<?php

namespace AloPeyk\Api\RESTful;

use AloPeyk\Api\RESTful\Config\Configs;
use AloPeyk\Api\RESTful\Exception\AloPeykApiException;
use AloPeyk\Api\RESTful\Exception\InvalidLatLongException;
use AloPeyk\Api\RESTful\Exception\InvalidLocationNameException;
use AloPeyk\Api\RESTful\Exception\InvalidOrderException;
use AloPeyk\Api\RESTful\Validator\AloPeykValidator;

class ApiHandler
{

    private static $localToken;

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return self::$name($arguments);
        }

        throw new \Exception('AloPeyk API: This Function Does Not Exist!');
    }

    /**
     * @param string $endPoint
     * @param string $method
     * @param null $postFields
     * @return array
     * @throws AloPeykApiException
     */
    private static function getCurlOptions($endPoint = '', $method = 'GET', $postFields = null)
    {
        /*
         * Throw Exception If User Machine DOES NOT ABLE To Use 'openssl'
         */
        if (!extension_loaded('openssl')) {
            throw new AloPeykApiException('AloPeyk API Needs The Open SSL PHP Extension! please enable it on your server.');
        }

        /*
         * Get ACCESS-TOKEN
         */
        $accessToken = empty(self::$localToken) ? config('alopeyk.access-token') : self::$localToken;
        if (empty($accessToken) || $accessToken == "PUT-YOUR-ACCESS-TOKEN-HERE") {
            throw new AloPeykApiException('Invalid ACCESS-TOKEN! All AloPeyk API endpoints support the JWT authentication protocol. To start sending authenticated HTTP requests you will need to use your JWT authorization token which is sent to you. Put it in: vendor/alopeyk/alopeyk-api-php/src/Config/Configs.php : TOKEN const');
        }

        $curlOptions = [
            CURLOPT_URL => Configs::API_URL . $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_FAILONERROR => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json; charset=utf-8',
                'X-Requested-With: XMLHttpRequest'
            ],
        ];
        if ($method == 'GET') {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = 'GET';
        } else {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = 'POST';
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($postFields);
        }
        return $curlOptions;
    }

    /**
     * @param $curlObject
     * @return mixed
     * @throws \Exception
     */
    private static function getApiResponse($curlObject)
    {
        $response = curl_exec($curlObject);
        $err = curl_error($curlObject);

        curl_close($curlObject);

        if ($err) {
            throw new \Exception($err);
        } else {
            return json_decode($response);
        }
    }


    /** ----------------------------------------------------------------------------------------------------------------
     * public functions
     * ---------------------------------------------------------------------------------------------------------------- */

    /**
     * Authentication
     */
    public static function authenticate()
    {
        $curl = curl_init();

        curl_setopt_array($curl, self::getCurlOptions());

        return self::getApiResponse($curl);
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return mixed
     * @throws InvalidLatLongException
     */
    public static function getAddress($latitude, $longitude)
    {
        $curl = curl_init();


        if (!AloPeykValidator::validateLatitude($latitude)) {
            throw new InvalidLatLongException('Latitude');
        }
        if (!AloPeykValidator::validateLongitude($longitude)) {
            throw new InvalidLatLongException('Longitude');
        }


        curl_setopt_array($curl, self::getCurlOptions("locations?latlng=$latitude,$longitude"));

        return self::getApiResponse($curl);
    }

    /**
     * @param $locationName
     * @return mixed
     * @throws AloPeykApiException
     */
    public static function getLocationSuggestion($locationName)
    {
        $curl = curl_init();

        $locationName = AloPeykValidator::sanitize($locationName);
        if (empty($locationName)) {
            throw new AloPeykApiException("Invalid Location Name! location name can not be empty");
        }

        curl_setopt_array($curl, self::getCurlOptions("locations?input=$locationName"));

        return self::getApiResponse($curl);
    }

    /**
     * @param $order
     * @return mixed
     */
    public static function getPrice($order)
    {
        $curl = curl_init();

        curl_setopt_array($curl, self::getCurlOptions('orders/price/calc', 'POST', $order->toArray('getPrice')));

        return self::getApiResponse($curl);
    }

    /**
     * @param $order
     * @return mixed
     */
    public static function createOrder($order)
    {
        $curl = curl_init();

        curl_setopt_array($curl, self::getCurlOptions('orders', 'POST', $order->toArray('createOrder')));

        return self::getApiResponse($curl);
    }

    /**
     * @param $orderID
     * @return mixed
     * @throws InvalidOrderException
     */
    public static function getOrderDetail($orderID)
    {
        $curl = curl_init();

        $orderID = AloPeykValidator::sanitize($orderID);
        if (!filter_var($orderID, FILTER_VALIDATE_INT)) {
            throw new InvalidOrderException("Invalid order id! Order ID must be integer");
        }

        curl_setopt_array($curl, self::getCurlOptions("orders/{$orderID}?columns=*,addresses,screenshot,progress,courier,customer,last_position_minimal,eta_minimal"));

        return self::getApiResponse($curl);
    }

    /**
     * @param $orderID
     * @return mixed
     * @throws InvalidOrderException
     */
    public static function cancelOrder($orderID)
    {
        $curl = curl_init();

        $orderID = AloPeykValidator::sanitize($orderID);
        if (!filter_var($orderID, FILTER_VALIDATE_INT)) {
            throw new InvalidOrderException("Invalid order id! Order ID must be integer");
        }

        curl_setopt_array($curl, self::getCurlOptions("orders/{$orderID}/cancel"));

        return self::getApiResponse($curl);
    }
}