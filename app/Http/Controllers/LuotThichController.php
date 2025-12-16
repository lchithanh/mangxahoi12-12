<?php
namespace App\Http\Controllers;

use App\Models\ThongBao;
use Illuminate\Http\Request;
use App\Models\LuotThich;
use App\Models\BaiViet;

class LuotThichController extends Controller
{
    public function toggleLike($id)
{
    $user = session('user');

    if (!$user) {
        return redirect()->back()->with('error', '⚠️ Cần đăng nhập để thích bài viết.');
    }

    $baiViet = BaiViet::findOrFail($id);

    $like = LuotThich::where('bai_viet_id', $id)
        ->where('nguoi_dung_id', $user->ma_nguoi_dung)
        ->first();

    if ($like) {
        // ❌ UNLIKE → chỉ xóa, KHÔNG tạo thông báo
        $like->delete();
    } else {
        // ✅ LIKE → tạo like + thông báo
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
                'noi_dung'      => $user->ho_ten . ' đã thích bài viết của bạn',
                'da_doc'        => 0,
                'thoi_gian_tao' => now(),
            ]);
        }
    }

    return redirect()->back();
}

}
