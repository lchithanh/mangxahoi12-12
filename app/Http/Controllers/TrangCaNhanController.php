<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use App\Models\NhaHang;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class TrangCaNhanController extends Controller
{
    /**
     * =====================================================
     * FORM SETUP / CHỈNH SỬA PROFILE
     * =====================================================
     */
    public function showSetupForm()
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return redirect()->route('login');
        }

        $user = NguoiDung::findOrFail($maNguoiDung);

        return view('trangcanhan.setup', compact('user'));
    }

    /**
     * =====================================================
     * LƯU CẬP NHẬT PROFILE
     * =====================================================
     */
    public function saveSetup(Request $request)
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return redirect()->route('login');
        }

        $user = NguoiDung::findOrFail($maNguoiDung);

        // Validate
        $request->validate([
            'ho_ten'       => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:nguoi_dung,email,' . $user->ma_nguoi_dung . ',ma_nguoi_dung',
            'mo_ta'        => 'nullable|string|max:1000',
            'anh_dai_dien' => 'nullable|image|max:2048',
        ]);

        // Cập nhật thông tin
        $user->update([
            'ho_ten'  => $request->ho_ten,
            'email'   => $request->email,
            'mo_ta'   => $request->mo_ta,
        ]);

        /**
         * ============================
         * UPLOAD AVATAR (DOCUMENT_ROOT)
         * ============================
         */
        if ($request->hasFile('anh_dai_dien')) {

            // Xóa ảnh cũ
            if (
                $user->anh_dai_dien &&
                File::exists($_SERVER['DOCUMENT_ROOT'] . '/' . $user->anh_dai_dien)
            ) {
                File::delete($_SERVER['DOCUMENT_ROOT'] . '/' . $user->anh_dai_dien);
            }

            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/anh_nguoi_dung';
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('anh_dai_dien');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);

            $user->update([
                'anh_dai_dien' => 'uploads/anh_nguoi_dung/' . $fileName
            ]);
        }

        return redirect()
            ->route('trangcanhan.index')
            ->with('success', '✅ Cập nhật thông tin cá nhân thành công');
    }

    /**
     * =====================================================
     * HIỂN THỊ TRANG CÁ NHÂN
     * =====================================================
     */
    public function showProfile()
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');

        if (!$maNguoiDung) {
            return redirect()->route('login');
        }

        $user = NguoiDung::withCount('baiviets')
            ->with(['baiviets.anhBaiViets'])
            ->findOrFail($maNguoiDung);

        // Followers / Following
        $user->followers_count = $user->followers()->count();
        $user->following_count = $user->following()->count();

        /**
         * =========================
         * TRANG CÁ NHÂN CHỦ QUÁN
         * =========================
         */
        if ($user->vai_tro === 'chu_quan') {

            $nhaHangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)
                ->with('baiviets.anhBaiViets')
                ->get();

            $showDanhSach = $nhaHangs->count() >= 2;

            $danhGias = DanhGia::with('baiViet.nhaHang')
                ->where('ma_nguoi_dung', $user->ma_nguoi_dung)
                ->orderByDesc('thoi_gian_tao')
                ->get();

            return view('trangcanhan.owner', compact(
                'user',
                'nhaHangs',
                'showDanhSach',
                'danhGias'
            ));
        }

        /**
         * =========================
         * TRANG CÁ NHÂN NGƯỜI DÙNG
         * =========================
         */
        $danhGias = $user->danhGias()
            ->with('baiViet.nhaHang')
            ->orderByDesc('thoi_gian_tao')
            ->get();

        return view('trangcanhan.user', compact('user', 'danhGias'));
    }
}
