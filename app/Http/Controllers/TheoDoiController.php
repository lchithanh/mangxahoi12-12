<?php

namespace App\Http\Controllers;

use App\Models\TheoDoi;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TheoDoiController extends Controller
{
    /**
     * Theo dõi một người dùng
     */
    public function follow($maNguoiDungDuocTheoDoi)
    {
        $maNguoiDung = session('ma_nguoi_dung'); // người đang đăng nhập

        if ($maNguoiDung == $maNguoiDungDuocTheoDoi) {
            return redirect()->back()->with('error', 'Bạn không thể theo dõi chính mình!');
        }

        $exists = TheoDoi::where('ma_nguoi_dung', $maNguoiDung)
            ->where('ma_nguoi_duoc_theo_doi', $maNguoiDungDuocTheoDoi)
            ->first();

        if (!$exists) {
            TheoDoi::create([
                'ma_nguoi_dung' => $maNguoiDung,
                'ma_nguoi_duoc_theo_doi' => $maNguoiDungDuocTheoDoi,
                'thoi_gian_tao' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Đã theo dõi người dùng.');
    }

    /**
     * Hủy theo dõi
     */
    public function unfollow($maNguoiDungDuocTheoDoi)
    {
        $maNguoiDung = session('ma_nguoi_dung');

        TheoDoi::where('ma_nguoi_dung', $maNguoiDung)
            ->where('ma_nguoi_duoc_theo_doi', $maNguoiDungDuocTheoDoi)
            ->delete();

        return redirect()->back()->with('success', 'Đã hủy theo dõi.');
    }

    /**
     * Danh sách người theo dõi
     */
    public function followers($maNguoiDung)
    {
        $nguoiDung = NguoiDung::find($maNguoiDung);

        $followers = $nguoiDung->followers()->get(); // dùng quan hệ followers trong model NguoiDung

        return view('trangcanhan.followers', compact('nguoiDung', 'followers'));
    }

    /**
     * Danh sách người đang theo dõi
     */
    public function following($maNguoiDung)
    {
        $nguoiDung = NguoiDung::find($maNguoiDung);

        $following = $nguoiDung->following()->get(); // dùng quan hệ following trong model NguoiDung

        return view('trangcanhan.following', compact('nguoiDung', 'following'));
    }
    public function followNhaHang($id)
{
    $userId = session('user')->ma_nguoi_dung;

    // Kiểm tra đã follow chưa
    $exists = TheoDoi::where('ma_nguoi_dung', $userId)
                    ->where('ma_nha_hang', $id)
                    ->exists();

    if (!$exists) {
        TheoDoi::create([
            'ma_nguoi_dung' => $userId,
            'ma_nha_hang' => $id,
            'thoi_gian_tao' => now(),
        ]);
    }

    return back();
}

public function unfollowNhaHang($id)
{
    $userId = session('user')->ma_nguoi_dung;

    TheoDoi::where('ma_nguoi_dung', $userId)
           ->where('ma_nha_hang', $id)
           ->delete();

    return back();
}

}
