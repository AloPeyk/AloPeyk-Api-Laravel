<?php

namespace AloPeyk\AloPeyk\Model;

use AloPeyk\AloPeyk\ApiHandler;

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
    public static function getSuggestions($locationName, $latlng)
    {
        return ApiHandler::getLocationSuggestion($locationName, $latlng);
    }
}
