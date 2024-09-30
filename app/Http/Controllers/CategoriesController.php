<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {

        $posts = Categories::all();
        return response()->json($posts);
    }

    public function store(Request $request)
    {

        $data = [
            'Category_Id' => (float) $request->Category_Id,
            'Category_Name' => $request->Category_Name,
            'Amount' => (float)$request->Amount,
            'Has_Penalty' => (float) $request->Has_Penalty,
            'Penalties' => (float)$request->Penalties,
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
            'Has_Penalty' => 'required',
            
        ]);

        // Update the record
        $resource->update($validatedData);

        // Return a successful response
        return response()->json(['message' => 'Resource updated successfully', 'data' => $resource], 200);
    }
    public function destroy(string $id) {}
    public function search(Request $request) {}
}
