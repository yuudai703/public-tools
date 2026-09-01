<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EraName extends Model
{
    use HasFactory;
    /** @var string */
    protected $table = 'era_names';

    /** @var array */
    protected $dates = [
        'start_at',
        'end_at',
    ];

    /** @var array */
    protected $guarded = [
        'id',
    ];
}
