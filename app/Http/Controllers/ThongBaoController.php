<?php

namespace App\Http\Controllers;

use App\Models\ThongBao;
use Illuminate\Http\Request;

class ThongBaoController extends Controller
{
    /**
     * =========================
     * DANH SÁCH THÔNG BÁO
     * =========================
     */
    public function index()
    {
        // Lấy id người dùng từ session
        $userId = session('ma_nguoi_dung');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem thông báo.');
        }

        // Lấy toàn bộ thông báo của user
        $thongBaos = ThongBao::where('ma_nguoi_nhan', $userId)
            ->orderByDesc('thoi_gian_tao')
            ->get();

        // Đánh dấu tất cả thông báo chưa đọc thành đã đọc
        ThongBao::where('ma_nguoi_nhan', $userId)
            ->where('da_doc', 0)
            ->update(['da_doc' => 1]);

        // Dữ liệu cho header
        $soThongBaoChuaDoc = ThongBao::where('ma_nguoi_nhan', $userId)
            ->where('da_doc', 0)
            ->count();

        $thongBaosHeader = ThongBao::where('ma_nguoi_nhan', $userId)
            ->orderByDesc('thoi_gian_tao')
            ->limit(5)
            ->get();

        return view('thongbao.index', [
            'thongBaos' => $thongBaos,              // danh sách đầy đủ
            'soThongBaoChuaDoc' => $soThongBaoChuaDoc, // số lượng cho header
            'thongBaosHeader' => $thongBaosHeader,     // 5 thông báo mới nhất cho header
        ]);
    }

    /**
     * =========================
     * ĐÁNH DẤU ĐÃ ĐỌC
     * =========================
     */
    public function daDoc($id)
    {
        $userId = session('ma_nguoi_dung');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập.');
        }

        $thongBao = ThongBao::where('ma_thong_bao', $id)
            ->where('ma_nguoi_nhan', $userId) // chỉ cho phép đánh dấu thông báo của chính mình
            ->firstOrFail();

        $thongBao->update(['da_doc' => 1]);

        return back();
    }
    
}