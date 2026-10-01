<?php

use Illuminate\Support\Facades\Route;

// Customer Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\CategoryController
    as AdminCategoryController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\StatisticsController;

use App\Http\Controllers\Admin\OrderController
    as AdminOrderController;

use App\Http\Controllers\Admin\ProductController
    as AdminProductController;

use App\Http\Controllers\Admin\ProductVariantController
    as AdminProductVariantController;

use App\Http\Controllers\Admin\UserController
    as AdminUserController;


/*
|--------------------------------------------------------------------------
| HOME + PRODUCT
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');


Route::get(
    '/products',
    [ProductController::class, 'index']
)->name('products.index');


Route::get(
    '/products/{product}',
    [ProductController::class, 'show']
)->name('products.show');


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::get(
    '/cart',
    [CartController::class, 'index']
)->name('cart.index');


Route::post(
    '/cart/add',
    [CartController::class, 'add']
)->name('cart.add');


Route::patch(
    '/cart/update/{variantId}',
    [CartController::class, 'update']
)->name('cart.update');


Route::delete(
    '/cart/remove/{variantId}',
    [CartController::class, 'remove']
)->name('cart.remove');


Route::delete(
    '/cart/clear',
    [CartController::class, 'clear']
)->name('cart.clear');


/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

Route::get(
    '/checkout',
    [CheckoutController::class, 'index']
)->name('checkout.index');


Route::post(
    '/checkout',
    [CheckoutController::class, 'store']
)->name('checkout.store');


Route::get(
    '/checkout/success/{order}',
    [CheckoutController::class, 'success']
)->name('checkout.success');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get(
    '/register',
    [AuthController::class, 'registerForm']
)->name('register.form');


Route::post(
    '/register',
    [AuthController::class, 'register']
)->name('register');


Route::get(
    '/login',
    [AuthController::class, 'loginForm']
)->name('login.form');


Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login');


Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout');


/*
|--------------------------------------------------------------------------
| USER ĐÃ ĐĂNG NHẬP
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'passwordForm'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Danh sách đơn hàng
    Route::get(
        '/orders',
        [OrderController::class, 'index']
    )->name('orders.index');

    // Xem chi tiết đơn hàng
    Route::get(
        '/orders/{order}',
        [OrderController::class, 'show']
    )->name('orders.show');

    // Hủy đơn hàng
    Route::post(
        '/orders/{order}/cancel',
        [OrderController::class, 'cancel']
    )->name('orders.cancel');

    // Wishlist
    Route::get(
        '/wishlists',
        [WishlistController::class, 'index']
    )->name('wishlists.index');

    Route::post(
        '/wishlists/add',
        [WishlistController::class, 'add']
    )->name('wishlists.add');

    Route::delete(
        '/wishlists/{product}',
        [WishlistController::class, 'remove']
    )->name('wishlists.remove');

    Route::get(
        '/wishlists/{product}/check',
        [WishlistController::class, 'check']
    )->name('wishlists.check');

    // Đánh giá sản phẩm
    Route::post(
        '/products/{product}/reviews',
        [ReviewController::class, 'store']
    )->name('reviews.store');


    // Thanh toán QR
    Route::get(
        '/payment/qr/{order}',
        [PaymentController::class, 'qr']
    )->name('payment.qr');

    Route::get(
        '/payment/qr/{order}/status',
        [PaymentController::class, 'checkStatus']
    )->name('payment.qr.status');

    Route::post(
        '/payment/qr/{order}/confirm',
        [PaymentController::class, 'confirm']
    )->name('payment.qr.confirm');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Product
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'products',
            AdminProductController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Product Variant
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/products/{product}/variants',
            [AdminProductVariantController::class, 'store']
        )->name('products.variants.store');


        Route::put(
            '/variants/{variant}',
            [AdminProductVariantController::class, 'update']
        )->name('variants.update');


        Route::delete(
            '/variants/{variant}',
            [AdminProductVariantController::class, 'destroy']
        )->name('variants.destroy');


        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'categories',
            AdminCategoryController::class
        )->only([
            'index',
            'store',
            'update',
            'destroy'
        ]);


        /*
        |--------------------------------------------------------------------------
        | Order
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/orders',
            [AdminOrderController::class, 'index']
        )->name('orders.index');


        Route::get(
            '/orders/{order}',
            [AdminOrderController::class, 'show']
        )->name('orders.show');


        Route::patch(
            '/orders/{order}/status',
            [AdminOrderController::class, 'updateStatus']
        )->name('orders.update-status');


        Route::patch(
            '/orders/{order}/payment',
            [AdminOrderController::class, 'updatePayment']
        )->name('orders.update-payment');

        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'discounts',
            DiscountController::class
        );

        /*
        |--------------------------------------------------------------------------
        | Users / Customers
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/users',
            [AdminUserController::class, 'index']
        )->name('users.index');

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/statistics',
            [StatisticsController::class, 'index']
        )->name('statistics.index');

        Route::get(
            '/statistics/report',
            [StatisticsController::class, 'report']
        )->name('statistics.report');

        Route::get(
            '/statistics/export',
            [StatisticsController::class, 'export']
        )->name('statistics.export');
    });