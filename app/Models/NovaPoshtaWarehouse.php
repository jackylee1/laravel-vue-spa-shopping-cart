<?php

namespace App\Models;

use App\Tools\Api\Delivery\NovaPoshta;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NovaPoshtaWarehouse
 *
 * @property int $id
 * @property string $ref
 * @property string $city_ref
 * @property string $site_key
 * @property string $type_of_warehouse
 * @property string $number
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereCityRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereSiteKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereTypeOfWarehouse($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse withCacheCooldownSeconds($seconds)
 */
class NovaPoshtaWarehouse extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'site_key',
        'ref',
        'type_of_warehouse',
        'number',
        'city_ref',
        'description'
    ];

    protected function getItems() {
        return NovaPoshtaWarehouse::where('city_ref', request()->get('city_ref'))->get();
    }

    public static function updateWarehouses() {
        $np = new NovaPoshta();
        $response_warehouses = collect($np->getWarehouses());

        $data = $response_warehouses->filter(function ($item) {
            return ($item['locale'] == 'ru');
        })->first()['data'];

        $ref = $data->pluck('ref');

        NovaPoshtaWarehouse::whereNotIn('ref', $ref->toArray())->delete();

        $select_refs = NovaPoshtaWarehouse::whereIn('ref', $ref->toArray())->select('ref')->get();
        $select_refs = collect($select_refs)->pluck('ref');

        $create_refs = $ref->filter(function ($value) use ($select_refs) {
            return !($select_refs->contains($value));
        });

        $create_refs->each(function ($create_ref) use ($response_warehouses) {
            $model = new NovaPoshtaWarehouse();

            $warehouses = $response_warehouses->firstWhere('locale', 'ru')['data']->firstWhere('ref', $create_ref);

            $model->ref = $create_ref;
            $model->site_key = $warehouses['site_key'];
            $model->type_of_warehouse = $warehouses['type_of_warehouse'];
            $model->number = $warehouses['number'];
            $model->city_ref = $warehouses['city_ref'];
            $model->description = $warehouses['description'];

            $model->save();
        });
    }
}
