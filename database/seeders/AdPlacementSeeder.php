<?php

namespace Database\Seeders;

use App\Models\AdPlacement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdPlacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdPlacement::insert([
            [
                'name'       => 'sidebar-right-top',
                'slug'       => 'sidebar-right-top',
                'ad_id'      => 1,
                'active'     => 'y',
                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'       => 'sidebar-right-bottom',
                'slug'       => 'sidebar-right-bottom',
                'ad_id'      => 1,
                'active'     => 'y',
                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'       => 'sidebar-left-top',
                'slug'       => 'sidebar-left-top',
                'ad_id'      => 1,
                'active'     => 'y',
                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'       => 'sidebar-left-bottom',
                'slug'       => 'sidebar-left-bottom',
                'ad_id'      => 1,
                'active'     => 'y',
                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'       => 'home-horizontal',
                'slug'       => 'home-horizontal',
                'ad_id'      => 2,
                'active'     => 'y',
                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
