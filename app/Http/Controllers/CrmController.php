<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CrmController extends Controller
{
    protected $authToken;

    public function __construct()
    {
        $this->authToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiTHVpcyBTYWxhemFyIiwibmFtZSI6IlRlc3QgSW50ZWdyYXRpb24iLCJBUElfVElNRSI6MTcyNDI3MTIzN30.SYuNAyjeQnHxGGZucwt5ZvmnrTt1JiiPnFxCg3aaeMs';
    }

    /**
     * Get CRM Data for a specific customer.
     */
    public function getCrmData($customer)
    {
        // Construct the API URL
        $url = "https://keoscrm.org/api/customers/{$customer}";

        // Set headers and make the request
        $response = Http::withHeaders([
            'authtoken' => $this->authToken,
            'Content-Type' => 'application/json',
        ])->get($url);

        // Check for HTTP status code
        if ($response->status() == 404) {
            // Return custom JSON response for customer not found
            return response()->json([
                'status' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        // Return CRM API response
        return response()->json($response->json());
    }

    /**
     * Validate customer ID.
     */
    public function validateId($customer)
    {
        // Construct the API URL
        $url = "https://keoscrm.org/api/customers/{$customer}";

        // Set headers and make the request
        $response = Http::withHeaders([
            'authtoken' => $this->authToken,
            'Content-Type' => 'application/json',
        ])->get($url);
        }

    }