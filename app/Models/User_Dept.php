<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Dept extends Model
{
    use HasFactory;
    protected $table = "user_dept";
    protected $guarded=['id'];
}
