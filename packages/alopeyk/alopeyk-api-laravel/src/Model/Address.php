<?php

namespace AloPeyk\Api\RESTful\Model;

use AloPeyk\Api\RESTful\Config\Configs;
use AloPeyk\Api\RESTful\Exception\AloPeykApiException;
use AloPeyk\Api\RESTful\Exception\InvalidAddressException;
use AloPeyk\Api\RESTful\Exception\InvalidLatLongException;
use AloPeyk\Api\RESTful\Validator\AloPeykValidator;

class Address
{
    // Attributes ------------------------------------------------------------------------------------------------------

    private $type;
    private $city;
    private $latitude;
    private $longitude;
    private $address;
    private $description;       //some description for origin'
    private $unit;              //unit of origin address
    private $number;            //number of origin address
    private $person_fullname;   //sender s name
    private $person_phone;      //sender s phone

    /**
     * Address constructor.
     * @param $type
     * @param $city
     * @param $latitude
     * @param $longitude
     */
    public function __construct($type, $city, $latitude, $longitude)
    {
        $this->setType($type);
        $this->setCity($city);
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    // Setters ---------------------------------------------------------------------------------------------------------

    /**
     * @param $type
     * @throws AloPeykApiException
     */
    public function setType($type)
    {
        $type = AloPeykValidator::sanitize($type);
        if (!in_array($type, array_values(Configs::ADDRESS_TYPES))) {
            throw new InvalidAddressException('Type Of Address is not correct');
        }

        $this->type = $type;
    }

    /**
     * @param $city
     * @throws AloPeykApiException
     */
    public function setCity($city)
    {
        $city = AloPeykValidator::sanitize($city);
        if (!in_array($city, array_values(Configs::CITIES))) {
            throw new InvalidAddressException('This city is not supported yet');
        }

        $this->city = $city;
    }

    /**
     * @param $latitude
     * @throws AloPeykApiException
     */
    public function setLatitude($latitude)
    {
        if (!AloPeykValidator::validateLatitude($latitude)) {
            throw new InvalidLatLongException('Latitude');
        }

        $this->latitude = $latitude;
    }

    /**
     * @param $longitude
     * @throws AloPeykApiException
     */
    public function setLongitude($longitude)
    {
        if (!AloPeykValidator::validateLongitude($longitude)) {
            throw new InvalidLatLongException('Longitude');
        }

        $this->longitude = $longitude;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = AloPeykValidator::sanitize($address);
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = AloPeykValidator::sanitize($description);
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit)
    {
        $this->unit = AloPeykValidator::sanitize($unit);
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = AloPeykValidator::sanitize($number);
    }

    /**
     * @param mixed $personFullname
     */
    public function setPersonFullname($personFullname)
    {
        $this->person_fullname = AloPeykValidator::sanitize($personFullname);
    }

    /**
     * @param mixed $personPhone
     */
    public function setPersonPhone($personPhone)
    {
        $this->person_phone = AloPeykValidator::sanitize($personPhone);
    }

    // Getters ---------------------------------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getPersonFullname()
    {
        return $this->person_fullname;
    }

    /**
     * @return mixed
     */
    public function getPersonPhone()
    {
        return $this->person_phone;
    }


    // Utilities -------------------------------------------------------------------------------------------------------

    /**
     * @param $endPoint
     * @return array
     */
    public function toArray($endPoint)
    {
        $this->isValid($endPoint);

        return [
            'type' => $this->getType(),
            'city' => $this->getCity(),
            'lat' => $this->getLatitude(),
            'lng' => $this->getLongitude(),
            'address' => $this->getAddress(),
            'description' => $this->getDescription(),
            'unit' => $this->getUnit(),
            'number' => $this->getNumber(),
            'person_fullname' => $this->getPersonFullname(),
            'person_phone' => $this->getPersonPhone(),
        ];
    }

    /**
     * @param $endPoint
     * @return bool
     * @throws AloPeykApiException
     */
    private function isValid($endPoint)
    {
        if ($endPoint == "getPrice") {
            if (!$this->getType() || !$this->getCity() || !$this->getLongitude() || !$this->getLatitude()) {
                throw new AloPeykApiException('Fill Out This Attributes in all destinations: type , city , latitude , longitude');
            }
        }

        if ($endPoint == "createOrder") {
            if (!$this->getAddress()) {
                throw new AloPeykApiException('Fill Out This Attributes in all destinations: type , city , latitude , longitude , address');
            }
        }

        return true;
    }


}
