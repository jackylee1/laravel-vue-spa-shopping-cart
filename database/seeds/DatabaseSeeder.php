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
        $this->call(SettingTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(TextNotificationTableSeeder::class);
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
            'email' => 'admin@admin.com',
            'phone' => '380950000000',
            'password' => Hash::make('admin'),
            'status' => 'administration',
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

class CategoryTableSeeder extends Seeder {
    public function run() {
        $data = [
            [
                'name' => 'Корневая категория', 'like_name' => getOnlyCharacters('Корневая категория'),
                'slug' => str_slug('Корневая категория'), 'sorting_order' => 0, 'parent_id' => 0, 'type_id' => null
            ],
        ];
        DB::table('categories')->delete();
        DB::table('categories')->truncate();
        DB::table('categories')->insert($data);
        $this->command->info('Categories seeded');
    }
}

class SettingTableSeeder extends Seeder {
    public function run() {
        $data = [
            ['slug' => 'phone1'],
            ['slug' => 'phone2'],
            ['slug' => 'email'],
            ['slug' => 'address'],
            ['slug' => 'index_m_title'],
            ['slug' => 'index_m_description'],
            ['slug' => 'index_m_keywords'],
            ['slug' => 'favicon'],
            ['slug' => 'logo'],
        ];

        DB::table('settings')->delete();
        DB::table('settings')->truncate();
        DB::table('settings')->insert($data);

        $this->command->info('Settings seeded');
    }
}

class TextNotificationTableSeeder extends Seeder {
    public function run() {
        $data = [
            ['slug' => 'new_order'],
            ['slug' => 'update_status_order'],
            ['slug' => 'promotional_code']
        ];

        DB::table('text_notifications')->delete();
        DB::table('text_notifications')->truncate();
        DB::table('text_notifications')->insert($data);

        $this->command->info('TextNotification seeded');
    }
}
