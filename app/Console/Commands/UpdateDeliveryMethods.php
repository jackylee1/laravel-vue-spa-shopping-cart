<?php

namespace App\Console\Commands;

use App\Models\NovaPoshtaArea;
use App\Models\NovaPoshtaCity;
use App\Models\NovaPoshtaWarehouse;
use Illuminate\Console\Command;

class UpdateDeliveryMethods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-delivery-methods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update delivery methods';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        NovaPoshtaArea::updateAreas();
        NovaPoshtaCity::updateCities();
        NovaPoshtaWarehouse::updateWarehouses();
    }
}