<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\TheoDoi;
use App\Models\NguoiDung;
use App\Models\ThongBao;
use App\Models\NhaHang;

class TheoDoiController extends Controller
{
    // ===============================
    // Toggle Follow/Unfollow User
    // ===============================
    public function toggleFollowUser($maNguoiDungDuocTheoDoi)
    {
        $maNguoiDung = session('ma_nguoi_dung');
        if (!$maNguoiDung) return back()->with('error','Bạn chưa đăng nhập');
        if ($maNguoiDung == $maNguoiDungDuocTheoDoi) return back()->with('error','Không thể theo dõi chính mình');

        $exists = TheoDoi::where('ma_nguoi_dung', $maNguoiDung)
            ->where('ma_nguoi_duoc_theo_doi', $maNguoiDungDuocTheoDoi)
            ->exists();

        if ($exists) {
            TheoDoi::where('ma_nguoi_dung', $maNguoiDung)
                ->where('ma_nguoi_duoc_theo_doi', $maNguoiDungDuocTheoDoi)
                ->delete();
            return back()->with('success','Đã hủy theo dõi người dùng');
        }

        TheoDoi::create([
            'ma_nguoi_dung' => $maNguoiDung,
            'ma_nguoi_duoc_theo_doi' => $maNguoiDungDuocTheoDoi,
            'thoi_gian_tao' => now(),
        ]);
        // Lấy tên người gửi
        $nguoiGui = NguoiDung::find($maNguoiDung);


        // Thông báo
        ThongBao::create([
            'ma_nguoi_nhan' => $maNguoiDungDuocTheoDoi,
            'ma_nguoi_gui' => $maNguoiDung,
            'loai_thong_bao' => 'theo_doi',
            'ma_doi_tuong' => $maNguoiDung,
            'noi_dung' => $nguoiGui->ho_ten . " đã theo dõi bạn",
            'link'          => route('trangcanhan.index', $maNguoiDung), // <- link tới profile

            'da_doc' => 0,
            'thoi_gian_tao' => now(),
        ]);

        return back()->with('success','Đã theo dõi người dùng');
    }

    // ===============================
    // Toggle Follow/Unfollow NhaHang
    // ===============================
    public function toggleFollowNhaHang($maNhaHang)
    {
        $maNguoiDung = session('ma_nguoi_dung');
        if (!$maNguoiDung) return back()->with('error','Bạn chưa đăng nhập');

        $exists = TheoDoi::where('ma_nguoi_dung', $maNguoiDung)
            ->where('ma_nha_hang', $maNhaHang)
            ->exists();

        if ($exists) {
            TheoDoi::where('ma_nguoi_dung', $maNguoiDung)
                ->where('ma_nha_hang', $maNhaHang)
                ->delete();
            return back()->with('success','Đã hủy theo dõi nhà hàng');
        }

        TheoDoi::create([
            'ma_nguoi_dung' => $maNguoiDung,
            'ma_nha_hang' => $maNhaHang,
            'thoi_gian_tao' => now(),
        ]);

        $nhaHang = NhaHang::findOrFail($maNhaHang);
        // Lấy thông tin nhà hàng và người gửi để tạo thông báo
        $nhaHang = NhaHang::findOrFail($maNhaHang);
        $nguoiGui = NguoiDung::find($maNguoiDung); // Lấy thông tin người đang thực hiện hành động

        ThongBao::create([
            'ma_nguoi_nhan' => $nhaHang->ma_chu_so_huu,
            'ma_nguoi_gui' => $maNguoiDung,
            'loai_thong_bao' => 'theo_doi',
            'ma_doi_tuong' => $maNhaHang,
            'noi_dung' => $nguoiGui->ho_ten . " đã theo dõi nhà hàng của bạn",
            'da_doc' => 0,
            'thoi_gian_tao' => now(),
        ]);

        return back()->with('success','Đã theo dõi nhà hàng');
    }


}