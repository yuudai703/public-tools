<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FireSafetyConstructionItemCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "firesafetyconstruction_mt_item_categories";
    protected $guarded=['id'];
}
