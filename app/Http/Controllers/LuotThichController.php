<?php
namespace App\Http\Controllers;

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

        // Kiểm tra user đã like chưa
        $like = LuotThich::where('bai_viet_id', $id)
            ->where('nguoi_dung_id', $user->ma_nguoi_dung)
            ->first();

        if ($like) {
            // Nếu đã like → unlike
            $like->delete();
        } else {
            // Thêm lượt like
            LuotThich::create([
                'bai_viet_id' => $id,
                'nguoi_dung_id' => $user->ma_nguoi_dung,
                'ngay_thich' => now(),
            ]);
        }

        return redirect()->back();
    }
}
