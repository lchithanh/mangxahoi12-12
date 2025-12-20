<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    BaiVietController,
    LuuBaiVietController,
    LuotThichController,
    DanhGiaController,
    NhaHangController,
    TheoDoiController,
    PhongChatController,
    TinNhanController,
    TrangCaNhanController,
    ThongBaoController,
    KhuVucController,
    RegisterController,
    LoginController
};

/*
|--------------------------------------------------------------------------
| Trang chủ
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Authentication (Session-based)
|--------------------------------------------------------------------------
*/
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register',  [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

/*
|--------------------------------------------------------------------------
| Trang cá nhân
|--------------------------------------------------------------------------
*/
Route::prefix('trangcanhan')->name('trangcanhan.')->group(function () {
    Route::get('/',        [TrangCaNhanController::class, 'showProfile'])->name('index');
    Route::get('/setup',   [TrangCaNhanController::class, 'showSetupForm'])->name('edit');
    Route::post('/setup',  [TrangCaNhanController::class, 'saveSetup'])->name('save');
});

/*
|--------------------------------------------------------------------------
| Bài viết
|--------------------------------------------------------------------------
*/
Route::prefix('baiviet')->name('baiviet.')->group(function () {
    Route::get('/',        [BaiVietController::class, 'index'])->name('index');
    Route::get('/create',  [BaiVietController::class, 'create'])->name('create');
    Route::post('/',       [BaiVietController::class, 'store'])->name('store');

    Route::get('/{id}',        [BaiVietController::class, 'show'])->name('show');
    Route::get('/{id}/edit',   [BaiVietController::class, 'edit'])->name('edit');
    Route::put('/{id}',        [BaiVietController::class, 'update'])->name('update');
    Route::delete('/{id}',     [BaiVietController::class, 'destroy'])->name('destroy');

    Route::post('/{id}/like',  [LuotThichController::class, 'toggleLike'])->name('like');
    Route::post('/{id}/luu',   [LuuBaiVietController::class, 'toggle'])->name('save');

    Route::delete('/anh/{id}', [BaiVietController::class, 'xoaAnh'])->name('xoaAnh');
});

/*
|--------------------------------------------------------------------------
| Bài viết đã lưu
|--------------------------------------------------------------------------
*/
Route::get('/luu-bai-viet', [LuuBaiVietController::class, 'index'])
    ->name('luu_baiviet.index');

/*
|--------------------------------------------------------------------------
| Nhà hàng
|--------------------------------------------------------------------------
*/
Route::prefix('nhahang')->name('nhahang.')->group(function () {
    Route::get('/',        [NhaHangController::class, 'index'])->name('index');
    Route::get('/create',  [NhaHangController::class, 'create'])->name('create');
    Route::post('/',       [NhaHangController::class, 'store'])->name('store');

    Route::get('/{id}',        [NhaHangController::class, 'show'])->name('show');
    Route::get('/{id}/edit',   [NhaHangController::class, 'edit'])->name('edit');
    Route::put('/{id}',        [NhaHangController::class, 'update'])->name('update');
    Route::delete('/{id}',     [NhaHangController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Đánh giá
|--------------------------------------------------------------------------
*/
Route::prefix('danhgia')->name('danhgia.')->group(function () {
    Route::get('/baiviet/{id}',   [DanhGiaController::class, 'index'])->name('baiviet');
    Route::get('/nhahang/{id}',   [DanhGiaController::class, 'indexNhaHang'])->name('nhahang');

    Route::get('/create/{id}',    [DanhGiaController::class, 'create'])->name('create');
    Route::post('/store/{id}',    [DanhGiaController::class, 'store'])->name('store');

    Route::get('/{id}/edit',      [DanhGiaController::class, 'edit'])->name('edit');
    Route::put('/{id}',           [DanhGiaController::class, 'update'])->name('update');
    Route::delete('/{id}',        [DanhGiaController::class, 'destroy'])->name('destroy');

    Route::post('/{id}/like',     [DanhGiaController::class, 'like'])->name('like');
});

/*
|--------------------------------------------------------------------------
| Theo dõi
|--------------------------------------------------------------------------
*/
Route::prefix('follow')->name('follow.')->group(function () {
    Route::post('/user/{id}',     [TheoDoiController::class, 'follow'])->name('user');
    Route::post('/nhahang/{id}',  [TheoDoiController::class, 'followNhaHang'])->name('nhahang');

    Route::delete('/user/{id}',     [TheoDoiController::class, 'unfollow'])->name('user.un');
    Route::delete('/nhahang/{id}',  [TheoDoiController::class, 'unfollowNhaHang'])->name('nhahang.un');
});

/*
|--------------------------------------------------------------------------
| Phòng chat & Tin nhắn
|--------------------------------------------------------------------------
*/

Route::prefix('tinnhan')->name('tinnhan.')->group(function () {
    // Danh sách phòng chat
    Route::get('/', [PhongChatController::class, 'index'])->name('index');

    // Tạo phòng chat mới
    Route::post('/phong', [PhongChatController::class, 'store'])->name('phong.store');

    // Hiển thị chi tiết phòng chat
    Route::get('/phong/{id}', [PhongChatController::class, 'show'])->name('phong.show');

    // Gửi tin nhắn trong phòng chat
    Route::post('/phong/{id}/gui', [TinNhanController::class, 'store'])->name('gui');
});

/*
|--------------------------------------------------------------------------
| Thông báo
|--------------------------------------------------------------------------
*/
Route::prefix('thong-bao')->name('thongbao.')->group(function () {
    Route::get('/',                   [ThongBaoController::class, 'index'])->name('index');
    Route::post('/{id}/da-doc',        [ThongBaoController::class, 'daDoc'])->name('dadoc');
});

/*
|--------------------------------------------------------------------------
| Khu vực
|--------------------------------------------------------------------------
*/
Route::resource('khuvuc', KhuVucController::class);
