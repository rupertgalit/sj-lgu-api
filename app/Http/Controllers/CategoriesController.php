<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {

        // $transaction = Transaction::paginate(10);
        // return response()->json($transaction, 200);
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
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
    public function search(Request $request) {}
}
