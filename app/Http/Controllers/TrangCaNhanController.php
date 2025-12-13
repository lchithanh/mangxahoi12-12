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
        $user = NguoiDung::find(session('ma_nguoi_dung'));

        if (!$user) {
            return redirect()->route('login');
        }

        return view('trangcanhan.setup', compact('user'));
    }

    /**
     * =====================================================
     * LƯU CẬP NHẬT PROFILE
     * - Không dùng storage
     * - Upload avatar vào public/uploads/anh_nguoi_dung
     * =====================================================
     */
    public function saveSetup(Request $request)
{
    $user = NguoiDung::find(session('ma_nguoi_dung'));

    if (!$user) {
        return redirect()->route('login');
    }

    // ✅ Validate dữ liệu
    $request->validate([
        'ho_ten' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:nguoi_dung,email,' . $user->ma_nguoi_dung . ',ma_nguoi_dung',
        'gioi_thieu' => 'nullable|string|max:1000',
        'anh_dai_dien' => 'nullable|image|max:2048',
    ]);

    // Cập nhật thông tin cơ bản
    $user->ho_ten = $request->ho_ten;
    $user->email = $request->email;
    $user->gioi_thieu = $request->gioi_thieu;

    // ================================
    // UPLOAD AVATAR NGƯỜI DÙNG
    // ================================
    if ($request->hasFile('anh_dai_dien')) {

        // 🗑️ Xóa avatar cũ nếu tồn tại
        if ($user->anh_dai_dien && File::exists(public_path($user->anh_dai_dien))) {
            File::delete(public_path($user->anh_dai_dien));
        }

        // 📁 Thư mục upload avatar người dùng
        $uploadPath = public_path('uploads/anh_nguoi_dung');

        // Tạo thư mục nếu chưa có
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // 📸 Tạo tên file an toàn
        $file = $request->file('anh_dai_dien');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // 📥 Lưu file vào public/uploads/anh_nguoi_dung
        $file->move($uploadPath, $fileName);

        // 💾 Lưu đường dẫn tương đối vào DB
        $user->anh_dai_dien = 'uploads/anh_nguoi_dung/' . $fileName;
    }

    // Lưu dữ liệu
    $user->save();

    return redirect()
        ->route('trangcanhan.index')
        ->with('success', '✅ Cập nhật thông tin cá nhân thành công');
}



    /**
     * =====================================================
     * HIỂN THỊ TRANG CÁ NHÂN
     * - Phân biệt chủ quán / người dùng thường
     * =====================================================
     */
    public function showProfile()
    {
        $user = NguoiDung::withCount('baiviets')
            ->with(['baiviets.anhBaiViets'])
            ->find(session('ma_nguoi_dung'));

        if (!$user) {
            return redirect()->route('login');
        }

        // 👥 Followers / Following
        $user->followers_count = $user->followers()->count();
        $user->following_count = $user->following()->count();

        /**
         * ================================
         * TRANG CÁ NHÂN CHỦ QUÁN
         * ================================
         */
        if ($user->vai_tro === 'chu_quan') {

            $nhaHangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)
                ->with('baiViets.anhBaiViets')
                ->get();

            // Hiển thị danh sách nhà hàng nếu >= 2
            $showDanhSach = $nhaHangs->count() >= 2;

            return view('trangcanhan.owner', compact(
                'user',
                'nhaHangs',
                'showDanhSach'
            ));
        }

        /**
         * ================================
         * TRANG CÁ NHÂN NGƯỜI DÙNG THƯỜNG
         * ================================
         */
        $danhGias = $user->danhGias()
            ->with('baiViet.nhaHang')
            ->get();

        return view('trangcanhan.user', compact('user', 'danhGias'));
    }
}
