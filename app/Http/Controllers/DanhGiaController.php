<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\DanhGia;
use App\Models\BaiViet;
use App\Models\AnhDanhGia;
use App\Models\ThongBao;

class DanhGiaController extends Controller
{
    /**
     * =====================================================
     * DANH SÁCH ĐÁNH GIÁ CỦA BÀI VIẾT
     * =====================================================
     */
    public function index($ma_bai_viet, Request $request)
    {
        $baiViet = BaiViet::with(['nguoiDang', 'anhBaiViets'])
            ->findOrFail($ma_bai_viet);

        $query = $baiViet->danhGias()
            ->with(['nguoiDung', 'anhDanhGias']);

        switch ($request->get('sort', 'latest')) {
            case '5to1':
                $query->orderByDesc('diem_danh_gia');
                break;
            case '1to5':
                $query->orderBy('diem_danh_gia');
                break;
            default:
                $query->orderByDesc('thoi_gian_tao');
        }

        return view('danhgia.index', [
            'baiViet'  => $baiViet,
            'danhGias' => $query->get(),
        ]);
    }

    /**
     * =====================================================
     * LƯU ĐÁNH GIÁ
     * =====================================================
     */
    public function store(Request $request, $ma_bai_viet)
    {
        $userId = session('ma_nguoi_dung');

        if (!$userId) {
            return redirect()->route('baiviet.index')
                ->with('error', 'Cần đăng nhập để viết đánh giá.');
        }

        $request->validate([
            'diem_danh_gia'  => 'required|integer|min:1|max:5',
            'binh_luan'      => 'required|string',
            'anh_danh_gia.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $baiViet = BaiViet::findOrFail($ma_bai_viet);

        $danhGia = DanhGia::create([
            'ma_nguoi_dung' => $userId,
            'ma_nha_hang'   => $baiViet->ma_nha_hang,
            'ma_bai_viet'   => $baiViet->ma_bai_viet,
            'diem_danh_gia' => $request->diem_danh_gia,
            'binh_luan'     => $request->binh_luan,
            'thoi_gian_tao' => now(),
        ]);

        // Upload ảnh đánh giá (DOCUMENT_ROOT)
        if ($request->hasFile('anh_danh_gia')) {

            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/anh_danh_gia';

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            foreach ($request->file('anh_danh_gia') as $file) {
                $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);

                AnhDanhGia::create([
                    'ma_danh_gia'   => $danhGia->ma_danh_gia,
                    'duong_dan_anh' => 'uploads/anh_danh_gia/'.$fileName,
                    'thoi_gian_tao' => now(),
                ]);
            }
        }

        // Thông báo cho chủ bài viết
        ThongBao::create([
            'ma_nguoi_nhan' => $baiViet->ma_nguoi_dang,
            'ma_nguoi_gui'  => $userId,
            'loai_thong_bao'=> 'danh_gia',
            'ma_doi_tuong'  => $baiViet->ma_bai_viet,
            'noi_dung'      => 'Có người đã đánh giá bài viết của bạn',
            'da_doc'        => 0,
            'thoi_gian_tao' => now(),
        ]);

        return redirect()->route('danhgia.index', $ma_bai_viet)
            ->with('success', 'Đánh giá đã được gửi!');
    }

    /**
     * =====================================================
     * FORM SỬA ĐÁNH GIÁ
     * =====================================================
     */
    public function edit($id)
    {
        $userId  = session('ma_nguoi_dung');
        $danhGia = DanhGia::with('anhDanhGias')->findOrFail($id);

        if (!$userId || $danhGia->ma_nguoi_dung != $userId) {
            return redirect()->back()
                ->with('error', 'Bạn không có quyền sửa đánh giá này.');
        }

        return view('danhgia.edit', compact('danhGia'));
    }

    /**
     * =====================================================
     * CẬP NHẬT ĐÁNH GIÁ
     * =====================================================
     */
    public function update(Request $request, $id)
    {
        $userId  = session('ma_nguoi_dung');
        $danhGia = DanhGia::with('anhDanhGias')->findOrFail($id);

        if (!$userId || $danhGia->ma_nguoi_dung != $userId) {
            return redirect()->back()
                ->with('error', 'Bạn không có quyền cập nhật đánh giá này.');
        }

        $request->validate([
            'diem_danh_gia'  => 'required|integer|min:1|max:5',
            'binh_luan'      => 'required|string',
            'anh_danh_gia.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $danhGia->update([
            'diem_danh_gia' => $request->diem_danh_gia,
            'binh_luan'     => $request->binh_luan,
        ]);

        // Xóa ảnh được chọn
        if ($request->has('xoa_anh')) {
            foreach ($request->xoa_anh as $anhId) {
                $anh = AnhDanhGia::find($anhId);
                if ($anh && File::exists($_SERVER['DOCUMENT_ROOT'].'/'.$anh->duong_dan_anh)) {
                    File::delete($_SERVER['DOCUMENT_ROOT'].'/'.$anh->duong_dan_anh);
                    $anh->delete();
                }
            }
        }

        // Thêm ảnh mới
        if ($request->hasFile('anh_danh_gia')) {

            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/anh_danh_gia';

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            foreach ($request->file('anh_danh_gia') as $file) {
                $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);

                AnhDanhGia::create([
                    'ma_danh_gia'   => $danhGia->ma_danh_gia,
                    'duong_dan_anh' => 'uploads/anh_danh_gia/'.$fileName,
                    'thoi_gian_tao' => now(),
                ]);
            }
        }

        return redirect()->route('danhgia.index', $danhGia->ma_bai_viet)
            ->with('success', 'Đánh giá đã được cập nhật!');
    }

    /**
     * =====================================================
     * XÓA ĐÁNH GIÁ
     * =====================================================
     */
    public function destroy($id)
    {
        $userId  = session('ma_nguoi_dung');
        $danhGia = DanhGia::with('anhDanhGias')->findOrFail($id);

        if (!$userId || $danhGia->ma_nguoi_dung != $userId) {
            return redirect()->back()
                ->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }

        foreach ($danhGia->anhDanhGias as $anh) {
            $path = $_SERVER['DOCUMENT_ROOT'].'/'.$anh->duong_dan_anh;
            if (File::exists($path)) {
                File::delete($path);
            }
            $anh->delete();
        }

        $danhGia->delete();

        return redirect()->route('danhgia.index', $danhGia->ma_bai_viet)
            ->with('success', 'Đánh giá đã được xóa!');
    }
}