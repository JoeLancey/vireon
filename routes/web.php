<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\AdminController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Auth Routes (Breeze)
require __DIR__.'/auth.php';

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/products/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/products/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/products/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/cart/confirmation', [CartController::class, 'confirmation'])->name('cart.confirmation');

    // Order History Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin Only Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/products/archived', [AdminProductController::class, 'archived'])->name('products.archived');
    Route::patch('/products/{product}/archive', [AdminProductController::class, 'destroy'])->name('products.archive');
    Route::patch('/products/{product}/unarchive', [AdminProductController::class, 'unarchive'])->name('products.unarchive');
    Route::delete('/products/{product}/force-destroy', [AdminProductController::class, 'forceDestroy'])->name('products.force-destroy');
    Route::resource('products', AdminProductController::class)->except(['destroy']);
    Route::resource('brands', AdminBrandController::class);
});

Route::get('/product', function () {
    return view('product');
});