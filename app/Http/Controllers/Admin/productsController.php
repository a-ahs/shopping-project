<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Admin\Products\storeRequest;
    use App\Http\Requests\Admin\Products\updateRequest;
    use App\Models\Category;
    use App\Models\Product;
    use App\Models\User;
    use App\Utilities\imageUploader;
    use Exception;
    use Illuminate\Http\Request;

class productsController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.add', compact('categories'));
    }

    public function store(storeRequest $request)
    {
        $admin = User::where('name', 'admin')->first();

        $validatedData = $request->validated();
        $createdProduct = Product::create([
            'title' => $validatedData['title'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
            'owner_id' => $admin->id,
        ]);

        try {
            $basePath = 'products/' . $createdProduct->id . '/'; 
            $sourcePath = $basePath . 'source_url_' . $validatedData['source_url']->getClientOriginalName();
            
            $images = [
                'thumbnail_url' => $validatedData['thumbnail_url'], 
                'demo_url' => $validatedData['demo_url']
            ];
            $imagesPath = imageUploader::uploadMany($images, $basePath);
        
            $fullSourcePath = $basePath . 'source_url_' . $validatedData['source_url']->getClientOriginalName();
            
            imageUploader::upload($validatedData['source_url'], $sourcePath);

            $updateProduct = $createdProduct->update([
                'thumbnail_url' => $imagesPath['thumbnail_url'],
                'demo_url' => $imagesPath['demo_url'],
                'source_url' => $fullSourcePath
            ]);

            if(!$updateProduct)
            {
                throw new \Exception('محصول ایجاد نشد');
            }

            return back()->with('success', 'محصول ایجاد شد');

        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }

    }

    public function all()
    {
        $products = Product::paginate(3);
        return view('admin.products.all', compact('products'));
    }

    public function downloadDemo($product_id)
    {
        $product = Product::findOrFail($product_id);
        // dd(public_path($product->demo_url));

        return response()->download(public_path($product->demo_url));
    }

    public function downloadSource($product_id)
    {
        $product = Product::findOrFail($product_id);

        return response()->download(storage_path('app/local_storage/' . $product->source_url));
    }

    public function delete($product_id)
    {
        $product = Product::findOrFail($product_id);

        $product->delete();

        return back()->with('success', 'محصول با موفقیت حذف شد');
    }

    public function edit($product_id)
    {
        $product = Product::findOrFail($product_id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(updateRequest $request, $product_id)
    {
        $validatedData = $request->validated();

        $product = Product::findOrFail($product_id);

        $product->update([
            'title' => $validatedData['title'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
        ]);

        return $this->uploadImage($product, $validatedData);
    }

    private function uploadImage($product, $validatedData)
    {
        try {
            $basePath = 'products/' . $product->id . '/'; 
            $sourcePath = null;
            $data = [];
            
            if(isset($validatedData['source_url']))
            {
                $sourcePath = $basePath . 'source_url_' . $validatedData['source_url']->getClientOriginalName();
                imageUploader::upload($validatedData['source_url'], $sourcePath);

                $data += ['source_url' => $sourcePath];
            }
            
            if(isset($validatedData['thumbnail_url']))
            {
                $fullPath = $basePath . 'thumbnail_url_' . $validatedData['thumbnail_url']->getClientOriginalName();
                imageUploader::upload($validatedData['thumbnail_url'], $fullPath, 'public_storage');

                $data += ['thumbnail_url' => $fullPath];
            }
            
            if(isset($validatedData['demo_url']))
            {
                $fullPath = $basePath . 'demo_url_' . $validatedData['demo_url']->getClientOriginalName();
                imageUploader::upload($validatedData['demo_url'], $fullPath, 'public_storage');

                $data += ['source_url' => $sourcePath];
            }

            $updateProduct = $product->update($data);

            if(!$updateProduct)
            {
                throw new \Exception('محصول ویرایش نشد');
            }

            return back()->with('success', 'محصول با موفقیت ویرایش شد');

        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }        
    }
}
