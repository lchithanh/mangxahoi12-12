<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\LuuBaiViet;
use App\Models\BaiViet;
use App\Models\ThongBao;
use App\Models\NguoiDung;

class LuuBaiVietController extends Controller
{
    /**
     * Lưu / hủy lưu bài viết (toggle)
     */
    public function toggle($id)
    {
        /* ========== KIỂM TRA ĐĂNG NHẬP ========== */
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để lưu bài viết.');
        }

        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return redirect()->back()->with('error', 'Phiên đăng nhập không hợp lệ.');
        }

        /* ========== TOGGLE LƯU ========== */
        $luu = LuuBaiViet::where('ma_bai_viet', $id)
            ->where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->first();

        if ($luu) {
            $luu->delete();
            return redirect()->back()->with('success', 'Đã xóa bài viết khỏi danh sách lưu.');
        }

        LuuBaiViet::create([
            'ma_bai_viet'   => $id,
            'ma_nguoi_dung' => $user->ma_nguoi_dung,
            'thoi_gian_tao' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã lưu bài viết.');
    }

    /**
     * Danh sách bài viết đã lưu
     */
    public function index()
    {
        /* ========== USER TỪ SESSION ID ========== */
        $maNguoiDung = Session::get('ma_nguoi_dung');
        if (!$maNguoiDung) {
            return redirect()->route('home');
        }

        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return redirect()->route('home');
        }

        /* ========== LẤY BÀI VIẾT ĐÃ LƯU ========== */
        $luuBaiViets = LuuBaiViet::with([
                'baiViet' => fn ($q) => $q->with(['anhBaiViets', 'nhaHang', 'nguoiDang'])
            ])
            ->where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->get()
            ->filter(fn ($luu) => $luu->baiViet);

        $baiviets = $luuBaiViets->pluck('baiViet');
        $luuBaiVietIds = $luuBaiViets->pluck('ma_bai_viet')->toArray();

        /* ========== THÔNG BÁO HEADER ========== */
        $soThongBaoChuaDoc = ThongBao::where('ma_nguoi_nhan', $user->ma_nguoi_dung)
            ->where('da_doc', 0)
            ->count();

        $thongBaosHeader = ThongBao::where('ma_nguoi_nhan', $user->ma_nguoi_dung)
            ->orderByDesc('thoi_gian_tao')
            ->limit(5)
            ->get();

        return view('baiviet.save', [
            'user'               => $user,
            'baiviets'           => $baiviets,
            'luuBaiVietIds'      => $luuBaiVietIds,
            'soThongBaoChuaDoc'  => $soThongBaoChuaDoc,
            'thongBaosHeader'    => $thongBaosHeader,
        ]);
    }
}
