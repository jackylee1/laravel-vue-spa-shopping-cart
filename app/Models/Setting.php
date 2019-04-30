<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'slug',
        'value'
    ];
    public $timestamps = false;

    public static function getItems() {
        return Setting::get();
    }

    protected function updateModel($slug, $value) {
        Setting::where('slug', $slug)->update(['value' => $value]);
    }
}
