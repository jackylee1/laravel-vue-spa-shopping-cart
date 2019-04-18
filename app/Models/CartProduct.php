<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CartProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_available_id
 * @property int $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereProductAvailableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereQuantity($value)
 * @mixin \Eloquent
 * @property int $cart_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereCartId($value)
 */
class CartProduct extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_available_id',
        'quantity'
    ];

    protected $casts = [
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'product_available_id' => 'integer',
        'quantity' => 'integer'
    ];
}
