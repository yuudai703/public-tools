<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireSafetyCheckItem extends Model
{
    use HasFactory;
    protected $table = "firesafetycheck_mt_check_items";
    protected $guarded=['id'];
}
