<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            SettingSeeder::class,
            SocialmediaSeeder::class,
            UserSeeder::class,
            TermSeeder::class,
            PostSeeder::class,
            MenuSeeder::class,
            MenuItemSeeder::class,
            AdvertisementSeeder::class,
            AdPlacementSeeder::class
        ]);
    }
}
