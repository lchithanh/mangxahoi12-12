<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhaHang;
use App\Models\NguoiDung;
use App\Models\KhuVuc;
use App\Models\PhanLoai;

class NhaHangController extends Controller
{
    /**
     * Hiển thị danh sách nhà hàng
     */
    public function index()
    {
        // Lấy danh sách nhà hàng kèm quan hệ (chủ sở hữu, khu vực, phân loại)
        $nhaHangs = NhaHang::with(['chuSoHuu', 'khuVuc', 'phanLoai'])
            ->orderBy('ten_nha_hang')
            ->get();

        return view('nhahang.index', compact('nhaHangs'));
    }

    /**
     * Xem chi tiết một nhà hàng
     */
    public function show($id)
    {
        $nhaHang = NhaHang::with([
            'chuSoHuu',
            'khuVuc',
            'phanLoai',
            'baiViets',  // danh sách bài viết của nhà hàng
        ])->findOrFail($id);

        return view('nhahang.show', compact('nhaHang'));
    }

    /**
     * Form tạo nhà hàng
     */
    public function create()
    {
        $user = session('user');

        // Chỉ cho chủ quán tạo nhà hàng
        if (!$user || $user->vai_tro !== 'chu_quan') {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Chỉ chủ quán mới có thể tạo nhà hàng.');
        }

        // Lấy lựa chọn khu vực và phân loại để hiển thị dropdown
        $khuVucs = KhuVuc::all();
        $phanLoais = PhanLoai::all();

        return view('nhahang.create', compact('user', 'khuVucs', 'phanLoais'));
    }

    /**
     * Lưu nhà hàng mới
     */
    public function store(Request $request)
    {
        $user = session('user');

        // Kiểm tra role
        if (!$user || $user->vai_tro !== 'chu_quan') {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Chỉ chủ quán mới có thể tạo nhà hàng.');
        }

        // Validate
        $request->validate([
            'ten_nha_hang' => 'required|string|max:255',
            'dia_chi'      => 'required|string',
            'ma_khu_vuc'   => 'nullable|integer',
            'phan_loai'    => 'nullable|string|max:255',
            'anh_dai_dien' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'mo_ta'        => 'nullable|string|max:500',
        ]);

        // Tạo hoặc lấy phân loại
        $phanLoaiObj = PhanLoai::firstOrCreate([
            'ten_phan_loai' => $request->phan_loai
        ]);

        // Chuẩn bị dữ liệu để lưu
        $data = $request->only(['ten_nha_hang', 'dia_chi', 'ma_khu_vuc', 'mo_ta']);
        $data['ma_chu_so_huu'] = $user->ma_nguoi_dung;
        $data['ma_phan_loai']  = $phanLoaiObj->ma_phan_loai;

        /**
         * LƯU ẢNH ĐẠI DIỆN
         * - File được lưu vào storage/app/public/uploads/nhahang
         * - Laravel sẽ tạo đường dẫn public qua /storage/... sau khi chạy:
         *
         *   php artisan storage:link
         */
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')
                ->store('uploads/nhahang', 'public');
        }

        // Lưu vào DB
        NhaHang::create($data);

        return redirect()->route('nhahang.index')
            ->with('success', 'Tạo nhà hàng thành công!');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $user = session('user');
        $nhaHang = NhaHang::findOrFail($id);

        // Chỉ chủ quán mới sửa được
        if (!$user || $user->ma_nguoi_dung !== $nhaHang->ma_chu_so_huu) {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Bạn không có quyền chỉnh sửa nhà hàng này.');
        }

        $khuVucs = KhuVuc::all();
        $phanLoais = PhanLoai::all();

        return view('nhahang.edit', compact('nhaHang', 'khuVucs', 'phanLoais'));
    }

    /**
     * Cập nhật nhà hàng
     */
    public function update(Request $request, $id)
    {
        $user = session('user');
        $nhaHang = NhaHang::findOrFail($id);

        // Kiểm tra quyền sửa
        if (!$user || $user->ma_nguoi_dung !== $nhaHang->ma_chu_so_huu) {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Bạn không có quyền cập nhật nhà hàng này.');
        }

        // Validate
        $request->validate([
            'ten_nha_hang' => 'required|string|max:255',
            'dia_chi'      => 'required|string',
            'ma_khu_vuc'   => 'nullable|integer',
            'phan_loai'    => 'nullable|string|max:255',
            'anh_dai_dien' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'mo_ta'        => 'nullable|string|max:500',
        ]);

        // Xử lý phân loại
        $phanLoaiObj = PhanLoai::firstOrCreate(['ten_phan_loai' => $request->phan_loai]);

        // Dữ liệu update
        $data = $request->only(['ten_nha_hang', 'dia_chi', 'ma_khu_vuc', 'mo_ta']);
        $data['ma_phan_loai'] = $phanLoaiObj->ma_phan_loai;

        /**
         * CẬP NHẬT ẢNH
         */
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')
                ->store('uploads/nhahang', 'public');
        }

        $nhaHang->update($data);

        return redirect()->route('nhahang.index')
            ->with('success', 'Cập nhật thành công!');
    }

    /**
     * Xóa nhà hàng
     */
    public function destroy($id)
    {
        $user = session('user');
        $nhaHang = NhaHang::findOrFail($id);

        // Kiểm tra quyền
        if (!$user || $user->ma_nguoi_dung !== $nhaHang->ma_chu_so_huu) {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Bạn không có quyền xóa nhà hàng này.');
        }

        $nhaHang->delete();

        return redirect()->route('nhahang.index')->with('success', 'Xóa thành công!');
    }
}
