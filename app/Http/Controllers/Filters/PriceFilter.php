<?php

    namespace app\Http\Controllers\Filters;

    use App\Models\Product;

    class PriceFilter
    {
        public function between($data)
        {
            return Product::whereBetween('price', $data)->get();
        }
    }

?>