<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMainType extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'type_id',
        'category_id'
    ];
    protected $casts = [
        'product_id' => 'integer',
        'type_id' => 'integer',
        'category_id' => 'array'
    ];
}
