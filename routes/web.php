<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Redirect the root URL to the login page (Vue login)
Route::get('/', function () {
    return view('auth.login'); // Redirecting to the login view generated by Vue
})->name('vue.login'); // Changed the name to avoid conflict

// Authentication routes
Auth::routes(); // This registers routes like /login, /register, etc.

// Resourceful routes for products (only accessible after login)
Route::middleware(['auth'])->group(function () {

// Show product index (accessible to all users, Admin or Customer)
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::middleware('auth')->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
    
    // Resourceful routes for products, but restrict create and edit to Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::resource('users', UserController::class);
    });

    
    Route::get('/products', [ProductController::class, 'index'])->name('products.index'); // View all products
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show'); // View a single product

    Route::get('/product/{product}/buy', [ProductController::class, 'buy'])->name('product.buy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Admin'])->group(function () {
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index'); // Defined once
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/my-orders', [OrderController::class, 'userOrders'])->name('orders.index');
    });

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    Route::get('payment/success/{order_id}', [OrderController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('payment/cancel', [OrderController::class, 'paymentCancel'])->name('payment.cancel');
    Route::get('orders/{orderId}', [OrderController::class, 'show'])->name('admin.orders.show');

    Route::get('/checkout', [CheckoutController::class, 'showCheckoutForm'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'storeCheckoutData'])->name('checkout.store');

    Route::get('/checkout/stripe', [StripeController::class, 'createCheckoutSession'])->name('checkout.stripe');
    Route::get('/checkout/success', [StripeController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [StripeController::class, 'cancel'])->name('checkout.cancel');

});
