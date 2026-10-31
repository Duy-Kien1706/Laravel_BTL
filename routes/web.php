<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Guest Routes (Đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes (Bảo vệ bởi Custom Middleware: admin)
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Quản lý Thuốc & Danh mục
    Route::resource('medicines', MedicineController::class);
    Route::resource('categories', CategoryController::class);

    // 2. Quản lý Hóa Đơn (Bán hàng)
    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');

    // 3. Quản lý Nhà Cung Cấp & Nhân Viên (Chỉ Admin)
    Route::middleware('admin:only')->group(function () {
        Route::resource('suppliers', SupplierController::class)->except(['show']);
        Route::resource('staff', StaffController::class);
    });
});
