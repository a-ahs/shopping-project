<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bags = Storage::files('bag');
        $cloths = Storage::files('cloth');
        $watches = Storage::files('watch');

        Product::factory()->create([
            'title' => str_replace('bag/', '', $bags[0]),
            'category_id' => 1,
            'owner_id' => User::first(),
            'thumbnail_url' => 'products/1/' . str_replace('bag/', '', $bags[0]),
            'demo_url' => 'products/1/' . str_replace('bag/', '', $bags[0]),
            'source_url' => 'products/1/' . str_replace('bag/', '', $bags[0]),
        ]);
            
        Product::factory()->create([
            'title' => str_replace('cloth/', '', $cloths[0]),
            'category_id' => 2,
            'owner_id' => User::first(),
            'thumbnail_url' => 'products/2/' . str_replace('cloth/', '', $cloths[0]),
            'demo_url' => 'products/2/' . str_replace('cloth/', '', $cloths[0]),
            'source_url' => 'products/2/' . str_replace('cloth/', '', $cloths[0]),
        ]);

        Product::factory()->create([
            'title' => str_replace('watch/', '', $watches[0]),
            'category_id' => 3,
            'owner_id' => User::first(),
            'thumbnail_url' => 'products/3/' . str_replace('watch/', '', $watches[0]),
            'demo_url' => 'products/3/' . str_replace('watch/', '', $watches[0]),
            'source_url' => 'products/3/' . str_replace('watch/', '', $watches[0]),
        ]);
    }
}
