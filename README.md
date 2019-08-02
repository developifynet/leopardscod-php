# Leopards Courier COD (Cash on Delivery) API Wrapper for PHP

<a href="https://travis-ci.org/developifynet/leopardscod-php"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/developifynet/leopardscod-php"><img src="https://poser.pugx.org/developifynet/leopardscod-php/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/developifynet/leopardscod-php"><img src="https://poser.pugx.org/developifynet/leopardscod-php/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/developifynet/leopardscod-php"><img src="https://poser.pugx.org/developifynet/leopardscod-php/license.svg" alt="License"></a>

This composer package offers quick booking packets via APIs on Leopards COD (Cash on Delivery) for your Laravel applications.

## Installation

Begin by pulling in the package through Composer.

```bash
composer require developifynet/leopardscod-php
```

## Laravel Framework Usage

Within your controllers, you can call LeopardsCOD facade and can send perform different operations.

### Set Credentials

```php
use \Developifynet\LeopardsCOD\LeopardsCOD;
public function index()
{
    LeopardsCOD::setCredentials(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
    ));
}
```

or setting up with data. See below endpoints where credentials are porvided within function data


### Get All Cities

```php
use \Developifynet\LeopardsCOD\LeopardsCOD;
public function index()
{
    $response = LeopardsCOD::setCredentials(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
    ))->getAllCities();
}
```

### Book a Packet

```php
use \Developifynet\LeopardsCOD\LeopardsCOD;
public function index()
{
    $response = LeopardsCOD::setCredentials(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
    ))->bookPacket(array(
        'booked_packet_weight' => '200',
        'booked_packet_vol_weight_w' => '',
        'booked_packet_vol_weight_h' => '',
        'booked_packet_vol_weight_l' => '',
        'booked_packet_no_piece' => '1',
        'booked_packet_collect_amount' => '1000',
        'booked_packet_order_id' => '1001',
        'origin_city' => 'string',                  /** Params: 'self' or 'integer_value' e.g. 'origin_city' => 'self' or 'origin_city' => 789 (where 789 is Lahore ID)
                                                     * If 'self' is used then Your City ID will be used.
                                                     * 'integer_value' provide integer value (for integer values read 'Get All Cities' api documentation)
                                                     */
            
        'destination_city' => 'string',             /** Params: 'self' or 'integer_value' e.g. 'destination_city' => 'self' or 'destination_city' => 789 (where 789 is Lahore ID)
                                                     * If 'self' is used then Your City ID will be used.
                                                     * 'integer_value' provide integer value (for integer values read 'Get All Cities' api documentation) 
                                                     */
        // Shipper Information
        'shipment_name_eng' => 'self',
        'shipment_email' => 'self',
        'shipment_phone' => 'self',
        'shipment_address' => 'self',
        // Consingee Information
        'consignment_name_eng' => 'John Doe',
        'consignment_email' => 'johndoe@example.com',
        'consignment_phone' => '+923330000000',
        'consignment_address' => 'Test Address is used here',
        'special_instructions' => 'n/a',
     ));
}
```

### Track Packet(s)

```php
use \Developifynet\LeopardsCOD\LeopardsCOD;
public function index()
{
    $response = LeopardsCOD::setCredentials(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
    ))->trackPacket(array(
        'track_numbers' => 'LEXXXXXXXX',            // E.g. 'XXYYYYYYYY' OR 'XXYYYYYYYY,XXYYYYYYYY,XXYYYYYY' 10 Digits each number
     ));
}
```

## Other Usage

Within your controllers, you can call LeopardsCODClient Object and call approperiate functions for your need.

You can set credentials in various ways. See below

### Set Credentials

```php
use \Developifynet\LeopardsCOD\LeopardsCODClient;
public function index()
{
    $leopards = new LeopardsCODClient(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
    ));
}
```

or using 'setCredentials'

```php
use \Developifynet\LeopardsCOD\LeopardsCODClient;
public function index()
{
    $leopards = new LeopardsCODClient();
    
    $leopards->setCredentials(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
    ));
}
```

or setting up with data. See below endpoints where credentials are porvided within function data


### Get All Cities

```php
use \Developifynet\LeopardsCOD\LeopardsCODClient;
public function index()
{
    $leopards = new LeopardsCODClient();
    
    $response = $leopards->getAllCities(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
    ));
}
```

### Book a Packet

```php
use \Developifynet\LeopardsCOD\LeopardsCODClient;
public function index()
{
    $leopards = new LeopardsCODClient();
    
    $response = $leopards->bookPacket(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
        'booked_packet_weight' => '200',
        'booked_packet_vol_weight_w' => '',
        'booked_packet_vol_weight_h' => '',
        'booked_packet_vol_weight_l' => '',
        'booked_packet_no_piece' => '1',
        'booked_packet_collect_amount' => '1000',
        'booked_packet_order_id' => '1001',
        'origin_city' => 'string',                  /** Params: 'self' or 'integer_value' e.g. 'origin_city' => 'self' or 'origin_city' => 789 (where 789 is Lahore ID)
                                                     * If 'self' is used then Your City ID will be used.
                                                     * 'integer_value' provide integer value (for integer values read 'Get All Cities' api documentation)
                                                     */
            
        'destination_city' => 'string',             /** Params: 'self' or 'integer_value' e.g. 'destination_city' => 'self' or 'destination_city' => 789 (where 789 is Lahore ID)
                                                     * If 'self' is used then Your City ID will be used.
                                                     * 'integer_value' provide integer value (for integer values read 'Get All Cities' api documentation) 
                                                     */
        // Shipper Information
        'shipment_name_eng' => 'self',
        'shipment_email' => 'self',
        'shipment_phone' => 'self',
        'shipment_address' => 'self',
        // Consingee Information
        'consignment_name_eng' => 'John Doe',
        'consignment_email' => 'johndoe@example.com',
        'consignment_phone' => '+923330000000',
        'consignment_address' => 'Test Address is used here',
        'special_instructions' => 'n/a',
     ));
}
```

### Track Packet(s)

```php
use \Developifynet\LeopardsCOD\LeopardsCODClient;
public function index()
{
    $leopards = new LeopardsCODClient();
    
    $response = $leopards->trackPacket(array(
        'api_key' => '<your_api_key>',              // API Key provided by LCS
        'api_password' => '<your_api_password>',    // API Password provided by LCS
        'enable_test_mode' => true,                 // [Optional] default value is 'false', true|false to set mode test or live
        'track_numbers' => 'LEXXXXXXXX',            // E.g. 'XXYYYYYYYY' OR 'XXYYYYYYYY,XXYYYYYYYY,XXYYYYYY' 10 Digits each number
     ));
}
```