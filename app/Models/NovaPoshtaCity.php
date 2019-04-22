<?php

namespace App\Models;

use App\Tools\Api\Delivery\NovaPoshta;
use Illuminate\Database\Eloquent\Model;

class NovaPoshtaCity extends Model
{
    protected $fillable = [
        'ref',
        'area',
        'settlement_type',
        'city_id',
        'description',
        'settlement_type_description'
    ];
    public $timestamps = false;

    public function warehouses() {
        return $this->hasMany('App\Models\NovaPoshtaWarehouse', 'city_ref', 'ref');
    }

    protected function getItems() {
        return NovaPoshtaCity::where('area', request()->get('area_ref'))->get();
    }

    public static function updateCities() {
        $np = new NovaPoshta();
        $response_cities = collect($np->getCities());

        $data = $response_cities->filter(function ($item) {
            return ($item['locale'] == 'ru');
        })->first()['data'];

        $ref = $data->pluck('ref');

        NovaPoshtaCity::whereNotIn('ref', $ref->toArray())->delete();

        $select_refs = NovaPoshtaCity::whereIn('ref', $ref->toArray())->select('ref')->get();
        $select_refs = collect($select_refs)->pluck('ref');

        $create_refs = $ref->filter(function ($value) use ($select_refs) {
            return !($select_refs->contains($value));
        });

        $create_refs->each(function ($create_ref) use ($response_cities) {
            $model = new NovaPoshtaCity();

            $city = $response_cities->firstWhere('locale', 'ru')['data']->firstWhere('ref', $create_ref);

            $model->ref = $create_ref;
            $model->area = $city['area'];
            $model->settlement_type = $city['settlement_type'];
            $model->city_id = $city['city_id'];
            $model->description = $city['description'];
            $model->settlement_type_description = $city['settlement_type_description'];

            $model->save();
        });
    }
}
