<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {

        $transaction = Transaction::all();
        return response()->json($transaction, 200);
    }

    public function store(Request $request)
    {

        $data = [
            'Trans_Id' => $request->Trans_Id,
            'Categories' => $request->Categories,
            'Sub_Amount' => $request->Sub_Amount,
            'Total_Amount' => $request->Total_Amount,
            'Date_Created' => $request->Date_Created
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
}
