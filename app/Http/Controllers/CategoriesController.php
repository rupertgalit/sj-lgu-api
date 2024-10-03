<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CategoriesController extends Controller

{
     public function GenCateroiesId()
    {
        do {
            // You can use any logic to generate the number, here using a random number
            $uniqueNumber = mt_rand(1000000000, 9999999999); // 10-digit random number
        } while (Categories::where('Category_Id', $uniqueNumber)->exists()); // Ensure it's unique

        return $uniqueNumber;
    }

    public function index()
    {

        $posts = Categories::all();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $customOrderId = $this->GenCateroiesId();


        $data = [
            'Category_Id' =>(float) $customOrderId,
            'Category_Name' => $request->Category_Name,
            'Amount' => (float)$request->Amount,
            'Is_Fix' => (float) $request->Is_Fix,
            // 'Penalties' => (float)$request->Penalties,
        ];

        $save = Categories::create($data);
        $reponse = [
            "status" => "200",
            "message" => "success",
            "data" => $save
        ];

        return response()->json($reponse, 200);
    }

    public function show(string $id) {}
    public function update(Request $request, string $id)
    {
        $resource = Categories::find($id);

        if (!$resource) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Validate the incoming request
        $validatedData = $request->validate([
            'Category_Name' => 'required',
            'Amount' => 'required',
            'Is_Fix' => 'required',
            
        ]);

        // Update the record
        $resource->update($validatedData);

        // Return a successful response
        return response()->json(['message' => 'Resource updated successfully', 'data' => $resource], 200);
    }
    public function destroy(string $id) {}
    public function search(Request $request) {}
}
