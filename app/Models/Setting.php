<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Setting
 *
 * @property int $id
 * @property string $slug
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting withCacheCooldownSeconds($seconds)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    use Cachable;

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
