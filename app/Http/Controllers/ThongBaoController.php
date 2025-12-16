<?php

namespace App\Http\Controllers;

use App\Models\ThongBao;
use Illuminate\Http\Request;

class ThongBaoController extends Controller
{
    // Danh sách thông báo
    public function index()
    {
        $user = session('user');

        $thongBaos = ThongBao::where('ma_nguoi_nhan', $user->ma_nguoi_dung)
            ->orderByDesc('thoi_gian_tao')
            ->get();

        // Đánh dấu đã đọc
        ThongBao::where('ma_nguoi_nhan', $user->ma_nguoi_dung)
            ->where('da_doc', 0)
            ->update(['da_doc' => 1]);

        return view('thongbao.index', compact('thongBaos'));
    }


    // Đánh dấu đã đọc
    public function daDoc($id)
    {
        $thongBao = ThongBao::findOrFail($id);
        $thongBao->da_doc = 1;
        $thongBao->save();

        return back();
    }
}
