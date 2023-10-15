<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\storeRequest;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(storeRequest $request)
    {
        $validatedData = $request->validated();

        $createdCategory = Category::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug']
        ]);

        if(!$createdCategory)
        {
            return back()->with('failed', ' دسته بندی ایجاد نشد!!!');
        }else
        {
            return back()->with('success', ' دسته بندی با موفقیت ایجاد شد');
        }
    }

    public function all()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.all', compact('categories'));
    }

    public function delete($category_id)
    {
        $category = Category::find($category_id);
        $category->delete($category);

        return back()->with('success', 'دسته بندی حذف شد');
    }

    public function edit($category_id)
    {
        $category = Category::find($category_id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(storeRequest $request, $category_id)
    {
        $validatedData = $request->validated();

        $category = Category::find($category_id);
        $category->update(
            [
                'title' => $validatedData['title'],
                'slug' => $validatedData['slug']
            ]
        );

        if(!$category)
        {
            return back()->with('fail', 'دسته بندی بروزرسانی نشد');
        }
        return back()->with('success', 'دسته بندی بروزرسانی شد');
    }
}
