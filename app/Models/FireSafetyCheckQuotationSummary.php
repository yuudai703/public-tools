<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireSafetyCheckQuotationSummary extends Model
{
    use HasFactory;
    protected $table = "firesafetycheck_quotation_summaries";
    protected $guarded=['id'];
    protected $appends = ['htotal'];
    
    public function getHtotalAttribute()
    {
        return $this->device_inspection_expense + $this->general_inspection_expense + $this->display_value;
    }
    
}
