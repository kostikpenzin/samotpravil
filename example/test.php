<?php

namespace Example;

use kostikpenzin\samotpravil\Client;
use kostikpenzin\samotpravil\SendMessage;

require dirname(__DIR__) . '/vendor/autoload.php';

$host = 'xxxx';
$secretKey = 'xxxx';
$params = [
];

// Create a new Postal client using the server key you generate in the web interface
$client = new Client($host, $secretKey);

$message = new SendMessage($params);



