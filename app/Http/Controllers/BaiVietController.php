<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\BaiViet;
use App\Models\NhaHang;
use App\Models\AnhBaiViet;

class BaiVietController extends Controller
{
    /**
     * =====================================================
     * HIỂN THỊ DANH SÁCH BÀI VIẾT
     * - Chủ quán: chỉ thấy bài của nhà hàng mình
     * - Người dùng thường: thấy tất cả bài viết
     * =====================================================
     */
    public function index()
    {
        $user = session('user');

        // Nếu là chủ quán
        if ($user && trim($user->vai_tro) === 'chu_quan') {

            // Lấy danh sách nhà hàng của chủ quán
            $nhahangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)
                ->pluck('ma_nha_hang');

            // Nếu chưa có nhà hàng thì không có bài viết
            $baiviets = $nhahangs->isEmpty()
                ? collect()
                : BaiViet::with(['nguoiDang', 'anhBaiViets'])
                    ->whereIn('ma_nha_hang', $nhahangs)
                    ->orderByDesc('thoi_gian_tao')
                    ->get();
        }
        // Người dùng thường
        else {
            $baiviets = BaiViet::with(['nguoiDang', 'anhBaiViets'])
                ->orderByDesc('thoi_gian_tao')
                ->get();
        }

        return view('baiviet.index', compact('baiviets', 'user'));
    }

    /**
     * =====================================================
     * XEM CHI TIẾT BÀI VIẾT
     * =====================================================
     */
    public function show($id)
    {
        $user = session('user');

        $baiViet = BaiViet::with(['nguoiDang', 'nhaHang', 'anhBaiViets'])
            ->findOrFail($id);

        return view('baiviet.show', compact('baiViet', 'user'));
    }

    /**
     * =====================================================
     * FORM TẠO BÀI VIẾT
     * - Chỉ chủ quán mới được phép
     * =====================================================
     */
    public function create()
    {
        $user = session('user');

        // Kiểm tra quyền
        if (!$user || trim($user->vai_tro) !== 'chu_quan') {
            return redirect()->route('baiviet.index')
                ->with('error', '⚠️ Chỉ chủ quán mới được tạo bài viết.');
        }

        // Lấy nhà hàng của chủ quán
        $nhahangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)->get();

        // Nếu chưa có nhà hàng → bắt tạo trước
        if ($nhahangs->isEmpty()) {
            return redirect()->route('nhahang.create')
                ->with('error', '⚠️ Vui lòng tạo nhà hàng trước.');
        }

        return view('baiviet.create', compact('nhahangs', 'user'));
    }

    /**
     * =====================================================
     * LƯU BÀI VIẾT MỚI
     * - Upload ảnh vào public/uploads/anh_bai_viet
     * - DB chỉ lưu đường dẫn tương đối
     * =====================================================
     */
    public function store(Request $request)
    {
        $user = session('user');

        // Validate dữ liệu
        $request->validate([
            'ma_nha_hang' => 'required|integer',
            'noi_dung' => 'required|string',
            'anh_bai_viet.*' => 'nullable|image|max:10240',
        ]);

        // Tạo bài viết
        $baiViet = BaiViet::create([
            'ma_nha_hang' => $request->ma_nha_hang,
            'ma_nguoi_dang' => $user->ma_nguoi_dung,
            'noi_dung' => $request->noi_dung,
            'thoi_gian_tao' => now(),
        ]);

        // Xử lý upload ảnh
        if ($request->hasFile('anh_bai_viet')) {

            $uploadPath = public_path('uploads/anh_bai_viet');

            // Tạo thư mục nếu chưa tồn tại
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            foreach ($request->file('anh_bai_viet') as $file) {

                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);

                AnhBaiViet::create([
                    'ma_bai_viet' => $baiViet->ma_bai_viet,
                    'duong_dan_anh' => 'uploads/anh_bai_viet/' . $fileName,
                ]);
            }
        }

        return redirect()->route('home')
            ->with('success', 'Tạo bài viết thành công!');
    }

    /**
     * =====================================================
     * FORM CHỈNH SỬA BÀI VIẾT
     * - Chỉ chủ bài viết mới được sửa
     * =====================================================
     */
    public function edit($id)
    {
        $user = session('user');
        $baiViet = BaiViet::findOrFail($id);

        // Kiểm tra quyền
        if (
            !$user ||
            $user->vai_tro !== 'chu_quan' ||
            $baiViet->ma_nguoi_dang != $user->ma_nguoi_dung
        ) {
            return redirect()->route('baiviet.index')
                ->with('error', '⚠️ Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $nhahangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)->get();

        return view('baiviet.edit', compact('baiViet', 'nhahangs', 'user'));
    }

    /**
     * =====================================================
     * CẬP NHẬT BÀI VIẾT
     * - Có thể xóa ảnh cũ
     * - Có thể thêm ảnh mới
     * =====================================================
     */
    public function update(Request $request, $id)
    {
        $user = session('user');
        $baiViet = BaiViet::findOrFail($id);

        // Kiểm tra quyền
        if (
            !$user ||
            $user->vai_tro !== 'chu_quan' ||
            $baiViet->ma_nguoi_dang != $user->ma_nguoi_dung
        ) {
            return redirect()->route('baiviet.index')
                ->with('error', '⚠️ Không có quyền.');
        }

        // XÓA ẢNH ĐƯỢC CHỌN
        if ($request->has('xoa_anh')) {
            foreach ($request->xoa_anh as $anhId) {
                $anh = AnhBaiViet::find($anhId);

                if ($anh && File::exists(public_path($anh->duong_dan_anh))) {
                    File::delete(public_path($anh->duong_dan_anh));
                    $anh->delete();
                }
            }
        }

        // CẬP NHẬT BÀI VIẾT
        $baiViet->update([
            'noi_dung' => $request->noi_dung,
            'ma_nha_hang' => $request->ma_nha_hang,
        ]);

        // THÊM ẢNH MỚI
        if ($request->hasFile('anh_bai_viet')) {

            $uploadPath = public_path('uploads/anh_bai_viet');

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            foreach ($request->file('anh_bai_viet') as $file) {

                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);

                AnhBaiViet::create([
                    'ma_bai_viet' => $baiViet->ma_bai_viet,
                    'duong_dan_anh' => 'uploads/anh_bai_viet/' . $fileName,
                ]);
            }
        }

        return redirect()->route('baiviet.index')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     * =====================================================
     * XÓA BÀI VIẾT
     * - Xóa cả ảnh vật lý + DB
     * =====================================================
     */
    public function destroy($id)
    {
        $user = session('user');
        $baiViet = BaiViet::findOrFail($id);

        // Kiểm tra quyền
        if (
            !$user ||
            $user->vai_tro !== 'chu_quan' ||
            $baiViet->ma_nguoi_dang != $user->ma_nguoi_dung
        ) {
            return redirect()->route('baiviet.index')
                ->with('error', '⚠️ Không có quyền xóa.');
        }

        // Xóa ảnh vật lý
        foreach ($baiViet->anhBaiViets as $anh) {
            if (File::exists(public_path($anh->duong_dan_anh))) {
                File::delete(public_path($anh->duong_dan_anh));
            }
        }

        // Xóa bài viết
        $baiViet->delete();

        return redirect()->route('baiviet.index')
            ->with('success', 'Đã xóa bài viết.');
    }
}
