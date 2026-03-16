<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\ClientController;
use App\Http\Controllers\Seller\InventoryController;
use App\Http\Controllers\Seller\OrderController; 
use App\Http\Controllers\Seller\SalesController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\StorefrontController;
use App\Http\Controllers\Seller\ReviewController; // 1. Added Seller Review Controller
use App\Http\Controllers\Buyer\HomeController; 
use App\Http\Controllers\Buyer\ProductController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\CheckoutController;
use App\Http\Controllers\Buyer\HistoryController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    if (Auth::user()->role_id == 2) { 
        return redirect()->route('buyer.home');
    }
    return redirect()->route('seller.dashboard'); 
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        Route::put('/password-update', 'updatePassword')->name('password.update');
    });

    Route::name('buyer.')->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/buyer-profile', [ProfileController::class, 'edit'])->name('profile');
        
        Route::controller(HistoryController::class)->group(function () {
            Route::get('/history', 'index')->name('history');
            Route::post('/history/review', 'storeReview')->name('reviews.store');
        });
        
        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('product');
            Route::get('/products/{product}', 'show')->name('product.show');
        });

        Route::controller(CartController::class)->group(function () {
            Route::get('/cart', 'index')->name('cart');
            Route::post('/cart/add/{product}', 'add')->name('cart.add');
            Route::delete('/cart/remove/{item}', 'remove')->name('cart.remove');
        });

        Route::controller(CheckoutController::class)->group(function () {
            Route::get('/checkout', 'index')->name('checkout');
            Route::post('/checkout/process', 'process')->name('checkout.process');
        });
    });

    Route::prefix('seller')->name('seller.')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/sales', [SalesController::class, 'index'])->name('sales'); 
        Route::get('/seller/sales/export', [SalesController::class, 'exportPDF'])->name('sales.export');

        Route::controller(StorefrontController::class)->group(function () {
            Route::get('/storefront', 'index')->name('storefront');
            Route::patch('/storefront', 'update')->name('storefront.update');
        });
        
        Route::controller(InventoryController::class)->group(function () {
            Route::get('/inventory', 'index')->name('inventory');
            Route::post('/inventory', 'store')->name('inventory.store');
            Route::patch('/inventory/{product}', 'update')->name('inventory.update');
            Route::delete('/inventory/{product}', 'destroy')->name('inventory.destroy');
            Route::get('/seller/inventory/export', 'exportPDF')->name('inventory.export');
        });

        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'index')->name('orders');
            Route::patch('/orders/{order}', 'updateStatus')->name('orders.update');
        });

        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        
        Route::controller(ClientController::class)->group(function () {
            Route::get('/clients', 'index')->name('clients');
            Route::delete('/clients/{user}', 'destroy')->name('clients.destroy');
        });
    });
});

require __DIR__.'/auth.php';