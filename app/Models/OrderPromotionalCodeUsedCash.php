<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPromotionalCodeUsedCash extends Model
{
    protected $fillable = [
        'order_id',
        'promotional_code_id',
        'cash'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'promotional_code_id' => 'integer',
        'cash' => 'integer'
    ];
}
