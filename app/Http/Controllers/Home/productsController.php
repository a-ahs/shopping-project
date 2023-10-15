<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class productsController extends Controller
{
    public function index(Request $request)
    {
        $products = null;

        if(isset($request->filter, $request->action, $request->value))
        {
            $products = $this->findValues($request->filter, $request->action, $request->value) ?? Product::all();            
        }elseif(isset($request->filter, $request->action))
        {
            $products = $this->findFilters($request->filter, $request->action) ?? Product::all();
        }elseif($request->has('search'))
        {
            $products = Product::where('title', 'LIKE', '%' . $request->input('search') . '%')->get();
        }elseif($request->has('search-product'))
        {
            $products = Product::where('title', 'LIKE', '%' . $request->input('search-product') . '%')->get();
        }else
        {
            $products = Product::all();
        }
        $categories = Category::all();
        return view('frontend.products.all', compact('products', 'categories'));
    }

    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);

        $similarProducts = Product::where('category_id', $product->category_id)->take(4)->get();

        return view('frontend.products.show', compact('product', 'similarProducts'));
    }

    private function findFilters(string $className,string $methodName)
    {
        $baseNamespace = 'App\Http\Controllers\Filters\\';

        $class = $baseNamespace . (ucfirst($className) . 'Filter');

        if(!class_exists($class))
        {
            return null;
        }
        $object = new $class;

        if(!method_exists($class, $methodName))
        {
            return null;
        }

        return $object->{$methodName}();
    }

    private function findValues(string $className,string $methodName, string $value = null)
    {
        $baseNamespace = 'App\Http\Controllers\Filters\\';

        $class = $baseNamespace . (ucfirst($className) . 'Filter');

        if(!class_exists($class))
        {
            return null;
        }
        $object = new $class;

        if(!method_exists($class, $methodName))
        {
            return null;
        }

        $data = explode('to', $value);
        $data1 = 1000 * $data[0];
        $data2 = 1000 * $data[1];
        $data = [$data1,$data2];

        return $object->{$methodName}($data);
    }
}
