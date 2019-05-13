<?php

namespace AloPeyk\Api\RESTful\Model;

use AloPeyk\Api\RESTful\ApiHandler;
use AloPeyk\Api\RESTful\Config\Configs;
use AloPeyk\Api\RESTful\Exception\AloPeykApiException;
use AloPeyk\Api\RESTful\Exception\InvalidAddressException;
use AloPeyk\Api\RESTful\Exception\InvalidOrderException;
use AloPeyk\Api\RESTful\Exception\InvalidTransportTypeException;
use AloPeyk\Api\RESTful\Validator\AloPeykValidator;

class Order
{
    // Attributes ------------------------------------------------------------------------------------------------------
    private $transportType;
    private $city;
    private $originAddress;
    private $destinationsAddress;
    private $hasReturn;
    private $credit = true;
    private $cashed = false;
    private $payAtDestination = false;
    private $scheduled_at;

    public function __construct($transportType, $originAddress, $destinationsAddress, $scheduled_at = null)
    {
        $this->setTransportType($transportType);
        $this->addOriginAddress($originAddress);
        $this->setHasReturn(false);
        $this->setCredit(true);
        $this->setCashed(false);
        $this->setPayAtDestination(false);

        if($scheduled_at)
        {
            $this->setScheduledAt($scheduled_at);
        }

        if($this->cashed){
            $this->setCredit(false);
        }else{
            $this->setCredit(true);
            $this->setPayAtDestination(false);
        }

        $this->destinationsAddress = [];
        if (!is_array($destinationsAddress)) {
            $this->addDestinationsAddress($destinationsAddress);
        } else {
            foreach ($destinationsAddress as $destAddress) {
                $this->addDestinationsAddress($destAddress);
            }
        }
    }
    // Setters ---------------------------------------------------------------------------------------------------------
    /**
     * @param $transportType
     * @throws AloPeykApiException
     */
    public function setTransportType($transportType)
    {
        $transportType = AloPeykValidator::sanitize($transportType);
        if (!in_array($transportType, array_keys(Configs::TRANSPORT_TYPES))) {
            throw new AloPeykApiException('Transport Type is not correct');
        }
        $this->transportType = $transportType;
    }

    /**
     * Set scheduled_at attribute
     * @param $scheduled_at
     */
    public function setScheduledAt($scheduled_at)
    {
        $this->scheduled_at = $scheduled_at;
    }

    /**
     * @param $originAddress
     * @throws AloPeykApiException
     */
    public function addOriginAddress($originAddress)
    {
        if (!$originAddress instanceof Address) {
            throw new AloPeykApiException('Origin Address is not valid!');
        }
        if ($originAddress->getType() != 'origin') {
            throw new AloPeykApiException('Type Of Origin Address is not correct! please change it to `origin`.');
        }
        $this->originAddress = $originAddress;
        $this->city = $originAddress->getCity();
    }
    /**
     * @param $newDestinationsAddress
     * @throws AloPeykApiException
     */
    public function addDestinationsAddress($newDestinationsAddress)
    {
        if (!$newDestinationsAddress instanceof Address) {
            throw new AloPeykApiException('Destination Address is not valid!');
        }
        if ($newDestinationsAddress->getType() != 'destination') {
            throw new AloPeykApiException('Type Of Destination Address is not correct! please change it to `destination`.');
        }
        array_push($this->destinationsAddress, $newDestinationsAddress);
    }
    /**
     * @param mixed $hasReturn
     */
    public function setHasReturn($hasReturn)
    {
        $this->hasReturn = $hasReturn;
    }
    /**
     * @param mixed $credit
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
    }
    /**
     * @param mixed $cashed
     */
    public function setCashed($cashed)
    {
        $this->cashed = $cashed;
    }
    /**
     * @param mixed $payAtDestination
     */
    public function setPayAtDestination($payAtDestination)
    {
        $this->payAtDestination = $payAtDestination;
    }
    // Getters ---------------------------------------------------------------------------------------------------------
    /**
     * @return mixed
     */
    public function getTransportType()
    {
        return $this->transportType;
    }
    /**
     * @return mixed
     */
    public function getOriginAddress()
    {
        return $this->originAddress;
    }
    /**
     * @return mixed
     */
    public function getDestinationsAddress()
    {
        return $this->destinationsAddress;
    }
    /**
     * @return mixed
     */
    public function getDestinationsAddressArray()
    {
        $addresses = [];
        foreach ($this->getDestinationsAddress() as $address) {
            array_push($addresses, $address->toArray('destination'));
        }
        return $addresses;
    }
    /**
     * @return mixed
     */
    public function getHasReturn()
    {
        return $this->hasReturn;
    }
    /**
     * @return mixed
     */
    public function getCredit()
    {
        return $this->credit;
    }
    /**
     * @return mixed
     */
    public function getCashed()
    {
        return $this->cashed;
    }
    /**
     * @return mixed
     */
    public function getPayAtDestination()
    {
        return $this->payAtDestination;
    }
    // Actions ---------------------------------------------------------------------------------------------------------
    /**
     * @return mixed
     */
    public function create()
    {
        return ApiHandler::createOrder($this);
    }
    /**
     * @return mixed
     */
    public function getPrice()
    {
        return ApiHandler::getPrice($this);
    }

    /**
     * @return string scheduled_at
     */
    public function getScheduledAt()
    {
        return $this->scheduled_at;
    }

    /**
     * @param $orderID
     * @return mixed
     */
    public static function cancel($orderID, $comment)
    {
        return ApiHandler::cancelOrder($orderID, $comment);
    }

    /**
     * @param $orderID
     * @return mixed
     */
    public static function finish($orderID, $params)
    {
        return ApiHandler::finishOrder($orderID, $params);
    }

    /**
     * @param $orderID
     * @return mixed
     */
    public static function getDetails($orderID)
    {
        return ApiHandler::getOrderDetail($orderID);
    }
    // Utilities -------------------------------------------------------------------------------------------------------
    /**
     * @param $endPoint
     * @return array
     */
    public function toArray($endPoint)
    {
        $this->isValid();
        $orderArray = [
             'city' => $this->city,
             'transport_type' => $this->getTransportType(),
             'has_return' => $this->getHasReturn(),
             'credit' => $this->getCredit(),
             'cashed' => $this->getCashed(),
             'pay_at_dest' => $this->getPayAtDestination(),
             'scheduled_at' => $this->getScheduledAt()
        ];
        $orderArray['addresses'] = array_merge(
             [$this->getOriginAddress()->toArray($endPoint)],
             $this->getDestinationsAddressArray()
        );
        return $orderArray;
    }
    /**
     * @return bool
     * @throws AloPeykApiException
     */
    private function isValid()
    {
        // CHECK CITY
        if (AloPeykValidator::sanitize($this->city) != $this->getOriginAddress()->getCity()) {
            throw new InvalidAddressException('is not valid!', 'Origin');
        }
        // CHECK TRANSPORT_TYPE
        if (!in_array($this->getTransportType(), array_keys(Configs::TRANSPORT_TYPES))) {
            throw new InvalidOrderException('Transport Type is not correct!');
        }
        // CHECK ORIGIN
        if (!$this->getOriginAddress()) {
            throw new InvalidOrderException('Each Order Requires One Origin Address!');
        }
        if ($this->getOriginAddress()->getType() != 'origin') {
            throw new InvalidAddressException('Type Of Origin Address is not correct! please change it to `origin`.', 'Origin');
        }
        // CHECK DESTINATIONS
        if (count($this->getDestinationsAddress()) < 1) {
            throw new InvalidOrderException('Each Order Requires At Least One Destination!');
        }
        foreach ($this->getDestinationsAddress() as $destination) {
            if ($destination->getType() != 'destination') {
                throw new InvalidAddressException('Type of Destination Address is not correct! please change it to `destination`.', 'Destination');
            }
        }
        return true;
    }
}