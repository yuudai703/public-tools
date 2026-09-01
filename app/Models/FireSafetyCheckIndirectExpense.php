<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireSafetyCheckIndirectExpense extends Model
{
    use HasFactory;
    protected $table = "firesafetycheck_mt_indirect_expenses";
    protected $guarded=['id'];
}
