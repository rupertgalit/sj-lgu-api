<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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

        // Apply the sorting logic to the query
        $data = Transaction::orderBy($sortBy, $sortDirection)->paginate(10);

        // Return the sorted data as JSON response
        return response()->json($data);
    }
    public function store(Request $request)
    {
        $customOrderId = $this->GenTransactionId();

        $customOrderRef = $this->GenReferenceNumber();

        $data = [
            'Trans_Id' => $customOrderId,
            'Reference_No' =>  $customOrderRef,
            'Categories' => $request->Categories,
            'Sub_Amount' => (float) $request->Sub_Amount,
            'Total_Amount' => (float)$request->Total_Amount,
            'Date_Created' => now(),
            'Penalties' => (float)$request->Penalties,
            'Status' => $request->Status

        ];

        $save = Transaction::create($data);
        $reponse = [
            "status" => "200",
            "message" => "success",
            "data" => $save
        ];

        return response()->json($reponse, 200);
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
