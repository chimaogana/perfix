<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client; // Import the Guzzle client
use GuzzleHttp\Exception\RequestException;

class CrmController extends Controller
{
    protected $authToken;
    protected $httpClient; // GuzzleHttp Client

    public function __construct()
    {
        $this->authToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiTHVpcyBTYWxhemFyIiwibmFtZSI6IlRlc3QgSW50ZWdyYXRpb24iLCJBUElfVElNRSI6MTcyNDI3MTIzN30.SYuNAyjeQnHxGGZucwt5ZvmnrTt1JiiPnFxCg3aaeMs';
        $this->httpClient = new Client(); // Initialize Guzzle client
    }

    /**
     * Get CRM Data for a specific customer.
     */
    public function getCrmData($customer)
    {
        // Construct the API URL
        $url = "https://keoscrm.org/api/customers/{$customer}";

        try {
            // Set headers and make the request
            $response = $this->httpClient->request('GET', $url, [
                'headers' => [
                    'authtoken' => $this->authToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            // Return CRM API response
            return response()->json(json_decode($response->getBody(), true));

        } catch (RequestException $e) {
            // Handle different response codes
            if ($e->getResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                if ($statusCode == 404) {
                    // Return custom JSON response for customer not found
                    return response()->json([
                        'status' => false,
                        'message' => 'Customer not found',
                    ], 404);
                }
            }

            // Return a generic error response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching data.',
            ], 500);
        }
    }

    /**
     * Validate customer ID.
     */
    public function validateId($customer)
{
    // Construct the API URL
    $url = "https://keoscrm.org/api/customers/{$customer}";

    try {
        // Set headers and make the request
        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'authtoken' => $this->authToken,
                'Content-Type' => 'application/json',
            ],
        ]);

        // Check if the response status is successful (200 OK)
        if ($response->getStatusCode() == 200) {
            // Customer ID exists
            return response()->json(['status' => true]);
        }

    } catch (RequestException $e) {
        // Handle different response codes
        if ($e->getResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode == 404) {
                // Customer ID does not exist
                return response()->json(['status' => false]);
            }
        }
        // Return a generic error response
        return response()->json(['status' => false, 'message' => 'An error occurred while validating the customer ID.'], 500);
    }   

    // In case of any unexpected behavior
    return response()->json(['status' => false]);
}

}
