<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\NguoiDung;
use App\Models\BaiViet;

class HomeController extends Controller
{
    public function index()
    {
        $user = null;

        // Kiểm tra session
        if (Session::has('ma_nguoi_dung')) {
            $user = NguoiDung::find(Session::get('ma_nguoi_dung'));
        }

        // Lấy danh sách bài viết mới nhất kèm quan hệ
        $baiviets = BaiViet::with(['nguoiDang', 'anhBaiViets', ])
            ->orderBy('thoi_gian_tao', 'desc')
            ->get();

        return view('home', compact('user', 'baiviets'));
    }
}
