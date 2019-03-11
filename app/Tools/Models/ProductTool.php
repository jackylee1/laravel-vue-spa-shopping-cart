<?php

namespace App\Tools\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductTool
{
    private static $products;

    /**
     * @param $product
     * @return mixed
     */
    private static function handleCheckIfDiscountValid($product) {
        $current_datetime = Carbon::now();
        if (isset($product->discount_end) && $product->discount_end !== null) {
            $discount_end_datetime = Carbon::parse($product->discount_end);
            if ($discount_end_datetime->lt($current_datetime)) {
                $product->discount_end = $product->discount_start = $product->discount_price = null;
                $product->save();
            }
        }
        return $product;
    }
    /**
     * @param $products
     * @return Collection|mixed
     */
    public static function checkRelevanceDiscount($products) {
        self::$products = $products;
        if (is_array(self::$products)) {
            self::$products = collect(self::$products);
        }
        switch (self::$products) {
            case (self::$products instanceof Model):
                self::$products = self::handleCheckIfDiscountValid(self::$products);
                break;
            case (self::$products instanceof Collection):
                self::$products->each(function ($product) {
                    return self::handleCheckIfDiscountValid($product);
                });
                break;
        }
        return self::$products;
    }
}