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

                $product->current_price = $product->price;
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

    /**
     * @param $product
     * @return mixed
     */
    private static function handleCheckNewProducts($product) {
        $current_datetime = Carbon::now();
        $datetime_sub_months = Carbon::now()->subMonths(1);

        $created_at = Carbon::parse($product->created_at);
        $old_new_status = $product->new;
        if ($created_at->gte($datetime_sub_months) && $created_at->lte($current_datetime)) {
            $product->new = 1;
        }

        if ($old_new_status !== $product->new) {
            $product->save();
        }

        return $product;
    }

    public static function checkNewProducts($products) {
        self::$products = $products;
        if (is_array(self::$products)) {
            self::$products = collect(self::$products);
        }
        switch (self::$products) {
            case (self::$products instanceof Model):
                self::$products = self::handleCheckNewProducts(self::$products);
                break;
            case (self::$products instanceof Collection):
                self::$products->each(function ($product) {
                    return self::handleCheckNewProducts($product);
                });
                break;
        }
        return self::$products;
    }
}