<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhuVuc;

class KhuVucController extends Controller
{
    /**
     * Hiển thị danh sách khu vực
     */
    public function index()
    {
        $khuVucs = KhuVuc::orderBy('ten_khu_vuc')->get();
        return view('khuvuc.index', compact('khuVucs'));
    }

    /**
     * Form tạo khu vực mới
     */
    public function create()
    {
        return view('khuvuc.create');
    }

    /**
     * Lưu khu vực mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_khu_vuc' => 'required|string|max:255',
        ]);

        KhuVuc::create([
            'ten_khu_vuc' => $request->ten_khu_vuc,
        ]);

        return redirect()->route('khuvuc.index')
            ->with('success', 'Thêm khu vực thành công!');
    }

    /**
     * Form chỉnh sửa khu vực
     */
    public function edit($id)
    {
        $khuVuc = KhuVuc::findOrFail($id);
        return view('khuvuc.edit', compact('khuVuc'));
    }

    /**
     * Cập nhật khu vực
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_khu_vuc' => 'required|string|max:255',
        ]);

        $khuVuc = KhuVuc::findOrFail($id);
        $khuVuc->update([
            'ten_khu_vuc' => $request->ten_khu_vuc,
        ]);

        return redirect()->route('khuvuc.index')
            ->with('success', 'Cập nhật khu vực thành công!');
    }

    /**
     * Xóa khu vực
     */
    public function destroy($id)
    {
        $khuVuc = KhuVuc::findOrFail($id);
        $khuVuc->delete();

        return redirect()->route('khuvuc.index')
            ->with('success', 'Xóa khu vực thành công!');
    }
}
