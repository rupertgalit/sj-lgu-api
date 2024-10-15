<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TransactionController extends Controller
{


    public function GenTransactionId()
    {
        do {
            $uniqueNumber = mt_rand(1000000000, 9999999999);
        } while (Transaction::where('Trans_Id', $uniqueNumber)->exists());

        return $uniqueNumber;
    }

    public function GenReferenceNumber()
    {
        do {
            // Generate a random reference number
            $referenceNumber = 'REF-' . strtoupper(Str::random(8));
        } while (Transaction::where('Reference_No', $referenceNumber)->exists()); // Ensure it's unique

        return $referenceNumber;
    }


    public function index()
    {
        $posts = Transaction::all();
        return response()->json($posts);
    }
    public function sort(Request $request)
    {

        $defaultSortBy = 'id'; // Default to 'id' if null
        $defaultSortDirection = 'asc'; // Default to 'asc' if null

        // Get sort field and direction from the request
        $sortBy = $request->get('sort') ?? $defaultSortBy; // Use default if 'sort' is null
        $sortDirection = $request->get('direction') ?? $defaultSortDirection; // Use default if 'direction' is null

        // Validate the sort direction, ensuring it's either 'asc' or 'desc'
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = $defaultSortDirection;
        }


        $request->validate([
            'per_page' => 'integer|min:1|max:100',
            'page' => 'integer|min:1', // Optional: Limit per page from 1 to 100
        ]);

        $perPage = (int) ($request->get('per_page', 10));  // Default to 10 items per page
        $perPage = $perPage > 100 ? 100 : $perPage;  // Maximum 100 items per page

        // // Get the validated page number and per page value
        $page = $request->get('page', 1);      // Default to page 1 if not provided
        $perPage = $request->get('per_page', 4);  // Default to 10 items per page


        // // Perform the query and paginate results
        $query = Transaction::orderBy($sortBy, $sortDirection);
        $data = $query->paginate($perPage, ['*'], 'page', $page);

        // If the requested page is out of range, handle it here (optional)
        if ($data->currentPage() > $data->lastPage()) {
            return response()->json([
                'message' => 'Page not found.',
                'status' => 404,
            ], 404);
        }


        // Perform the query and paginate results
        $data = $query->paginate($perPage);
        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'total_pages' => $data->lastPage(),
            ]
        ]);
    }
    public function store(Request $request)
    {
        $customOrderId = $this->GenTransactionId();

        $customOrderRef = $this->GenReferenceNumber();

        $data = [
            'Categories' => $request->Categories,
            'Sub_Amount' => (float) $request->Sub_Amount,
            'Total_Amount' => (float)$request->Total_Amount,
            'Date_Created' => now(),
            'Penalties' => (float)$request->Penalties,
            'Status' => $request->Status,
            'Trans_Id' => $customOrderId,
            'Name' => $request->Name,
            'Company' => $request->Company,
            'Reference_No' =>  $customOrderRef,

        ];


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
                'Authorization' => 'Bearer ' . $token,
                'X-API-KEY' => 'sUteyGvTzslWLz6ivqsdc7E01',
                'Accept' => 'application/json',
            ];

            // Define the data to be posted
            $postData = [
                'amount' => $request->input('Total_Amount'),
                'reference_number' =>  $customOrderRef,
                'payment_type' =>  '3',
                'name' =>  $request->input('Name'),
                'phone_number' =>  '09345678100',
                'email' =>  'test@gmail.com',
                'notify_url' =>  'https://demo-api.netglobalsolutions.net/payment/callback',

            ];

            // Make the POST request with headers and data
            $response = Http::withHeaders($headers)->post($apiUrl, $postData);

            // Handle the response
            if ($response->successful()) {
                $save = Transaction::create($data);
                return response()->json([
                    "status" => "200",
                    'message' => 'success',
                    'response' => $response->json(),
                ], 200);
            } else {
                // Handle the error response
                return response()->json([
                    'status' => $response->status(),
                    'message' => 'failed',
                    'error' => $response->json(),
                ], $response->status());
            }
        }

        return response()->json([
            'status' => $response->status(),
            'message' => 'failed',
            "data" => [],
        ], $response->status());
    }

    public function show(string $id) {}

    public function update(Request $request, string $id) {}
    public function destroy(string $id)
    {
        $transaction = Transaction::find($id);

        if ($transaction) {
            $transaction->delete();
            return response()->json(['message' => 'transaction deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'transaction not found'], 404);
        }
    }
    public function search(Request $request)
    {

        $searchQuery = $request->input('search');
        $posts = Transaction::where('Trans_Id', 'like', '%' . $searchQuery . '%')
            ->orWhere('Reference_No', 'like', '%' . $searchQuery . '%')
            ->orWhere('Name', 'like', '%' . $searchQuery . '%')
            ->orWhere('Company', 'like', '%' . $searchQuery . '%')
            ->orWhere('Reference_No', 'like', '%' . $searchQuery . '%')
            ->orWhere('Categories', 'like', '%' . $searchQuery . '%')
            ->orWhere('Sub_Amount', 'like', '%' . $searchQuery . '%')
            ->orWhere('Total_Amount', 'like', '%' . $searchQuery . '%')
            ->orWhere('Date_Created', 'like', '%' . $searchQuery . '%')
            ->orWhere('Penalties', 'like', '%' . $searchQuery . '%')
            ->orWhere('Status', 'like', '%' . $searchQuery . '%')
            ->orWhere('created_at', 'like', '%' . $searchQuery . '%')
            ->orWhere('updated_at', 'like', '%' . $searchQuery . '%')
            ->get();

        return response()->json($posts);

        // $query = $request->input('query'); 
        // $users = Transaction::where('Categories', 'LIKE', "%{$query}%")->get();
        // return response()->json($users);

        // $query = Transaction::query();

        // if ($request->has('Trans_Id') ) {
        //     $query->where('Trans_Id', $request->Trans_Id);
        //     // $query->where('Reference_No', $request->Reference_No);
        //     // $query->where('Categories', $request->Categories);
        //     // $query->where('Sub_Amount', $request->Sub_Amount);
        //     // $query->where('Total_Amount', $request->Total_Amount);
        //     // $query->where('Date_Created', $request->Date_Created);
        //     // $query->where('Penalties', $request->Penalties);
        //     // $query->where('Status', $request->Status);
        // }
        // if ($request->has('Reference_No')) {
        //     // $query->where('Trans_Id', $request->Trans_Id);
        //     // $query->where('Reference_No', $request->Reference_No);
        //     // $query->where('Categories', $request->Categories);
        //     $query->where('Sub_Amount', $request->Sub_Amount);
        //     $query->where('Total_Amount', $request->Total_Amount);
        //     $query->where('Date_Created', $request->Date_Created);
        //     $query->where('Penalties', $request->Penalties);
        //     $query->where('Status', $request->Status);
        // }
        // if ($request->has('Categories')) {
        //     // $query->where('Trans_Id', $request->Trans_Id);
        //     // $query->where('Reference_No', $request->Reference_No);
        //     $query->where('Categories', $request->Categories);
        //     // $query->where('Sub_Amount', $request->Sub_Amount);
        //     // $query->where('Total_Amount', $request->Total_Amount);
        //     // $query->where('Date_Created', $request->Date_Created);
        //     // $query->where('Penalties', $request->Penalties);
        //     // $query->where('Status', $request->Status);
        // }
        // if ($request->has('Sub_Amount')) {
        //     // $query->where('Trans_Id', $request->Trans_Id);
        //     // $query->where('Reference_No', $request->Reference_No);
        //     // $query->where('Categories', $request->Categories);
        //     $query->where('Sub_Amount', $request->Sub_Amount);
        //     // $query->where('Total_Amount', $request->Total_Amount);
        //     // $query->where('Date_Created', $request->Date_Created);
        //     // $query->where('Penalties', $request->Penalties);
        //     // $query->where('Status', $request->Status);
        //   }
        // if ($request->has('Total_Amount')) {
        //     // $query->where('Trans_Id', $request->Trans_Id);
        //     // $query->where('Reference_No', $request->Reference_No);
        //     // $query->where('Categories', $request->Categories);
        //     // $query->where('Sub_Amount', $request->Sub_Amount);
        //     $query->where('Total_Amount', $request->Total_Amount);
        //     // $query->where('Date_Created', $request->Date_Created);
        //     // $query->where('Penalties', $request->Penalties);
        //     // $query->where('Status', $request->Status);
        // }
        // if ($request->has('Date_Created')) {
        //     // $query->where('Trans_Id', $request->Trans_Id);
        //     // $query->where('Reference_No', $request->Reference_No);
        //     // $query->where('Categories', $request->Categories);
        //     // $query->where('Sub_Amount', $request->Sub_Amount);
        //     // $query->where('Total_Amount', $request->Total_Amount);
        //     $query->where('Date_Created', $request->Date_Created);
        //     // $query->where('Penalties', $request->Penalties);
        //     // $query->where('Status', $request->Status);
        // }
        // if ($request->has('Penalties')) {
        //     // $query->where('Trans_Id', $request->Trans_Id);
        //     // $query->where('Reference_No', $request->Reference_No);
        //     // $query->where('Categories', $request->Categories);
        //     // $query->where('Sub_Amount', $request->Sub_Amount);
        //     // $query->where('Total_Amount', $request->Total_Amount);
        //     // $query->where('Date_Created', $request->Date_Created);
        //     $query->where('Penalties', $request->Penalties);
        //     // $query->where('Status', $request->Status);
        // }
        // if ($request->has('Status')) {
        //     // $query->where('Trans_Id', $request->Trans_Id);
        //     // $query->where('Reference_No', $request->Reference_No);
        //     // $query->where('Categories', $request->Categories);
        //     // $query->where('Sub_Amount', $request->Sub_Amount);
        //     // $query->where('Total_Amount', $request->Total_Amount);
        //     // $query->where('Date_Created', $request->Date_Created);
        //     // $query->where('Penalties', $request->Penalties);
        //     $query->where('Status', $request->Status);
        // // }
        // $data = $query->paginate(10);

        // return response()->json($data);
    }
}
