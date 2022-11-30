<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;
use Carbon\carbon;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Advertisement::insert([
            [
                'id'         => 1,
                'name'       => 'sidebar-350x300',
                'type'       => 'image',
                'url'        => '#',
                'image'      => '350x300.png',
                'size'       => '350x300',
                'active'     => 'y',
                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id'         => 2,
                'name'       => 'horizontal-750x80',
                'type'       => 'image',
                'url'        => '#',
                'image'      => '750x80.png',
                'size'       => '750x80',
                'active'     => 'y',
                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
