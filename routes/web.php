<?php

use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\LuotThichController;
use App\Http\Controllers\NhaHangController;
use App\Http\Controllers\TrangCaNhanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BaiVietController;

use App\Http\Controllers\HomeController;

Route::get('/', action: [HomeController::class, 'index'])->name('home');



Route::get('baiviet', [BaiVietController::class, 'index'])->name('baiviet.index');
Route::get('baiviet/create', [BaiVietController::class, 'create'])->name('baiviet.create');
Route::post('baiviet', [BaiVietController::class, 'store'])->name(name: 'baiviet.store');
Route::get('baiviet/{id}', [BaiVietController::class, 'show'])->name('baiviet.show');
Route::get('baiviet/{id}/edit', [BaiVietController::class, 'edit'])->name('baiviet.edit');
Route::put('baiviet/{id}', [BaiVietController::class, 'update'])->name('baiviet.update');
Route::delete('baiviet/{id}', [BaiVietController::class, 'destroy'])->name('baiviet.destroy');

//like
Route::post('/baiviet/like/{id}', [LuotThichController::class, 'toggleLike'])->name('baiviet.like');


// Đăng ký
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Đăng nhập

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Đăng xuất
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Trang cá nhân
Route::prefix('trangcanhan')->name('trangcanhan.')->group(function () {
    Route::get('/', [TrangCaNhanController::class, 'showProfile'])->name('index');
    Route::get('/edit', [TrangCaNhanController::class, 'showSetupForm'])->name('edit');
    Route::post('/edit', [TrangCaNhanController::class, 'saveSetup'])->name('update');
});



//nhà hàng
Route::prefix('nhahang')->name('nhahang.')->group(function () {

    Route::get('/', [NhaHangController::class, 'index'])->name('index');

    Route::get('/create', [NhaHangController::class, 'create'])->name('create');
    Route::post('/store', [NhaHangController::class, 'store'])->name('store');

    Route::get('/{id}/edit', [NhaHangController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NhaHangController::class, 'update'])->name('update');
    Route::delete('/{id}', [NhaHangController::class, 'destroy'])->name('destroy');

    Route::get('/{id}', [NhaHangController::class, 'show'])->name('show');
});





// Viết đánh giá
Route::get('danhgia/create/{ma_bai_viet}', [DanhGiaController::class, 'create'])->name('danhgia.create');
Route::post('danhgia/store/{ma_bai_viet}', [DanhGiaController::class, 'store'])->name('danhgia.store');
Route::get('danhgia/index/{ma_bai_viet}', [DanhGiaController::class, 'index'])->name('danhgia.index');
Route::get('/danhgia/nha-hang/{ma_nha_hang}', [DanhGiaController::class, 'indexNhaHang'])->name('danhgia.index');
Route::post('/danhgia/like/{ma_danh_gia}', [DanhGiaController::class, 'like'])->name('danhgia.like');


// Danh sách đánh giá nhà hàng
Route::get('/danhgia/nha-hang/{ma_nha_hang}', [DanhGiaController::class, 'indexNhaHang'])->name('danhgia.index');

// Like đánh giá
Route::post('/danhgia/like/{ma_danh_gia}', [DanhGiaController::class, 'like'])->name('danhgia.like');

