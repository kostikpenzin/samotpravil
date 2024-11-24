<?php

namespace test;

use kostikpenzin\samotpravil\Client;

require dirname(__DIR__) . '/vendor/autoload.php';

$secretKey = 'xxxx';

$client = new Client($secretKey);

// Send email
$res = $client->sendEmail(
    'penzin85@gmail.com',
    'Hi, {{ params.user }}', 
    $body,
    'info@samotpravil.ru',
    [
        'params' => [
            'name_from' => 'Penzin Konstantin'
        ]
    ]
);
var_dump($res);

// Get status
$res = $client->getStatus(['email' => 'penzin85@gmail.com']);
var_dump($res);

// Get statistics
$res = $client->getStatistics('2025-01-01', '2025-01-31', ['limit' => 100, 'cursor_next' => 0]);
var_dump($res);

// Get non-delivery report
$res = $client->getNonDeliveryByDate('2025-01-01', '2025-01-31', ['limit' => 100, 'cursor_next' => 0]);
var_dump($res);

// Get FBL report
$res = $client->getFblReportByDate('2025-01-01', '2025-01-31', ['limit' => 100, 'cursor_next' => 0]);
var_dump($res);

// Search in stop list
$res = $client->stopListSearch('penzin85@gmail.com');
var_dump($res);

// Add to stop list
$res = $client->stopListAdd('penzin85@gmail.com', "samotpravil.ru");
var_dump($res);

// Remove from stop list
$res = $client->stopListRemove('penzin85@gmail.com', "samotpravil.ru");
var_dump($res);








