<?php

namespace AloPeyk\Api\RESTful\Model;

use AloPeyk\Api\RESTful\ApiHandler;


class Location
{
    /**
     * @param $latitude
     * @param $longitude
     * @return mixed
     */
    public static function getAddress($latitude, $longitude)
    {
        return ApiHandler::getAddress($latitude, $longitude);
    }

    /**
     * @param $locationName
     * @return mixed
     */
    public static function getSuggestions($locationName)
    {
        return ApiHandler::getLocationSuggestion($locationName);
    }
}
