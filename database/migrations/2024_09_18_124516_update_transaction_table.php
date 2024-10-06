<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->string('Trans_Id')->nullable();
            $table->string('Reference_No')->nullable();
            $table->string('Categories')->nullable();
            $table->float('Sub_Amount')->nullable();
            $table->float('Total_Amount')->nullable();
            $table->dateTime('Date_Created')->nullable();
            $table->float('Penalties')->nullable();
            $table->string('Status')->nullable();
            $table->timestamps();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
