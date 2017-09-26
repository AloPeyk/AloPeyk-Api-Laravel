<?php

namespace AloPeyk\Api\RESTful\Validator;

class AloPeykValidator
{
    /**
     * Validates a given latitude $lat
     *
     * @param float|int|string $lat Latitude
     * @return bool `true` if $lat is valid, `false` if not
     */
    public static function validateLatitude($lat)
    {
        return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat);
    }

    /**
     * Validates a given longitude $long
     *
     * @param float|int|string $lng Longitude
     * @return bool `true` if $long is valid, `false` if not
     */
    public static function validateLongitude($lng)
    {
        return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lng);
    }

    /**
     * Validates a given coordinate
     *
     * @param float|int|string $lat Latitude
     * @param float|int|string $lng Longitude
     * @return bool `true` if the coordinate is valid, `false` if not
     */
    public static function validateLatLong($lat, $lng)
    {
        return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $lat . ',' . $lng);
    }


    /**
     * Sanitize a given string
     * @param $string
     * @return string
     */
    public static function sanitize($string)
    {
        return strtolower(trim(filter_var($string, FILTER_SANITIZE_STRING)));
    }

}