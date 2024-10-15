<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NetPayAPIController extends Controller
{
    public function getToken(Request $request)
    {
        $response = Http::withHeaders([
            'X-API-KEY' => 'sUteyGvTzslWLz6ivqsdc7E01',
            'X-API-USERNAME' => 'LGU Test_UAT',
            'X-API-PASSWORD' => 'tpPlzmQYoMlSvTv9Vm2cgolxT',
        ])->get('https://demo-api.netglobalsolutions.net/api/generate_token');

        if ($response->successful()) {

            $responseData = $response->json();
            $token = $responseData['data']['token'];

            if (is_null($token) || empty($token)) {
                return response()->json([
                    "status" => "400",
                    "message" => "failed",
                    "data" => [],
                ], 400); // Bad Request
            }
            $apiUrl = 'https://demo-api.netglobalsolutions.net/payment/cashin';

            // Define headers (if required by the API)
            $headers = [
                'Authorization' => 'Bearer '.$token,
                'X-API-KEY' => 'sUteyGvTzslWLz6ivqsdc7E01',
                'Accept' => 'application/json',
            ];
    
            // Define the data to be posted
            $postData = [
                'amount' => $request->input('amount'),
                'reference_number' =>  $request->input('reference_number'),
                'payment_type' =>  $request->input('payment_type'),
                'name' =>  $request->input('name'),
                'phone_number' =>  $request->input('phone_number'),
                'email' =>  $request->input('email'),
                'notify_url' =>  $request->input('notify_url'),
    
            ];
    
            // Make the POST request with headers and data
            $response = Http::withHeaders($headers)->post($apiUrl, $postData);
    
            // Handle the response
            if ($response->successful()) {
                return response()->json([
                    'message' => 'Data successfully sent to the external API',
                    'response' => $response->json(),
                ], 200);
            } else {
                // Handle the error response
                return response()->json([
                    'message' => 'Failed to send data to the external API',
                    'status' => $response->status(),
                    'error' => $response->json(),
                ], $response->status());
            }
            // return response()->json([
                
            //     "status" => "200",
            //     "message" => "failed",
            //     "data" => ['token' =>$token],
            // ], 200); // Success
        }

        return response()->json([
            'status' => $response->status(),
            'message' => 'failed',
            "data" => [],
        ], $response->status());
    }

    public function PayTransaction(Request $request)
    {

        $apiUrl = 'https://demo-api.netglobalsolutions.net/payment/cashin';

        // Define headers (if required by the API)
        $headers = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJBY2Nlc3MiOiJ0cnVlIiwiYWFjY291bnRfaWQiOiIxNCIsImlhdCI6MTcyODk3NDQ3NywiZXhwIjoxNzI5MDYwODc3fQ.lmoX8S3oOuegkTwIf-sBWCPNH-g8gt4iV-z-3wAbeBo',
            'X-API-KEY' => 'sUteyGvTzslWLz6ivqsdc7E01',
            'Accept' => 'application/json',
        ];

        // Define the data to be posted
        $postData = [
            'amount' => $request->input('amount'),
            'reference_number' =>  $request->input('reference_number'),
            'payment_type' =>  $request->input('payment_type'),
            'name' =>  $request->input('name'),
            'phone_number' =>  $request->input('phone_number'),
            'email' =>  $request->input('email'),
            'notify_url' =>  $request->input('notify_url'),

        ];

        // Make the POST request with headers and data
        $response = Http::withHeaders($headers)->post($apiUrl, $postData);

        // Handle the response
        if ($response->successful()) {
            return response()->json([
                'message' => 'Data successfully sent to the external API',
                'response' => $response->json(),
            ], 200);
        } else {
            // Handle the error response
            return response()->json([
                'message' => 'Failed to send data to the external API',
                'status' => $response->status(),
                'error' => $response->json(),
            ], $response->status());
        }
    }
}
