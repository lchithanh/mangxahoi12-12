<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LuuBaiViet;
use App\Models\BaiViet;
use Illuminate\Support\Facades\Session;

class LuuBaiVietController extends Controller
{
    // Lưu hoặc hủy lưu bài viết (toggle)
    public function toggle($id)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để lưu bài viết.');
        }

        $luu = LuuBaiViet::where('ma_bai_viet', $id)
            ->where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->first();

        if ($luu) {
            $luu->delete();
            return redirect()->back()->with('success', 'Đã xóa bài viết khỏi danh sách lưu.');
        }

        LuuBaiViet::create([
            'ma_bai_viet' => $id,
            'ma_nguoi_dung' => $user->ma_nguoi_dung,
            'thoi_gian_tao' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã lưu bài viết.');
    }

    // Hiển thị danh sách bài viết đã lưu của user
    public function index()
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('home')->with('error', 'Bạn cần đăng nhập để xem danh sách lưu.');
        }

        $luuBaiViets = LuuBaiViet::with('baiViet.anhBaiViets', 'baiViet.nhaHang')
            ->where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->orderBy('thoi_gian_tao', 'desc')
            ->get();

        return view('baiviet.save', compact('luuBaiViets', 'user'));
    }
}
