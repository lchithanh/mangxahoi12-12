<?php

use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\KhuVucController;
use App\Http\Controllers\LuotThichController;
use App\Http\Controllers\NhaHangController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TheoDoiController;
use App\Http\Controllers\TinNhanController;
use App\Http\Controllers\TrangCaNhanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BaiVietController;
use App\Http\Controllers\LuuBaiVietController;


use App\Http\Controllers\HomeController;

Route::get('/', action: [HomeController::class, 'index'])->name('home');



Route::get('baiviet', [BaiVietController::class, 'index'])->name('baiviet.index');
Route::get('baiviet/create', [BaiVietController::class, 'create'])->name('baiviet.create');
Route::post('baiviet', [BaiVietController::class, 'store'])->name(name: 'baiviet.store');
// ĐẶT /edit trước /{id}
 Route::get('/{id}/edit', [BaiVietController::class, 'edit'])->name('baiviet.edit-bv');
// Xóa ảnh bài viết riêng lẻ
Route::delete('/anh/{id}', [BaiVietController::class, 'xoaAnh'])->name('baiviet.xoaAnh');

Route::put('baiviet/{id}', [BaiVietController::class, 'update'])->name('baiviet.update');
    //Route::post('/{id}/save', [BaiVietController::class, 'save'])->name('baiviet.save');

Route::delete('baiviet/{id}', [BaiVietController::class, 'destroy'])->name('baiviet.destroy');

//like
Route::post('/baiviet/like/{id}', [LuotThichController::class, 'toggleLike'])->name('baiviet.like');
Route::get('baiviet/{id}', [BaiVietController::class, 'show'])->name('baiviet.show');


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

// Hiển thị profile
Route::get('/trangcanhan', [TrangCaNhanController::class, 'showProfile'])->name('trangcanhan.index');

// Hiển thị form chỉnh sửa
Route::get('/trangcanhan/setup', [TrangCaNhanController::class, 'showSetupForm'])->name('trangcanhan.edit');

// Lưu thay đổi
Route::post('/trangcanhan/setup', [TrangCaNhanController::class, 'saveSetup'])->name('trangcanhan.saveSetup');




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





// Danh sách đánh giá bài viết
Route::get('danhgia/index/{ma_bai_viet}', [DanhGiaController::class, 'index'])->name('danhgia.index');

// Danh sách đánh giá nhà hàng
Route::get('/danhgia/nha-hang/{ma_nha_hang}', [DanhGiaController::class, 'indexNhaHang'])->name('danhgia.indexNhaHang');

// Form tạo đánh giá
Route::get('danhgia/create/{ma_bai_viet}', [DanhGiaController::class, 'create'])->name('danhgia.create');
Route::post('danhgia/store/{ma_bai_viet}', [DanhGiaController::class, 'store'])->name('danhgia.store');

// Like đánh giá
Route::post('/danhgia/like/{ma_danh_gia}', [DanhGiaController::class, 'like'])->name('danhgia.like');


// Toggle lưu bài viết
Route::post('/baiviet/save/{id}', [LuuBaiVietController::class, 'toggle'])->name('baiviet.save');

// Danh sách bài viết đã lưu
Route::get('/luu-bai-viet', [LuuBaiVietController::class, 'index'])->name('luu_baiviet.index');

//theo dõi
Route::get('/follow/{id}', [TheoDoiController::class, 'follow'])->name('follow');
    Route::get('/unfollow/{id}', [TheoDoiController::class, 'unfollow'])->name('unfollow');
 // Theo dõi nhà hàng
Route::get('/follow/nhahang/{id}', [TheoDoiController::class, 'followNhaHang'])->name('follow.nhahang');
Route::get('/unfollow/nhahang/{id}', [TheoDoiController::class, 'unfollowNhaHang'])->name('unfollow.nhahang');


Route::get('/tin-nhan', [TinNhanController::class, 'index'])->name('tinnhan.index');
Route::get('/nha-hang/{id}/chat', [TinNhanController::class, 'show'])->name('tinnhan.show');
Route::post('/tin-nhan', [TinNhanController::class, 'store'])->name('tinnhan.store');

// API realtime (polling)
Route::get('/tin-nhan/fetch/{maNhaHang}', [TinNhanController::class, 'fetch'])
    ->name('tinnhan.fetch');


Route::resource('khuvuc', KhuVucController::class);
