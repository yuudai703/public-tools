<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FireSafetyConstructionQuotationSummary extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "firesafetyconstruction_quotation_summaries";
    protected $guarded=['id'];
}
