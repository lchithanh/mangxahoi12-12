<?php

namespace App\Http\Controllers;

use App\Models\NhaHang;
use Illuminate\Http\Request;
use App\Models\NguoiDung;

class TrangCaNhanController extends Controller
{
    // Hiển thị form setup profile (chỉnh sửa)
    public function showSetupForm()
    {
        $user = NguoiDung::find(session('ma_nguoi_dung'));

        if (!$user) {
            return redirect()->route('login');
        }

        return view('trangcanhan.setup', compact('user'));
    }

    // Lưu cập nhật thông tin profile
    public function saveSetup(Request $request)
    {
        $user = NguoiDung::find(session('ma_nguoi_dung'));

        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'ho_ten' => 'required',
            'anh_dai_dien' => 'nullable|image|max:2048'
        ]);

        $user->ho_ten = $request->ho_ten;

        if ($request->hasFile('anh_dai_dien')) {
            $path = $request->file('anh_dai_dien')->store('avatars', 'public');
            $user->anh_dai_dien = $path;
        }

        $user->save();

        return redirect()->route('trangcanhan.index')->with('success', 'Cập nhật thông tin thành công');
    }

    // Hiển thị trang cá nhân
    public function showProfile()
{
    $user = NguoiDung::withCount('baiviets')
                      ->with(['baiviets.anhBaiViets'])
                      ->find(session('ma_nguoi_dung'));

    if (!$user) {
        return redirect()->route('login');
    }

    // Đếm followers và following
    $user->followers_count = $user->followers()->count();
    $user->following_count = $user->following()->count();

    // Lấy nhà hàng của user (nếu có)
    $nhaHang = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)->first();

    return view('trangcanhan.index', compact('user', 'nhaHang')); 
}

}
