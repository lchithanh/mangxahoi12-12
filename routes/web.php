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
  Route::get('/setup', [TrangCaNhanController::class, 'showSetupForm'])->name('edit');
    Route::post('/setup', [TrangCaNhanController::class, 'saveSetup'])->name('save');
        Route::get('/{id}', [TrangCaNhanController::class, 'showProfile'])->name('index');



    // Xem danh sách đang theo dõi / người theo dõi
    Route::get('/{id}/dang-theo-doi', [TrangCaNhanController::class, 'showFollowing'])->name('dangtheodoi');
    Route::get('/{id}/nguoi-theo-doi', [TrangCaNhanController::class, 'showFollowers'])->name('theodoi');

    // Toggle follow/unfollow người dùng (POST)
    Route::post('/{id}/nguoi-theo-doi/toggle', [TheoDoiController::class, 'toggleFollowUser'])
    ->name('toggle.user');

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
|----------------------------------------------------------------------
| Nhà hàng
|----------------------------------------------------------------------
*/
Route::prefix('nhahang')->name('nhahang.')->group(function () {
    Route::get('/', [NhaHangController::class, 'index'])->name('index');
    Route::get('/create', [NhaHangController::class, 'create'])->name('create');
    Route::post('/', [NhaHangController::class, 'store'])->name('store');

    Route::get('/{id}', [NhaHangController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [NhaHangController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NhaHangController::class, 'update'])->name('update');
    Route::delete('/{id}', [NhaHangController::class, 'destroy'])->name('destroy');

    // Toggle follow/unfollow nhà hàng
    Route::post('/{id}/toggle-follow', [TheoDoiController::class, 'toggleFollowNhaHang'])->name('toggleFollow');
});



/* 
|--------------------------------------------------------------------------
| Đánh giá
|--------------------------------------------------------------------------
*/
Route::prefix('danhgia')->name('danhgia.')->group(function () {
    Route::get('/baiviet/{id}', [DanhGiaController::class, 'index'])->name('index');
    Route::post('/store/{id}', [DanhGiaController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [DanhGiaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DanhGiaController::class, 'update'])->name('update');
    Route::delete('/{id}', [DanhGiaController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/like', [DanhGiaController::class, 'like'])->name('like');
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
