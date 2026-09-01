<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireSafetyConstructionQuotationDetail extends Model
{
    use HasFactory;
    protected $table = "firesafetyconstruction_quotation_details";
    protected $guarded=['id'];
}
