<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenQuatationDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "gen_quatation_details";
    protected $guarded=['id'];
}
