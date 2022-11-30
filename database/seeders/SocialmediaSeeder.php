<?php

namespace Database\Seeders;

use App\Models\Socialmedia;
use Illuminate\Database\Seeder;

class SocialmediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Socialmedia::create([
            'name' => 'Facebook',
            'slug' => 'facebook',
            'url' => 'https://www.facebook.com',
            'icon' => 'fab fa-facebook'
        ]);
        Socialmedia::create([
            'name' => 'Twitter',
            'slug' => 'twitter',
            'url' => 'https://www.twitter.com',
            'icon' => 'fab fa-twitter'
        ]);
        Socialmedia::create([
            'name' => 'YouTube',
            'slug' => 'youtube',
            'url' => 'https://www.youtube.com',
            'icon' => 'fab fa-youtube'
        ]);
        Socialmedia::create([
            'name' => 'Instagram',
            'slug' => 'instagram',
            'url' => 'https://www.instagram.com',
            'icon' => 'fab fa-instagram'
        ]);
        Socialmedia::create([
            'name' => 'LinkedIn',
            'slug' => 'linkedin',
            'url' => 'https://www.linkedin.com',
            'icon' => 'fab fa-linkedin'
        ]);
    }
}
