<?php

namespace App\Models;

use App\Tools\Api\Delivery\NovaPoshta;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NovaPoshtaArea
 *
 * @property int $id
 * @property string $ref
 * @property string $area_center
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\NovaPoshtaCity[] $cities
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea whereAreaCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea whereRef($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea withCacheCooldownSeconds($seconds)
 */
class NovaPoshtaArea extends Model
{
    use Cachable;

    protected $fillable = [
        'ref',
        'area_center',
        'description'
    ];
    public $timestamps = false;

    public function cities() {
        return $this->hasMany('App\Models\NovaPoshtaCity', 'area', 'ref');
    }

    public static function getItems() {
        return NovaPoshtaArea::get();
    }

    public static function updateAreas() {
        $np = new NovaPoshta();
        $response_areas = collect($np->getAreas());

        $data = $response_areas->filter(function ($item) {
            return ($item['locale'] == 'ru');
        })->first()['data'];

        $ref = $data->pluck('Ref');

        NovaPoshtaArea::whereNotIn('ref', $ref->toArray())->delete();

        $select_refs = NovaPoshtaArea::whereIn('ref', $ref->toArray())->select('ref')->get();
        $select_refs = collect($select_refs)->pluck('ref');

        $create_refs = $ref->filter(function ($value) use ($select_refs) {
            return !($select_refs->contains($value));
        });

        $create_refs->each(function ($create_ref) use ($response_areas) {
            $model = new NovaPoshtaArea();
            $model->ref = $create_ref;
            $data = $response_areas->firstWhere('locale', 'ru')['data']->firstWhere('Ref', $create_ref);
            $model->area_center = $data['AreasCenter'];
            $model->description = $data['Description'];
            $model->save();
        });
    }

    public static function getWarehouse($params = null) {
        $area_id = ($params !== null) ? $params['area_id'] : request()->get('area_id');
        $city_id = ($params !== null) ? $params['city_id'] : request()->get('city_id');
        $warehouse_id = ($params !== null) ? $params['warehouse_id'] : request()->get('warehouse_id');

        return NovaPoshtaArea::where('ref', $area_id)->first()
            ->cities()->where('ref', $city_id)->first()
            ->warehouses()->where('ref', $warehouse_id)->first();
    }
}
