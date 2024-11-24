# Samotpravil for PHP

This library is forked by kostikpenzin/samotpravil, it helps you send e-mails through [Samotpravil](https://samotpravil.ru) in PHP 8.0 and above.

[Official site Samotpravil](https://samotpravil.ru/)

Full documentation REST API: [documentation.samotpravil.ru](https://documentation.samotpravil.ru/)

## Installation

Install the library using [Composer](https://getcomposer.org/):

```
$ composer require kostikpenzin/samotpravil
```

## Usage

``` php
use kostikpenzin\samotpravil\Client;

require dirname(__DIR__) . '/vendor/autoload.php';

$secretKey = 'xxxx';
$client = new Client($secretKey);
```

### sendEmail: Sends an email using the Samotpravil API.

``` php
$res = $client->sendEmail(
    'penzin85@gmail.com',
    ''Hi, Penzin Konstantin. How are you? 😊', 
    $body,
    'info@samotpravil.ru',
    [
        'params' => [
            'name_from' => 'Penzin Konstantin'
        ]
    ]
);
```

### getStatus: Gets the status of sent emails.
``` php
$res = $client->getStatus(['email' => 'penzin85@gmail.com']);
var_dump($res);
```

### getStatistics: Gets statistics of sent emails between specified dates.
``` php
$res = $client->getStatistics('2025-01-01', '2025-01-31', 
    ['limit' => 100, 'cursor_next' => 0]);
var_dump($res);
```

### getNonDeliveryByDate: Retrieves non-delivery report for emails between specified dates.
``` php
$res = $client->getNonDeliveryByDate('2025-01-01', '2025-01-31', 
    ['limit' => 100, 'cursor_next' => 0]);
var_dump($res);
```

### getFblReportByDate: Retrieves FBL report for emails between specified dates.
``` php
$res = $client->getFblReportByDate('2025-01-01', '2025-01-31', 
    ['limit' => 100, 'cursor_next' => 0]);
var_dump($res);
```

### stopListSearch: Searches for an email in the stop list.
``` php
$res = $client->stopListSearch('penzin85@gmail.com');
var_dump($res);
```

### stopListAdd: Adds an email to the stop list.
``` php
$res = $client->stopListAdd('penzin85@gmail.com', "samotpravil.ru");
var_dump($res);
```

### stopListRemove: Removes an email from the stop list.
``` php
$res = $client->stopListRemove('penzin85@gmail.com', "samotpravil.ru");
var_dump($res);
```

### getDomains: Gets a list of all domains that have been added to the list of allowed domains.
``` php
$res = $client->getDomains();
var_dump($res);
```

### domainAdd: Adds a domain to the list of allowed domains.
``` php
$res = $client->domainAdd('samotpravil.ru');
var_dump($res);
```

### domainRemove: Removes a domain from the list of allowed domains.
``` php
$res = $client->domainRemove('samotpravil.ru');
var_dump($res);
```

### domainCheckVerification: Verifies the given domain using Samotpravil API.
``` php
$res = $client->domainCheckVerification('samotpravil.ru');
var_dump($res);
```
