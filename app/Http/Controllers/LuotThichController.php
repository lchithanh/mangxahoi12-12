<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\ThongBao;
use App\Models\LuotThich;
use App\Models\BaiViet;
use App\Models\NguoiDung;

class LuotThichController extends Controller
{
    public function toggleLike($id)
    {
        /* ========== KIỂM TRA ĐĂNG NHẬP ========== */
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return redirect()->back()->with('error', '⚠️ Cần đăng nhập để thích bài viết.');
        }

        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return redirect()->back()->with('error', 'Phiên đăng nhập không hợp lệ.');
        }

        /* ========== BÀI VIẾT ========== */
        $baiViet = BaiViet::findOrFail($id);

        /* ========== KIỂM TRA ĐÃ LIKE CHƯA ========== */
        $like = LuotThich::where('bai_viet_id', $id)
            ->where('nguoi_dung_id', $user->ma_nguoi_dung)
            ->first();

        if ($like) {
            // ❌ UNLIKE → chỉ xóa
            $like->delete();
        } else {
            // ✅ LIKE → tạo lượt thích
            LuotThich::create([
                'bai_viet_id'   => $id,
                'nguoi_dung_id' => $user->ma_nguoi_dung,
                'ngay_thich'    => now(),
            ]);

            // ❗ Không gửi thông báo nếu tự like bài của mình
            if ($baiViet->ma_nguoi_dang != $user->ma_nguoi_dung) {
                ThongBao::create([
                    'ma_nguoi_nhan' => $baiViet->ma_nguoi_dang,
                    'ma_nguoi_gui'  => $user->ma_nguoi_dung,
                    'loai_thong_bao'=> 'thich',
                    'ma_doi_tuong'  => $baiViet->ma_bai_viet,
                    'noi_dung'      => "{$user->ho_ten} đã thích bài viết của bạn",
                    'link'          => route('baiviet.show', $id), // <- link tới bài viết

                    'da_doc'        => 0,
                    'thoi_gian_tao' => now(),
                ]);
            }
        }

        return redirect()->back();
    }
}
