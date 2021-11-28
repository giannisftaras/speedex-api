# Speedex SOAP API for PHP
[Speedex](https://www.speedex.gr/ "Speedex") is a Greek courier company. This package is a simple PHP handler for its SOAP API.

At the moment only the following features are supported:
- Get voucher data
- Check if the package has been delivered
- Check if the voucher is valid and present in the database

If you want more additions you can [contact me](mailto:me@giannisftaras.dev "contact me") or submit a feature request.

## Installation:
#### Prerequisites:
You need to have enabled the SOAP module on your server. To enable it:
- Edit the php.ini file and uncomment the `;extension=soap` line
- Restart the PHP-FPM service and Apache server

There are two ways to install the package.

**1. With composer:**
```bash
composer require giannisftaras/speedex-api
```

**2. Manually:**
Download this repository, extract the zip file and include the `SpeedexAPI.php` file, as seen in the [tests](https://github.com/giannisftaras/speedex-api/blob/main/tests/index.php "tests").

## Usage
```php
<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use SpeedexAPI\SpeedexAPI;

$sp_api = new SpeedexAPI();
$voucher_id = '<THE VOUCHER NUMBER>';
$voucher = $sp_api->get_voucher($voucher_id);

if ($voucher->is_valid()) {
	var_dump($voucher->get_data());
	var_dump($voucher->is_delivered());
}

?>
```

## Configuration
The API works directly out of the box as is, but if you want you can specify some options.
```php
$options = [
    'cache' => 2
];
$sp_api = new SpeedexAPI($options);
```

#### Available options:
|  configuration | options | Type | Description | Default |
| ------------ | ------------ | ------------ |  ------------ | 
|  cache | 0-3 | Integer | Specify the [WSDL cache](https://www.php.net/manual/en/soap.configuration.php#ini.soap.wsdl-cache "WSDL cache") level with a integer | 2 `'WSDL_CACHE_MEMORY'` |
| timeout  |  0-100 | Integer | Timeout in seconds if the server does not respond | 5 |
| exceptions | true, false | Boolean | Show or hide server side exceptions | true |