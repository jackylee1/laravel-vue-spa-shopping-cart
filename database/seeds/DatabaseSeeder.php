<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(UsersTableSeeder::class);
        $this->call(FilterTableSeeder::class);
        $this->call(TypeTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(CategoryFilterTableSeeder::class);
        $this->call(TypeFilterTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

class UsersTableSeeder extends Seeder {
    public function run() {
        $datetime = \Carbon\Carbon::now();

        $data = [
            'user_name' => 'Name',
            'user_surname' => 'Surname',
            'user_patronymic' => 'Patronymic',
            'email' => 'admin@admin',
            'phone' => '380950000000',
            'password' => Hash::make('admin'),
            'status' => 'administration',
            'like_name' => prepareForLike('admin'),
            'like_email' => prepareForLike('admin@admin'),
            'created_at' => $datetime,
            'updated_at' => $datetime
        ];

        App\User::truncate();

        DB::table('users')->delete();
        DB::table('users')->truncate();
        DB::table('users')->insert($data);

        $this->command->info('Users seeded');
    }
}

class FilterTableSeeder extends Seeder {
    public function run() {
        $data = [
            [
                'parent_id' => 0, 'name' => 'Filter 1', 'like_name' => 'filter1',
                'slug' => 'filter-1', 'type' => 1, 'sorting_order' => 0,
            ],
            [
                'parent_id' => 0, 'name' => 'Filter 2', 'like_name' => 'filter2',
                'slug' => 'filter-2', 'type' => 1, 'sorting_order' => 1,
            ],
            [
                'parent_id' => 1, 'name' => 'Filter 1-1', 'like_name' => 'filter11',
                'slug' => 'filter-1-1', 'type' => 0, 'sorting_order' => 0,
            ],
            [
                'parent_id' => 1, 'name' => 'Filter 1-2', 'like_name' => 'filter12',
                'slug' => 'filter-1-2', 'type' => 0, 'sorting_order' => 1,
            ],
            [
                'parent_id' => 2, 'name' => 'Filter 2-1', 'like_name' => 'filter21',
                'slug' => 'filter-2-1', 'type' => 0, 'sorting_order' => 0,
            ],
            [
                'parent_id' => 2, 'name' => 'Filter 2-2', 'like_name' => 'filter22',
                'slug' => 'filter-2-2', 'type' => 0, 'sorting_order' => 1,
            ],
        ];

        DB::table('filters')->delete();
        DB::table('filters')->truncate();
        DB::table('filters')->insert($data);

        $this->command->info('Filters seeded');
    }
}

class TypeTableSeeder extends Seeder {
    public function run() {
        $data = [
            ['name' => 'Type 1', 'slug' => 'type-1', 'sorting_order' => 0],
            ['name' => 'Type 2', 'slug' => 'type-2', 'sorting_order' => 1],
        ];

        DB::table('types')->delete();
        DB::table('types')->truncate();
        DB::table('types')->insert($data);

        $this->command->info('Types seeded');
    }
}

class CategoryTableSeeder extends Seeder {
    public function run() {
        $data = [
            [
                'name' => 'Корневая категория', 'like_name' => getOnlyCharacters('Корневая категория'),
                'slug' => str_slug('Корневая категория'), 'sorting_order' => 0, 'parent_id' => 0, 'type_id' => null
            ],
            [
                'name' => 'Category 1', 'like_name' => getOnlyCharacters('Category 1'),
                'slug' => str_slug('Category 1'), 'sorting_order' => 0, 'parent_id' => 1, 'type_id' => 1,
            ],
            [
                'name' => 'Category 2', 'like_name' => getOnlyCharacters('Category 2'),
                'slug' => str_slug('Category 2'), 'sorting_order' => 1, 'parent_id' => 1, 'type_id' => 1,
            ],
            [
                'name' => 'Subcategory 1-1', 'like_name' => getOnlyCharacters('Subcategory 1-1'),
                'slug' => str_slug('Subcategory 1-1'), 'sorting_order' => 0, 'parent_id' => 2, 'type_id' => 1,
            ],
            [
                'name' => 'Subcategory 1-2', 'like_name' => getOnlyCharacters('Subcategory 1-2'),
                'slug' => str_slug('Subcategory 1-2'), 'sorting_order' => 1, 'parent_id' => 2, 'type_id' => 1,
            ],
            [
                'name' => 'Subcategory 2-1', 'like_name' => getOnlyCharacters('Subcategory 2-1'),
                'slug' => str_slug('Subcategory 2-1'), 'sorting_order' => 0, 'parent_id' => 3, 'type_id' => 1,
            ],
            [
                'name' => 'Subcategory 2-2', 'like_name' => getOnlyCharacters('Subcategory 2-2'),
                'slug' => str_slug('Subcategory 2-2'), 'sorting_order' => 1, 'parent_id' => 3, 'type_id' => 1,
            ],
        ];

        DB::table('categories')->delete();
        DB::table('categories')->truncate();
        DB::table('categories')->insert($data);

        $this->command->info('Categories seeded');
    }
}

class CategoryFilterTableSeeder extends Seeder {
    public function run() {
        $data = [
            ['category_id' => 5, 'filter_id' => 1],
            ['category_id' => 4, 'filter_id' => 2],
            ['category_id' => 4, 'filter_id' => 1],
            ['category_id' => 6, 'filter_id' => 2],
            ['category_id' => 7, 'filter_id' => 1],
        ];

        DB::table('category_filters')->delete();
        DB::table('category_filters')->truncate();
        DB::table('category_filters')->insert($data);

        $this->command->info('CategoryFilter seeded');
    }
}

class TypeFilterTableSeeder extends Seeder {
    public function run() {
        $data = [
            ['type_id' => 1, 'filter_id' => 1],
            ['type_id' => 1, 'filter_id' => 2],
        ];

        DB::table('type_filters')->delete();
        DB::table('type_filters')->truncate();
        DB::table('type_filters')->insert($data);

        $this->command->info('TypeFilter seeded');
    }
}
