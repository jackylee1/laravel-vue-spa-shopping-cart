<?php

namespace App\Models;

use App\Tools\KeyTool;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Favorite
 *
 * @property int $id
 * @property string|null $key
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FavoriteProduct[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite withCacheCooldownSeconds($seconds)
 */
class Favorite extends Model
{
    use Cachable;

    protected $fillable = [
        'key',
        'user_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    protected $with = ['products'];

    public function products() {
        return $this->hasMany('App\Models\FavoriteProduct', 'favorite_id', 'id');
    }

    private static function getWhereQuery() {
        return [
            'key' => KeyTool::getKeyUser('favorite_key'),
            'user_id' => (auth()->check()) ? auth()->user()->id : null
        ];
    }

    public static function getByKey($key) {
        return Favorite::where('key', $key)->first();
    }

    public static function firstOrCreateModel() {
        return Favorite::firstOrCreate(self::getWhereQuery());
    }

    public static function getItem() {
        $favorite = self::firstOrCreateModel();

        return $favorite->fresh();
    }

    protected function addProduct() {
        return self::firstOrCreateModel()->products()->create([
            'product_id' => request()->get('product_id')
        ])->fresh();
    }
}
