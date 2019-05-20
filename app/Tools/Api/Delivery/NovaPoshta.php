<?php

namespace App\Tools\Api\Delivery;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class NovaPoshta
{
    private $guzzle,
        $api_path,
        $api_key,
        $result;

    public function __construct() {
        $this->guzzle = new Client([
            'base_uri' => 'https://api.novaposhta.ua',
            'headers' => [
                'content-type' => 'application/json'
            ]
        ]);
        $this->api_path = '/v2.0/json/';
        $this->api_key = ['apiKey' => env('NP_API_KEY')];
        $this->result = [];
    }

    /**
     * @param $data
     * @return mixed
     */
    private function sendAPIRequest($data) {
        $data = array_merge($this->api_key, $data);

        $response = $this->guzzle->post($this->api_path, [
            RequestOptions::JSON => $data
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $localization
     * @param $data
     */
    private function putData($localization, $data) {
        $this->result[] = ['locale' => $localization];
        $index = array_search($localization, array_column($this->result, 'locale'));

        $this->result[$index]['data'] = collect($data);
    }

    public function getAreas() {
        $this->result = [];
        $send_data = [
            'modelName' => 'Address',
            'calledMethod' => 'getAreas',
            'methodProperties' => [
                'Language' => 'ru'
            ]
        ];

        $areas = $this->sendAPIRequest($send_data);

        if ($areas['success']) {
            $this->putData('ru', $areas['data']);
        }

        return $this->result;
    }

    /**
     * @return array
     */
    public function getCities() {
        $this->result = [];

        $data = [
            'modelName' => 'Address',
            'calledMethod' => 'getCities'
        ];

        $cities = $this->sendAPIRequest($data);

        if ($cities['success']) {
            $data_ru = [];
            $cities = collect($cities['data']);
            $cities->each(function ($city) use (&$data_ru) {
                $settlement_type_description_ru = (isset($city['SettlementTypeDescriptionRu']))
                    ? $city['SettlementTypeDescriptionRu']
                    : null;

                array_push($data_ru, [
                    'description' => $city['DescriptionRu'],
                    'ref' => $city['Ref'],
                    'area' => $city['Area'],
                    'settlement_type' => $city['SettlementType'],
                    'city_id' => $city['CityID'],
                    'settlement_type_description' => $settlement_type_description_ru
                ]);
            });
            $this->putData('ru', $data_ru);
        }

        return $this->result;
    }

    /**
     * @return array
     */
    public function getWarehouses() {
        $this->result = [];

        $data = [
            'modelName' => 'AddressGeneral',
            'calledMethod' => 'getWarehouses'
        ];

        $warehouses = $this->sendAPIRequest($data);

        if ($warehouses['success']) {
            $data_ru = [];
            $warehouses = collect($warehouses['data']);
            $warehouses->each(function ($warehouse) use (&$data_ru) {
                array_push($data_ru, [
                    'site_key' => $warehouse['SiteKey'],
                    'description' => $warehouse['DescriptionRu'],
                    'ref' => $warehouse['Ref'],
                    'type_of_warehouse' => $warehouse['TypeOfWarehouse'],
                    'number' => $warehouse['Number'],
                    'city_ref' => $warehouse['CityRef']
                ]);
                $this->putData('ru', $data_ru);
            });
        }

        return $this->result;
    }
}