<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\WishlistController;
use App\Http\Controllers\Front\AccountController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminReportController;

// TEMP setup route (remove after one-time DB provisioning)
Route::get('/__setup__', function (\Illuminate\Http\Request $request) {
    if ($request->query('key') !== env('SETUP_KEY')) {
        abort(403);
    }
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');
    return response(\Illuminate\Support\Facades\Artisan::output());
});

// Language Switcher Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
    }
    return redirect()->back();
})->name('lang.switch');

// Front Storefront Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/quick-view/{id}', [ProductController::class, 'quickView'])->name('product.quickview');
Route::post('/product/{id}/review', [ProductController::class, 'submitReview'])->name('product.review');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/drawer-data', [CartController::class, 'drawerData'])->name('cart.drawer');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/cart/coupon', [CartController::class, 'clearCoupon'])->name('cart.coupon.clear');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/invoice/{orderNumber}', [CheckoutController::class, 'invoice'])->name('invoice.show');

// Informational Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/policies', [PageController::class, 'policies'])->name('policies');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Customer Account Routes
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [AccountController::class, 'orderDetail'])->name('order.detail');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'saveAddress'])->name('address.save');
    Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])->name('address.delete');
    Route::post('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
});

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Coupons
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::delete('/coupons/{id}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
});
