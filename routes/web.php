<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ordersController;
use App\Http\Controllers\Admin\paymentsController;
use App\Http\Controllers\Admin\productsController;
use App\Http\Controllers\Admin\usersController;
use App\Http\Controllers\Home\basketController;
use App\Http\Controllers\Home\checkoutController;
use App\Http\Controllers\Home\homeController;
use App\Http\Controllers\Home\productsController as HomeProductsController;
use App\Http\Controllers\paymentController;
use Illuminate\Support\Facades\Route;

Route::get('/products/all', function () {
    return view('frontend.all');
});

// Route::get('/admin/all', function () {
//     return view('admin.all');
// });

// Route::get('/admin/users', function () {
//     return view('admin.users.index');
// });
// Route::get('/admin/category', function () {
//     return view('admin.categories.create');
// });

Route::prefix('admin')->group(function(){
    Route::prefix('categories')->group(function(){
        Route::get('create', [CategoriesController::class, 'create'])->name('admin.categories.create');
        Route::post('', [CategoriesController::class, 'store'])->name('admin.categories.store');
        Route::get('', [CategoriesController::class, 'all'])->name('admin.categories.all');
        Route::delete('delete/{category_id}', [CategoriesController::class, 'delete'])->name('admin.categories.delete');
        Route::get('{category_id}/edit', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
        Route::put('{category_id}/update', [CategoriesController::class, 'update'])->name('admin.categories.update');
        
    });
    
    Route::prefix('products')->group(function() {
        Route::get('create', [productsController::class, 'create'])->name('admin.products.create');
        Route::post('', [productsController::class, 'store'])->name('admin.products.store');
        Route::get('', [productsController::class, 'all'])->name('admin.products.all');
        Route::get('{product_id}/download/demo', [productsController::class, 'downloadDemo'])->name('admin.products.download.demo');
        Route::get('{product_id}/download/source', [productsController::class, 'downloadSource'])->name('admin.products.download.source');
        Route::delete('{product_id}/delete', [productsController::class, 'delete'])->name('admin.products.delete');
        Route::get('{product_id}/edit', [productsController::class, 'edit'])->name('admin.products.edit');
        Route::put('{product_id}/update', [productsController::class, 'update'])->name('admin.products.update');

    });

    Route::prefix('users')->group(function(){
        Route::get('', [usersController::class, 'all'])->name('admin.users.all');
        Route::post('', [usersController::class, 'store'])->name('admin.users.store');
        Route::delete('{user_id}/delete', [usersController::class, 'delete'])->name('admin.users.delete');
        Route::put('{user_id}/update', [usersController::class, 'update'])->name('admin.users.update');
        Route::get('{user_id}/edit', [usersController::class, 'edit'])->name('admin.users.edit');
        Route::get('create', [usersController::class, 'create'])->name('admin.users.create');
    });

    Route::prefix('orders')->group(function (){
        Route::get('', [ordersController::class, 'all'])->name('admin.orders.all');
    });

    Route::prefix('payment')->group(function (){
        Route::get('', [paymentsController::class, 'all'])->name('admin.payments.all');
    });
});

Route::prefix('')->group(function (){
    Route::get('', [HomeProductsController::class, 'index'])->name('home.products.index');
    Route::get('{product_id}/show', [HomeProductsController::class, 'show'])->name('home.products.show');
    Route::get('{product_id}/addToBasket', [basketController::class, 'addToBasket'])->name('home.basket.add');
    Route::get('{product_id}/removeFromBasket', [basketController::class, 'removeFromBasket'])->name('home.remove.basket');
    Route::get('checkout', [checkoutController::class, 'show'])->name('home.checkout.show');
});

Route::get('pay', [paymentController::class, 'pay']);

