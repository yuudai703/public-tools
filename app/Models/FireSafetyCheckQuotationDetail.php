<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireSafetyCheckQuotationDetail extends Model
{
    use HasFactory;
    protected $table = "firesafetycheck_quotation_details";
    protected $guarded=['id'];
}
