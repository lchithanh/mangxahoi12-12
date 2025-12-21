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
            'ho_ten' => $request->ho_ten,
            'email'  => $request->email,
            'mo_ta'  => $request->mo_ta,
        ]);

        // Upload ảnh đại diện
       // Upload ảnh đại diện
if ($request->hasFile('anh_dai_dien')) {
    // Xóa ảnh cũ nếu có
    if (!empty($user->anh_dai_dien) && File::exists($_SERVER['DOCUMENT_ROOT'] . '/' . $user->anh_dai_dien)) {
        File::delete($_SERVER['DOCUMENT_ROOT'] . '/' . $user->anh_dai_dien);
    }

    // Lấy file upload
    $file = $request->file('anh_dai_dien');
    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $uploadPath = 'uploads/anh_nguoi_dung';

    // Tạo thư mục nếu chưa có
    if (!File::exists($_SERVER['DOCUMENT_ROOT'] . '/' . $uploadPath)) {
        File::makeDirectory($_SERVER['DOCUMENT_ROOT'] . '/' . $uploadPath, 0755, true);
    }

    // Di chuyển file
    $file->move($_SERVER['DOCUMENT_ROOT'] . '/' . $uploadPath, $fileName);

    // Cập nhật DB
    $user->update([
        'anh_dai_dien' => $uploadPath . '/' . $fileName
    ]);
}

return redirect()->route('trangcanhan.index', ['id' => $user->ma_nguoi_dung])
                 ->with('success', '✅ Cập nhật thông tin cá nhân thành công');   
                
    }

    /**
     * =====================================================
     * HIỂN THỊ TRANG CÁ NHÂN
     * =====================================================
     */
public function showProfile($maNguoiDung = null)
{
    if (!$maNguoiDung) {
        $maNguoiDung = Session::get('ma_nguoi_dung');
    }

    if (!$maNguoiDung) {
        return redirect()->route('login');
    }

    $user = NguoiDung::withCount([
        'baiviets',
        'followers',
        'followingUsers',
        'followingNhaHangs',
        'danhGias'
    ])
    ->with(['baiviets.anhBaiViets'])
    ->findOrFail($maNguoiDung);

    // Đánh giá của user (chung cho cả chủ quán và người thường)
    $danhGias = $user->danhGias()
        ->with('baiViet.nhaHang')
        ->orderByDesc('thoi_gian_tao')
        ->get();

    // Người đang theo dõi và đang theo dõi ai
    $followers = $user->followers()->get();
    $followingUsers = $user->followingUsers()->get();
    $followingNhaHangs = $user->followingNhaHangs()->get();

    $dangTheoDoiCount = $followingUsers->count() + $followingNhaHangs->count();

    if ($user->vai_tro === 'chu_quan') {
        // Chủ quán có thêm danh sách nhà hàng
        $nhaHangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)
            ->with('baiviets.anhBaiViets')
            ->get();

        $showDanhSach = $nhaHangs->count() >= 2;

        return view('trangcanhan.owner', compact(
            'user',
            'nhaHangs',
            'showDanhSach',
            'danhGias',
            'followers',
            'followingUsers',
            'followingNhaHangs',
            'dangTheoDoiCount'
        ));
    }

    // Người dùng bình thường
    return view('trangcanhan.user', compact(
        'user',
        'danhGias',
        'followers',
        'followingUsers',
        'followingNhaHangs'
    ));
}

    /**
     * =====================================================
     * XEM DANH SÁCH ĐANG THEO DÕI
     * =====================================================
     */
    public function showFollowing($maNguoiDung)
    {
        $user = NguoiDung::findOrFail($maNguoiDung);

        $followingUsers = $user->followingUsers()->get();
        $followingNhaHangs = $user->followingNhaHangs()->get();

        return view('trangcanhan.dangtheodoi', compact('user', 'followingUsers', 'followingNhaHangs'));
    }

    /**
     * =====================================================
     * XEM DANH SÁCH NGƯỜI THEO DÕI TÔI
     * =====================================================
     */
    public function showFollowers($maNguoiDung)
    {
        $user = NguoiDung::findOrFail($maNguoiDung);

        $followers = $user->followers()->get();

        return view('trangcanhan.theodoi', compact('user', 'followers'));
    }
}
