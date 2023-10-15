<?php

    namespace App\Http\Controllers\Filters;

    use App\Models\Product;

    class OrderbyFilter
    {
        public function newest()
        {
            return Product::orderBy('created_at', 'desc')->get();
        }

        public function default()
        {
            return Product::all();
        }

        public function mostpopular()
        {
            return Product::all();
        }

        public function hightolow()
        {
            return Product::orderBy('price', 'desc')->get();
        }

        public function lowtohigh()
        {
            return Product::orderBy('price', 'asc')->get();
        }
    }

?>