<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\ProductInFilter;
use App\Models\ProductMainType;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReassignCategoryProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deleteWhenMissingModels = true;

    protected $old_categories,
              $new_categories,
              $type_id;

    public function __construct($type_id, $old_categories, $new_categories)
    {
        $this->type_id = $type_id;
        $this->old_categories = $old_categories;
        $this->new_categories = $new_categories;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ProductMainType::getItemsByCategories($this->old_categories, $this->new_categories);

        $last_id_in_old_categories = $this->old_categories[count($this->old_categories) - 1];

        $product_in_filters = ProductInFilter::getItemsByTypeAndCategory($this->type_id, $last_id_in_old_categories);

        $this->new_categories = Category::getItemsByIds($this->new_categories);
        $this->new_categories = collect($this->new_categories);

        $product_in_filters->each(function ($item) {
            $item->categories()->delete();

            $this->new_categories->each(function ($category) use ($item) {
                $item->categories()->create(['category_id' => $category->id]);
            });
        });
    }
}
