<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert multiple records into the posts table
        DB::table('transaction')->insert([
            [
                "Trans_Id" => "57955-0173",
                "Recipient" => "donec posuere metus vitae ipsum",
                "Name" => "Christina",
                "Company" => "Flipstorm",
                "Reference_No" => "6709747453434133",
                "Categories" => "OC",
                "Sub_Amount" => 89,
                "Total_Amount" => 41,
                "Date_Created" => "2024-07-10",
                "Penalties" => "4675.27",
                "Status" => "directional",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "Trans_Id" => "0143-9864",
                "Recipient" => "nisi venenatis tristique fusce congue",
                "Name" => "Trish",
                "Company" => "Voomm",
                "Reference_No" => "379787183333546",
                "Categories" => "SA",
                "Sub_Amount" => 98,
                "Total_Amount" => 60,
                "Date_Created" => "2024-08-26",
                "Penalties" => "1321.29",
                "Status" => "didactic",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
        ]);
    }
}
