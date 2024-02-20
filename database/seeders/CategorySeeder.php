<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'لباس' => 'cloth',
            'ساعت' => 'watch',
            'کوله' => 'bag'
        ];

        foreach($categories as $name => $slug)
        {
            Category::create([
                'title' => $name,
                'slug' => $slug
            ]);
        }
    }
}
