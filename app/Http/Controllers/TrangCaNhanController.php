<?php

namespace App\Http\Controllers;

use App\Models\NhaHang;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TrangCaNhanController extends Controller
{
    /**
     * =====================================================
     * FORM SETUP / CHỈNH SỬA PROFILE
     * =====================================================
     */
    public function showSetupForm()
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('login');
        }

        return view('trangcanhan.setup', compact('user'));
    }

    /**
     * =====================================================
     * LƯU CẬP NHẬT PROFILE
     * =====================================================
     */
    public function saveSetup(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('login');
        }

        // 🔁 Lấy user mới nhất từ DB
        $user = NguoiDung::findOrFail($user->ma_nguoi_dung);

        // ✅ Validate
        $request->validate([
            'ho_ten'        => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:nguoi_dung,email,' . $user->ma_nguoi_dung . ',ma_nguoi_dung',
            'gioi_thieu'    => 'nullable|string|max:1000',
            'anh_dai_dien'  => 'nullable|image|max:2048',
        ]);

        // Update thông tin
        $user->update([
            'ho_ten'      => $request->ho_ten,
            'email'       => $request->email,
            'gioi_thieu'  => $request->gioi_thieu,
        ]);

        /**
         * ============================
         * UPLOAD AVATAR
         * ============================
         */
        if ($request->hasFile('anh_dai_dien')) {

            if ($user->anh_dai_dien && File::exists(public_path($user->anh_dai_dien))) {
                File::delete(public_path($user->anh_dai_dien));
            }

            $path = public_path('uploads/anh_nguoi_dung');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $file = $request->file('anh_dai_dien');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $fileName);

            $user->anh_dai_dien = 'uploads/anh_nguoi_dung/' . $fileName;
            $user->save();
        }

        // 🔁 CẬP NHẬT LẠI SESSION USER
        session(['user' => $user]);

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
        $userSession = session('user');

        if (!$userSession) {
            return redirect()->route('login');
        }

        $user = NguoiDung::withCount('baiviets')
            ->with(['baiviets.anhBaiViets'])
            ->findOrFail($userSession->ma_nguoi_dung);

        // 👥 Followers / Following
        $user->followers_count = $user->followers()->count();
        $user->following_count = $user->following()->count();

        
        /**
         * ============================
         * TRANG CÁ NHÂN CHỦ QUÁN
         * ============================
         */
        if ($user->vai_tro === 'chu_quan') {

            $nhaHangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)
                ->with('baiViets.anhBaiViets')
                ->get();

            $showDanhSach = $nhaHangs->count() >= 2;

            // Lấy danh sách đánh giá mà chủ quán đã gửi cho các bài viết khác
            $danhGias = \App\Models\DanhGia::with('baiViet.nhaHang')
                ->where('ma_nguoi_dung', $user->ma_nguoi_dung)
                ->orderByDesc('thoi_gian_tao')
                ->get();

            return view('trangcanhan.owner', compact(
                'user',
                'nhaHangs',
                'showDanhSach',
                'danhGias'   // 🔹 thêm dòng này
            ));
        }


        /**
         * ============================
         * TRANG CÁ NHÂN NGƯỜI DÙNG
         * ============================
         */
        $danhGias = $user->danhGias()
            ->with('baiViet.nhaHang')
            ->get();

        return view('trangcanhan.user', compact('user', 'danhGias'));
    }
}
