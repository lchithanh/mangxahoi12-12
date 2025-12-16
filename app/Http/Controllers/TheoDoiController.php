<?php

namespace App\Http\Controllers;

use App\Models\TheoDoi;
use App\Models\NguoiDung;
use App\Models\ThongBao;
use App\Models\NhaHang;
use Illuminate\Http\Request;

class TheoDoiController extends Controller
{
    /**
     * =========================
     * FOLLOW USER
     * =========================
     */
    public function follow($maNguoiDungDuocTheoDoi)
    {
        $user = session('user'); // ✅ thống nhất session

        if (!$user) {
            return back()->with('error', 'Bạn chưa đăng nhập');
        }

        if ($user->ma_nguoi_dung == $maNguoiDungDuocTheoDoi) {
            return back()->with('error', 'Bạn không thể theo dõi chính mình!');
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

            // 🔔 THÔNG BÁO CHO USER ĐƯỢC FOLLOW
            ThongBao::create([
                'ma_nguoi_nhan' => $maNguoiDungDuocTheoDoi,
                'ma_nguoi_gui'  => $user->ma_nguoi_dung,
                'loai_thong_bao'=> 'theo_doi',
                'ma_doi_tuong'  => $user->ma_nguoi_dung,
                'noi_dung'      => $user->ho_ten . ' đã theo dõi bạn',
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
        $user = session('user');

        if (!$user) return back()->with('error', 'Bạn chưa đăng nhập');

        TheoDoi::where('ma_nguoi_dung', $user->ma_nguoi_dung)
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
        $user = session('user');

        if (!$user) return back()->with('error', 'Bạn chưa đăng nhập');

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

            // 🔔 THÔNG BÁO CHO CHỦ NHÀ HÀNG
            ThongBao::create([
                'ma_nguoi_nhan' => $nhaHang->ma_chu_so_huu,
                'ma_nguoi_gui'  => $user->ma_nguoi_dung,
                'loai_thong_bao'=> 'theo_doi',
                'ma_doi_tuong'  => $id,
                'noi_dung'      => $user->ho_ten . ' đã theo dõi nhà hàng của bạn',
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
        $user = session('user');

        if (!$user) return back()->with('error', 'Bạn chưa đăng nhập');

        TheoDoi::where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->where('ma_nha_hang', $id)
            ->delete();

        return back()->with('success', 'Đã hủy theo dõi nhà hàng.');
    }
}
