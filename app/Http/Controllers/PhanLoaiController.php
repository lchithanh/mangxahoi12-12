<?php

namespace App\Http\Controllers;

use App\Models\PhanLoai;
use Illuminate\Http\Request;

class PhanLoaiController extends Controller
{
    /**
     * Hiển thị danh sách phân loại
     */
    public function index()
    {
        $phanLoais = PhanLoai::orderBy('thoi_gian_tao', 'desc')->get();

        return view('phanloai.index', compact('phanLoais'));
    }

    /**
     * Hiển thị form tạo phân loại
     */
    public function create()
    {
        return view('phanloai.create');
    }

    /**
     * Lưu phân loại mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_phan_loai' => 'required|string|max:100|unique:phan_loai,ten_phan_loai',
            'mo_ta' => 'nullable|string'
        ]);

        PhanLoai::create([
            'ten_phan_loai' => $request->ten_phan_loai,
            'mo_ta' => $request->mo_ta
        ]);

        return redirect()
            ->route('phanloai.index')
            ->with('success', 'Thêm phân loại thành công');
    }

    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id)
    {
        $phanLoai = PhanLoai::findOrFail($id);

        return view('phanloai.edit', compact('phanLoai'));
    }

    /**
     * Cập nhật phân loại
     */
    public function update(Request $request, $id)
    {
        $phanLoai = PhanLoai::findOrFail($id);

        $request->validate([
            'ten_phan_loai' => 'required|string|max:100|unique:phan_loai,ten_phan_loai,' . $phanLoai->ma_phan_loai . ',ma_phan_loai',
            'mo_ta' => 'nullable|string'
        ]);

        $phanLoai->update([
            'ten_phan_loai' => $request->ten_phan_loai,
            'mo_ta' => $request->mo_ta
        ]);

        return redirect()
            ->route('phanloai.index')
            ->with('success', 'Cập nhật phân loại thành công');
    }

    /**
     * Xóa phân loại
     */
    public function destroy($id)
    {
        $phanLoai = PhanLoai::findOrFail($id);

        // Nếu có nhà hàng đang dùng phân loại → chặn xóa
        if ($phanLoai->nhaHangs()->count() > 0) {
            return back()->with('error', 'Không thể xóa vì đang có nhà hàng sử dụng phân loại này');
        }

        $phanLoai->delete();

        return redirect()
            ->route('phanloai.index')
            ->with('success', 'Xóa phân loại thành công');
    }
}
