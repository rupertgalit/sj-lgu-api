<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_logs extends Model
{
    use HasFactory;

    protected $table = 'user_logs';
    protected $guarded = [];

}
