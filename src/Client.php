<?php

namespace kostikpenzin\samotpravil;

use Exception;
use StdClass;

class Client
{

    public function __construct(private string $api_key, private string $host = 'https://api.samotpravil.com/') {}

    
    /**
     * Make a request to the Samotpravil API.
     *
     * @param string $method The HTTP method to use for the request.
     * @param string $endpoint The API endpoint to call.
     * @param array|null $data The data to pass to the API endpoint.
     *
     * @return StdClass The API response decoded as a PHP stdClass.
     *
     * @throws Exception If the API request fails or the response is invalid.
     */
    private function makeRequest(string $method = 'GET', string $endpoint, ?array $data = []): StdClass
    {

        $url = sprintf('%s/%s', $this->host, $endpoint);

        $client = new GuzzleHttp\Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->api_key
        ];
        $res = $client->request($method, $url, $headers, json_encode($data));

        if ($res->getStatusCode() === 200) {
            $json = json_decode($res->getBody());

            if ($json->status == 'OK' || $json->status == 'ok') { // @phpstan-ignore-line
                return $json;
            } else {
                throw new Exception($json->message);
            }
        }

        throw new Exception('Couldn’t send message to API');
    }

    /**
     * Validate email address
     *
     * @param string $email
     *
     * @throws Exception
     */
    private function _validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }
    }

    /**
     * Send email using Samotpravil API
     *
     * @param string $emailTo Email address of the recipient
     * @param string $subject Email subject
     * @param string $messageText Email message body
     * @param string $emailFrom Email address of the sender
     * @param array $kwargs Optional parameters:
     *  - params: array of parameters for templates
     *  - x_track_id: track id
     *  - track_open: track open event
     *  - track_click: track click event
     *  - track_domain: domain for tracking
     *  - check_stop_list: check stop list
     *  - check_local_stop_list: check local stop list
     *  - domain_for_dkim: domain for DKIM signature
     *  - headers: headers
     *
     * @return StdClass API response
     *
     * @throws Exception
     */
    public function sendEmail($emailTo, $subject, $messageText, $emailFrom, array $kwargs = [])
    {
        $this->_validateEmail($emailTo);
        $this->_validateEmail($emailFrom);

        $data = [
            "email_to" => $emailTo,
            "subject" => $subject,
            "message_text" => $messageText,
            "email_from" => isset($kwargs['name_from']) ? "{$kwargs['name_from']} <$emailFrom>" : $emailFrom,
        ];

        $optionalFields = [
            "params",
            "x_track_id",
            "track_open",
            "track_click",
            "track_domain",
            "check_stop_list",
            "check_local_stop_list",
            "domain_for_dkim",
            "headers"
        ];

        foreach ($optionalFields as $field) {
            if (isset($kwargs[$field])) {
                $data[$field] = $kwargs[$field];
            }
        }

        return $this->makeRequest('POST', "api/v2/mail/send", $data);
    }


    /**
     * Get status of sent emails
     *
     * @param array $kwargs Optional parameters:
     *  - email: email address of the recipient
     *  - issue_id: issue id
     *  - x_track_id: track id
     *
     * @return StdClass API response
     *
     * @throws Exception
     */
    public function getStatus(array $kwargs = [])
    {
        $data = [];
        foreach ($kwargs as $key => $value) {
            if ($value !== null) {
                $data[$key] = $value;
            }
        }
        return $this->makeRequest('GET', "api/v2/issue/status", $data);
    }


    /**
     * Get statistics of sent emails between specified dates.
     *
     * @param string $dateFrom Start date for statistics in 'YYYY-MM-DD' format
     * @param string $dateTo End date for statistics in 'YYYY-MM-DD' format
     * @param array $kwargs Optional parameters:
     *  - limit: number of records to retrieve (default is 100)
     *  - cursor_next: cursor for pagination to get the next set of results
     *
     * @return StdClass API response
     *
     * @throws Exception
     */
    public function getStatistics(string $dateFrom, string $dateTo, array $kwargs = [])
    {
        $data = [
            "date_from" => $dateFrom,
            "date_to" => $dateTo,
            "limit" => isset($kwargs['limit']) ? $kwargs['limit'] : 100,
            "cursor_next" => $kwargs['cursor_next'] ?? 0,
        ];
        return $this->makeRequest('GET', "api/v2/issue/statistics", $data);
    }


    /**
     * Retrieve non-delivery report for emails between specified dates.
     *
     * @param string $dateFrom Start date for the report in 'YYYY-MM-DD' format
     * @param string $dateTo End date for the report in 'YYYY-MM-DD' format
     * @param array $kwargs Optional parameters:
     *  - limit: number of records to retrieve (default is 100)
     *  - cursor_next: cursor for pagination to get the next set of results
     *
     * @return StdClass API response
     *
     * @throws Exception
     */
    public function getNonDeliveryByDate(string $dateFrom, string $dateTo, array $kwargs = [])
    {
        $data = [
            "date_from" => $dateFrom,
            "date_to" => $dateTo,
            "limit" => isset($kwargs['limit']) ? $kwargs['limit'] : 100,
            "cursor_next" => $kwargs['cursor_next'] ?? 0,
        ];
        return $this->makeRequest('GET', "api/v2/blist/report/non-delivery", $data);
    }


    /**
     * Retrieve FBL report for emails between specified dates.
     *
     * @param string $dateFrom Start date for the report in 'YYYY-MM-DD' format
     * @param string $dateTo End date for the report in 'YYYY-MM-DD' format
     * @param array $kwargs Optional parameters:
     *  - limit: number of records to retrieve (default is 100)
     *  - cursor_next: cursor for pagination to get the next set of results
     *
     * @return StdClass API response
     *
     * @throws Exception
     */
    public function getFblReportByDate(string $dateFrom, string $dateTo, array $kwargs = [])
    {
        $data = [
            "date_from" => $dateFrom,
            "date_to" => $dateTo,
            "limit" => isset($kwargs['limit']) ? $kwargs['limit'] : 100,
            "cursor_next" => $kwargs['cursor_next'] ?? 0,
        ];
        return $this->makeRequest('GET', "api/v2/blist/report/fbl", $data);
    }


    /**
     * Search for an email in the stop list.
     *
     * @param string $email The email address to search in the stop list.
     *
     * @return StdClass API response containing the results of the search.
     *
     * @throws Exception if the email is invalid or the API request fails.
     */
    public function stopListSearch(string $email)
    {
        $this->_validateEmail($email);
        $data = ["email" => $email];
        return $this->makeRequest('GET', "api/v2/stop-list/search", $data);
    }


    /**
     * Add an email to the stop list.
     *
     * @param string $email The email address to add to the stop list.
     * @param string $domain The domain to set as the mail_from value.
     *
     * @return StdClass API response containing the results of the add operation.
     *
     * @throws Exception if the email is invalid or the API request fails.
     */
    public function stopListAdd(string $email, string $domain)
    {
        $this->_validateEmail($email);
        $endpoint =         $data = [
            "email" => $email,
            "mail_from" => "info@$domain",
        ];
        return $this->makeRequest('POST', "api/v2/stop-list/add", $data);
    }


    /**
     * Remove an email from the stop list.
     *
     * @param string $email The email address to remove from the stop list.
     * @param string $domain The domain to set as the mail_from value.
     *
     * @return StdClass API response containing the results of the remove operation.
     *
     * @throws Exception if the email is invalid or the API request fails.
     */
    public function stopListRemove(string $email, string $domain)
    {
        $this->_validateEmail($email);
        $data = [
            "email" => $email,
            "mail_from" => "info@$domain",
        ];
        return $this->makeRequest('POST', "api/v2/stop-list/remove", $data);
    }


    /**
     * Get a list of all domains that have been added to the list of allowed domains.
     *
     * @return StdClass API response containing the list of domains.
     */
    public function getDomains() {
        return $this->makeRequest('GET', "api/v2/blist/domains");
    }


    /**
     * Add a domain to the list of allowed domains.
     *
     * @param string $domain The domain to add.
     *
     * @return StdClass API response containing the results of the add operation.
     *
     * @throws Exception if the API request fails.
     */
    public function domainAdd(string $domain) {
        $endpoint = ;
        $data = ["domain" => $domain];
        return $this->makeRequest('POST', "api/v2/blist/domains/add", $data);
    }


    /**
     * Remove a domain from the list of allowed domains.
     *
     * @param string $domain The domain to remove.
     *
     * @return StdClass API response containing the results of the remove operation.
     *
     * @throws Exception if the API request fails.
     */
    
    public function domainRemove(string $domain) {
        $data = ["domain" => $domain];
        return $this->makeRequest('POST', "api/v2/blist/domains/remove", $data);
    }


    /**
     * Verify the given domain using Samotpravil API.
     *
     * @param string $domain The domain to verify.
     *
     * @return StdClass API response containing the verification results.
     *
     * @throws Exception if the API request fails.
     */
    public function domainCheckVerification(string $domain) {
        $data = ["domain" => $domain];
        return $this->makeRequest('POST', "api/v2/blist/domains/verify", $data);
    }

}
