<?php

namespace App\Models;

use App\Tools\Api\Delivery\NovaPoshta;
use Illuminate\Database\Eloquent\Model;

class NovaPoshtaWarehouse extends Model
{
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
