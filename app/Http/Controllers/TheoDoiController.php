<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\TheoDoi;
use App\Models\NguoiDung;
use App\Models\ThongBao;
use App\Models\NhaHang;

class TheoDoiController extends Controller
{
    /**
     * =========================
     * FOLLOW USER
     * =========================
     */
    public function follow($maNguoiDungDuocTheoDoi)
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return back()->with('error', 'Bạn chưa đăng nhập');
        }

        if ($maNguoiDung == $maNguoiDungDuocTheoDoi) {
            return back()->with('error', 'Bạn không thể theo dõi chính mình!');
        }

        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return back()->with('error', 'Phiên đăng nhập không hợp lệ.');
        }

        $exists = TheoDoi::where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->where('ma_nguoi_duoc_theo_doi', $maNguoiDungDuocTheoDoi)
            ->exists();

        if (!$exists) {
            TheoDoi::create([
                'ma_nguoi_dung'          => $user->ma_nguoi_dung,
                'ma_nguoi_duoc_theo_doi' => $maNguoiDungDuocTheoDoi,
                'thoi_gian_tao'          => now(),
            ]);

            // 🔔 Thông báo cho người được theo dõi
            ThongBao::create([
                'ma_nguoi_nhan' => $maNguoiDungDuocTheoDoi,
                'ma_nguoi_gui'  => $user->ma_nguoi_dung,
                'loai_thong_bao'=> 'theo_doi',
                'ma_doi_tuong'  => $user->ma_nguoi_dung,
                'noi_dung'      => "{$user->ho_ten} đã theo dõi bạn",
                'da_doc'        => 0,
                'thoi_gian_tao' => now(),
            ]);
        }

        return back()->with('success', 'Đã theo dõi người dùng.');
    }

    /**
     * =========================
     * UNFOLLOW USER
     * =========================
     */
    public function unfollow($maNguoiDungDuocTheoDoi)
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return back()->with('error', 'Bạn chưa đăng nhập');
        }

        TheoDoi::where('ma_nguoi_dung', $maNguoiDung)
            ->where('ma_nguoi_duoc_theo_doi', $maNguoiDungDuocTheoDoi)
            ->delete();

        return back()->with('success', 'Đã hủy theo dõi.');
    }

    /**
     * =========================
     * DANH SÁCH FOLLOWERS
     * =========================
     */
    public function followers($maNguoiDung)
    {
        $nguoiDung = NguoiDung::findOrFail($maNguoiDung);
        $followers = $nguoiDung->followers()->get();

        return view('trangcanhan.followers', compact('nguoiDung', 'followers'));
    }

    /**
     * =========================
     * DANH SÁCH FOLLOWING
     * =========================
     */
    public function following($maNguoiDung)
    {
        $nguoiDung = NguoiDung::findOrFail($maNguoiDung);
        $following = $nguoiDung->following()->get();

        return view('trangcanhan.following', compact('nguoiDung', 'following'));
    }

    /**
     * =========================
     * FOLLOW NHÀ HÀNG
     * =========================
     */
    public function followNhaHang($id)
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return back()->with('error', 'Bạn chưa đăng nhập');
        }

        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return back()->with('error', 'Phiên đăng nhập không hợp lệ.');
        }

        $exists = TheoDoi::where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->where('ma_nha_hang', $id)
            ->exists();

        if (!$exists) {
            TheoDoi::create([
                'ma_nguoi_dung' => $user->ma_nguoi_dung,
                'ma_nha_hang'   => $id,
                'thoi_gian_tao' => now(),
            ]);

            $nhaHang = NhaHang::findOrFail($id);

            // 🔔 Thông báo cho chủ nhà hàng
            ThongBao::create([
                'ma_nguoi_nhan' => $nhaHang->ma_chu_so_huu,
                'ma_nguoi_gui'  => $user->ma_nguoi_dung,
                'loai_thong_bao'=> 'theo_doi',
                'ma_doi_tuong'  => $id,
                'noi_dung'      => "{$user->ho_ten} đã theo dõi nhà hàng của bạn",
                'da_doc'        => 0,
                'thoi_gian_tao' => now(),
            ]);
        }

        return back()->with('success', 'Đã theo dõi nhà hàng.');
    }

    /**
     * =========================
     * UNFOLLOW NHÀ HÀNG
     * =========================
     */
    public function unfollowNhaHang($id)
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return back()->with('error', 'Bạn chưa đăng nhập');
        }

        TheoDoi::where('ma_nguoi_dung', $maNguoiDung)
            ->where('ma_nha_hang', $id)
            ->delete();

        return back()->with('success', 'Đã hủy theo dõi nhà hàng.');
    }
}
