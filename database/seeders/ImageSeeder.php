<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            [
                'path' => 'images/1.jpg',
                'visit_id' => 1,
                'type' => 1
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 1,
                'type' => 1
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 2,
                'type' => 1
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 2,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/3.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/3.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 1,
                'type' => 1
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 1,
                'type' => 1
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 2,
                'type' => 1
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 2,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/3.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/3.jpg',
                'visit_id' => 3,
                'type' => 1
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 1,
                'type' => 0
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 1,
                'type' => 0
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 2,
                'type' => 0
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 2,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/3.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/3.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 1,
                'type' => 0
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 1,
                'type' => 0
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 2,
                'type' => 0
            ],
            [
                'path' => 'images/1.jpg',
                'visit_id' => 2,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/2.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/3.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
            [
                'path' => 'images/3.jpg',
                'visit_id' => 3,
                'type' => 0
            ],
        ];

        foreach ($images as $image) {
            \App\Models\Image::query()->insert($image);
        }
    }
}
