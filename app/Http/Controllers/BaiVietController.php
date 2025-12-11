<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\BaiViet;
use App\Models\NguoiDung;
use App\Models\NhaHang;
use App\Models\AnhBaiViet;

class BaiVietController extends Controller
{
    /**
     * Hiển thị danh sách tất cả bài viết
     */
    public function index()
    {
        $user = session('user'); 

        if ($user && $user->vai_tro === 'chu_quan' && !$user->nhaHang) {
            return redirect()->route('nhahang.create', ['ma_chu_so_huu' => $user->ma_nguoi_dung]);
        }

        if ($user && trim($user->vai_tro) === 'chu_quan') {

            $nhahangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)->pluck('ma_nha_hang');

            if ($nhahangs->isEmpty()) {
                $baiviets = collect();
            } else {
                $baiviets = BaiViet::with(['nguoiDang', 'anhBaiViets'])
                    ->whereIn('ma_nha_hang', $nhahangs)
                    ->orderBy('thoi_gian_tao', 'desc')
                    ->get();
            }
        } else {
            $baiviets = BaiViet::with(['nguoiDang', 'anhBaiViets'])
                ->orderBy('thoi_gian_tao', 'desc')
                ->get();
        }

        return view('baiviet.index', compact('baiviets', 'user'));
    }

    public function show($id)
{
    $user = session('user');

    $baiViet = BaiViet::with(['nguoiDang', 'nhaHang', 'anhBaiViets'])
        ->findOrFail($id);

    return view('baiviet.show', compact('baiViet','user'));
}
    /**
     * Form tạo bài viết
     */
    public function create()
    {
        $user = session('user');

        if (!$user || trim($user->vai_tro) !== 'chu_quan') {
            return redirect()->route('baiviet.index')
                ->with('error', '⚠️ Cần đăng nhập hoặc chỉ chủ quán mới được tạo bài viết.');
        }

        $nhahangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)->get();

        if ($nhahangs->isEmpty()) {
            return redirect()->route('baiviet.index')
                ->with('error', '⚠️ Bạn chưa có nhà hàng nào. Vui lòng tạo nhà hàng trước.');
        }

        return view('baiviet.create', compact('nhahangs', 'user'));
    }

    /**
     * Lưu bài viết mới
     */
    public function store(Request $request)
{
    $user = session('user'); // lấy user từ session

    if (!$user) {
        return redirect()->back()->with('error', 'Cần đăng nhập mới có thể đăng bài viết');
    }

    // Validate dữ liệu
    $request->validate([
        'ma_nha_hang' => 'required|integer',
        'noi_dung' => 'required|string',
        'anh_bai_viet.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // Tạo bài viết
    $baiViet = BaiViet::create([
        'ma_nha_hang' => $request->ma_nha_hang,
        'ma_nguoi_dang' => $user->ma_nguoi_dung,
        'noi_dung' => $request->noi_dung,
        'thoi_gian_tao' => now(),
    ]);

    // Upload ảnh nếu có
    if ($request->hasFile('anh_bai_viet')) {

        foreach ($request->file('anh_bai_viet') as $file) {

            // Lưu vào storage/app/public/baiviet
            $path = $file->store('baiviet', 'public');

            // Lưu đường dẫn vào database (có thể dùng asset() để hiển thị)
            AnhBaiViet::create([
                'ma_bai_viet' => $baiViet->ma_bai_viet,
                'duong_dan_anh' => 'storage/' . $path, // lưu đường dẫn để dùng asset() hiển thị
            ]);
        }
    }

    return redirect()->route('home')->with('success', 'Tạo bài viết thành công!');
}


    /**
     * Chỉnh sửa bài viết
     */
    public function edit($id)
    {
        $user = session('user');
        $baiViet = BaiViet::findOrFail($id);

        if (!$user || $user->vai_tro !== 'chu_quan' || $baiViet->ma_nguoi_dung != $user->ma_nguoi_dung) {
            return redirect()->route('baiviet.index')->with('error', '⚠️ Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $nhahangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)->get();

        return view('baiviet.edit', compact('baiViet', 'nhahangs', 'user'));
    }

    /**
     * Cập nhật bài viết
     */public function update(Request $request, $id)
{
    $user = session('user');
    $baiViet = BaiViet::findOrFail($id);

    // KIỂM TRA QUYỀN: chỉ chủ bài viết mới được sửa
    if (
        !$user ||
        $user->vai_tro !== 'chu_quan' ||
        $baiViet->ma_nguoi_dung != $user->ma_nguoi_dung
    ) {
        return redirect()->route('baiviet.index')
            ->with('error', '⚠️ Bạn không có quyền chỉnh sửa bài viết này.');
    }

    // VALIDATE
    $request->validate([
        'noi_dung' => 'nullable|string',
        'ma_nha_hang' => 'nullable|integer',
        'anh_bai_viet.*' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
    ]);

    // CẬP NHẬT BÀI VIẾT
    $baiViet->update([
        'noi_dung' => $request->noi_dung,
        'ma_nha_hang' => $request->ma_nha_hang,
    ]);

    // UPLOAD ẢNH (NẾU CÓ)
    if ($request->hasFile('anh_bai_viet')) {
        foreach ($request->file('anh_bai_viet') as $file) {
            $path = $file->store('uploads/baiviet', 'public');

            AnhBaiViet::create([
                'ma_bai_viet' => $baiViet->ma_bai_viet,
                'duong_dan_anh' => $path,
            ]);
        }
    }

    return redirect()->route('baiviet.index')
        ->with('success', 'Cập nhật bài viết thành công!');
}

/**
 * Xóa bài viết
 */
public function destroy($id)
{
    $user = session('user');
    $baiViet = BaiViet::findOrFail($id);

    // KIỂM TRA QUYỀN: chỉ chủ bài viết mới được xóa
    if (
        !$user ||
        $user->vai_tro !== 'chu_quan' ||
        $baiViet->ma_nguoi_dung != $user->ma_nguoi_dung
    ) {
        return redirect()->route('baiviet.index')
            ->with('error', '⚠️ Bạn không có quyền xóa bài viết này.');
    }

    // XÓA ẢNH
    $baiViet->anhBaiViets()->delete();

    // XÓA BÀI VIẾT
    $baiViet->delete();

    return redirect()->route('baiviet.index')
        ->with('success', 'Xóa bài viết thành công!');
}
}