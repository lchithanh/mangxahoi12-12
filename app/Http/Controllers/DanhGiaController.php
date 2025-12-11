<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhGia;
use App\Models\BaiViet;
use App\Models\NhaHang;

class DanhGiaController extends Controller
{
    /**
     * Danh sách đánh giá của 1 bài viết
     */
    public function index($ma_bai_viet)
    {
        $baiViet = BaiViet::with('nguoiDang', 'anhBaiViets')->findOrFail($ma_bai_viet);

        $danhGias = DanhGia::with('nguoiDung')
            ->where('ma_nha_hang', $baiViet->ma_nha_hang)
            ->orderBy('thoi_gian_tao', 'desc')
            ->get();

        return view('danhgia.index', compact('baiViet', 'danhGias'));
    }

    /**
     * Form tạo đánh giá
     */
    public function create($ma_bai_viet)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('baiviet.index')
                ->with('error', 'Cần đăng nhập để viết đánh giá.');
        }

        $baiViet = BaiViet::findOrFail($ma_bai_viet);
        $nhaHang = $baiViet->nhaHang;

        return view('danhgia.create', [
            'maBaiViet' => $ma_bai_viet,
            'baiViet'   => $baiViet,
            'nhaHang'   => $nhaHang,
            'user'      => $user,
        ]);
    }

    /**
     * Lưu đánh giá
     */
    public function store(Request $request, $ma_bai_viet)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('baiviet.index')->with('error', 'Cần đăng nhập để viết đánh giá.');
        }

        $request->validate([
            'diem_danh_gia'   => 'required|integer|min:1|max:5',
            'binh_luan'       => 'required|string',
            'duong_dan_anh.*' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        $baiViet = BaiViet::findOrFail($ma_bai_viet);

        // Tạo đánh giá
        $danhGia = DanhGia::create([
            'ma_bai_viet'    => $ma_bai_viet,
            'ma_nguoi_dung'  => $user->ma_nguoi_dung,
            'ma_nha_hang'    => $baiViet->ma_nha_hang,
            'diem_danh_gia'  => $request->diem_danh_gia,
            'binh_luan'      => $request->binh_luan,
            'thoi_gian_tao'  => now(),
        ]);

        /**
         * Lưu ảnh nếu có —> Lưu nhiều ảnh thành chuỗi
         * uploads/danhgia/xxxx.jpg,uploads/danhgia/yyy.jpg,...
         */
        if ($request->hasFile('duong_dan_anh')) {
            $paths = [];

            foreach ($request->file('duong_dan_anh') as $file) {
                $paths[] = $file->store('uploads/danhgia', 'public');
            }

            $danhGia->duong_dan_anh = implode(',', $paths);
            $danhGia->save();
        }

        return redirect()->route('danhgia.index', $ma_bai_viet)
            ->with('success', 'Đánh giá đã được gửi!');
    }

    /**
     * Danh sách đánh giá cho nhà hàng
     */
    public function indexNhaHang($ma_nha_hang)
    {
        $danhGias = DanhGia::with('nguoiDung')
            ->where('ma_nha_hang', $ma_nha_hang)
            ->orderBy('thoi_gian_tao', 'desc')
            ->get();

        $nhaHang = NhaHang::findOrFail($ma_nha_hang);

        return view('danhgia.index-nhahang', compact('danhGias', 'nhaHang'));
    }

    /**
     * Tăng lượt thích
     */
    
}
