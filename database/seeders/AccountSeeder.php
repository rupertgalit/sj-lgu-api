<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                "Email" => "sadmin@mail.com",
                "Password" => "123",
                "UserType" => 1,
                "Is_active" => 0,
                "Status" => "ACTIVATED",
            ],
            [
                "Email" => "cashier@mail.com",
                "Password" => "123",
                "UserType" => 2,
                "Is_active" => 0,
                "Status" => "ACTIVATED",
            ],
            [
                "Email" => "accounting@mail.com",
                "Password" => "123",
                "UserType" => 3,
                "Is_active" => 0,
                "Status" => "ACTIVATED",
            ],
            [
                "Email" => "ceo@mail.com",
                "Password" => "123",
                "UserType" => 4,
                "Is_active" => 0,
                "Status" => "ACTIVATED",
            ],
        ]);
    }
}
