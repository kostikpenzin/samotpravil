# Samotpravil for PHP

This library is forked by kostikpenzin/samotpravil, it helps you send e-mails through [Samotpravil](https://samotpravil.ru) in PHP 8.0 and above.

## Installation

Install the library using [Composer](https://getcomposer.org/):

```
$ composer require kostikpenzin/samotpravil
```

## Usage

```
use kostikpenzin\samotpravil\Client;

require dirname(__DIR__) . '/vendor/autoload.php';

$secretKey = 'xxxx';

$client = new Client($secretKey);
```

### sendEmail: Sends an email using the Samotpravil API.

```
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
```


getStatus: Gets the status of sent emails.
getStatistics: Gets statistics of sent emails between specified dates.
getNonDeliveryByDate: Retrieves non-delivery report for emails between specified dates.
getFblReportByDate: Retrieves FBL report for emails between specified dates.
stopListSearch: Searches for an email in the stop list.
stopListAdd: Adds an email to the stop list.
stopListRemove: Removes an email from the stop list.
getDomains: Gets a list of all domains that have been added to the list of allowed domains.
domainAdd: Adds a domain to the list of allowed domains.
domainRemove: Removes a domain from the list of allowed domains.
domainCheckVerification: Verifies the given domain using Samotpravil API.