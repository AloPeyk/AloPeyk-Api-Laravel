# AloPeyk-Api-Laravel

[![License](https://poser.pugx.org/alopeyk/aloPeyk-api-laravel/license)](https://packagist.org/packages/alopeyk/aloPeyk-api-laravel)
[![Latest Stable Version](https://poser.pugx.org/alopeyk/aloPeyk-api-laravel/v/stable)](https://packagist.org/packages/alopeyk/aloPeyk-api-laravel)
[![Monthly Downloads](https://poser.pugx.org/alopeyk/aloPeyk-api-laravel/d/monthly)](https://packagist.org/packages/alopeyk/aloPeyk-api-laravel)


This package is built to facilitate application development for AloPeyk RESTful API. For more information about this api, please visit [AloPeyk Documents](https://docs.alopeyk.com/)

:warning: Use v1.0.7 for Laravel 5

## Installation
First of all, You need an [ACCESS-TOKEN](https://alopeyk.com/contact?unit=sales). 
All Alopeyk API endpoints support the JWT authentication protocol. To start sending authenticated HTTP requests you will need to use your JWT authorization token which is sent to you.
Then you can install this package by using [Composer](http://getcomposer.org), running this command:

```sh
composer require alopeyk/alopeyk-api-laravel
```
Link to Packagist: https://packagist.org/packages/alopeyk/alopeyk-api-laravel


To install this package you will need: 
- Laravel 7+

Install via composer 
edit your composer.json to require the package.
```
"require": {
    "alopeyk/alopeyk-api-laravel": "2.*"
}
```

Then run ```  composer update  ``` in your terminal to pull it in.

Once this has finished, you will need to add the service provider to the providers array in your app.php config as follows:

```php
AloPeyk\Api\RESTful\Provider\AloPeykServiceProvider::class
```

Next, also in the app.php config file, under the aliases array, you may want to add the JWTAuth facade.
```php
'AloPeykApiHandler' => AloPeyk\Api\RESTful\Facade\AloPeykApiHandlerFacade::class,
```

Finally, you will want to publish the config using the following command:
```php
$ php artisan vendor:publish --tag=alopeyk
```

## Usage

```php
<?php

namespace App\Http\Controllers;

use AloPeyk\Api\RESTful\Model\Address;
use AloPeyk\Api\RESTful\Model\Location;
use AloPeyk\Api\RESTful\Model\Order;
use AloPeykApiHandler;

class TestController extends Controller
{
    public function authenticate()
    {
        dd(AloPeykApiHandler::authenticate());
    }

    public function getLocation()
    {
        dd(Location::getAddress("35.732595", "51.413379"));
    }

    public function locSuggestion()
    {
        dd(Location::getSuggestions("آرژ"));
    }

    public function getPrice()
    {
        /*
         * Create Origin Address
         */
        $origin = new Address('origin', 'tehran', '35.723711', '51.410547');
        /*
         * Create First Destination
         */
        $firstDest = new Address('destination', 'tehran', '35.728457', '51.436969');

        /*
         * Create Second Destination
         */
        $secondDest = new Address('destination', 'tehran', '35.729379', '51.418151');

        /*
         * Create New Order
         */
        $order = new Order('motor_taxi', $origin, [$firstDest, $secondDest]);
        $order->setHasReturn(true);

        $apiResponse = $order->getPrice();

        dd($apiResponse);
    }

    public function createOrder()
    {
        /*
         * Create Origin: Behjat Abad
         */
        $origin = new Address('origin', 'tehran', '35.755460', '51.416874');
        $origin->setAddress("... Behjat Abad, Tehran");
        $origin->setDescription("Behjat Abad");                                            // optional
        $origin->setUnit("44");                                                            // optional
        $origin->setNumber("1");                                                           // optional
        $origin->setPersonFullname("Leonardo DiCaprio");                                   // optional
        $origin->setPersonPhone("09370000000");                                            // optional

        /*
         * Create First Destination: N Sohrevardi Ave
         */
        $firstDest = new Address('destination', 'tehran', '35.758495', '51.442550');
        $firstDest->setAddress("... N Sohrevardi Ave, Tehran");
        $firstDest->setDescription("N Sohrevardi Ave");                                    // optional
        $firstDest->setUnit("55");                                                         // optional
        $firstDest->setNumber("2");                                                        // optional
        $firstDest->setPersonFullname("Eddie Redmayne");                                   // optional
        $firstDest->setPersonPhone("09380000000");                                         // optional


        /*
         * Create Second Destination: Ahmad Qasir Bokharest St
         */
        $secondDest = new Address('destination', 'tehran', '35.895452', '51.589632');
        $secondDest->setAddress("... Ahmad Qasir Bokharest St, Tehran");
        $secondDest->setDescription("Ahmad Qasir Bokharest St");                            // optional
        $secondDest->setUnit("66");                                                         // optional
        $secondDest->setNumber("3");                                                        // optional
        $secondDest->setPersonFullname("Matt Damon");                                       // optional
        $secondDest->setPersonPhone("09390000000");                                         // optional

        $order = new Order('motor_taxi', $origin, [$firstDest, $secondDest]);
        $order->setHasReturn(true);

        $apiResponse = $order->create($order);
    
        dd($apiResponse);
    }

    public function getOrderDetails()
    {        
        // $orderID = "   352 ";     // works fine as 300
        // $orderID = "   352<p>";   // works fine as 300
        // $orderID = '';            // throws AloPeykException
        // $orderID = null;          // throws AloPeykException
        $orderID = 352;
        $apiResponse = Order::getDetails($orderID);
            
        dd($apiResponse);
    }

    public function cancelOrder()
    {
        $apiResponse = null;
        
        // $orderID = "   300 ";     // works fine as 300
        // $orderID = "   300<p>";   // works fine as 300
        // $orderID = '';            // throws AloPeykException
        // $orderID = null;          // throws AloPeykException
        $orderID = 353;
        $apiResponse = Order::cancel($orderID);
        
        dd($apiResponse);
    }
}

```




## License

This package is released under the __MIT license__.

Copyright (c) 2012-2017 Markus Poerschke

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
