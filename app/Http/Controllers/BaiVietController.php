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
     * DANH SÁCH BÀI VIẾT
     */
    public function index()
    {
        $userId   = session('ma_nguoi_dung');
        $userRole = session('user_role');

        if ($userId && $userRole === 'chu_quan') {
            // Chủ quán chỉ thấy bài viết của nhà hàng mình
            $maNhaHangs = NhaHang::where('ma_chu_so_huu', $userId)->pluck('ma_nha_hang');

            $baiviets = $maNhaHangs->isEmpty()
                ? collect()
                : BaiViet::with(['nguoiDang','anhBaiViets'])
                    ->whereIn('ma_nha_hang', $maNhaHangs)
                    ->orderByDesc('thoi_gian_tao')
                    ->orderByDesc('ma_bai_viet')
                    ->get();
        } else {
            // Người dùng thường thấy tất cả
            $baiviets = BaiViet::with(['nguoiDang','anhBaiViets'])
                ->orderByDesc('thoi_gian_tao')
                ->orderByDesc('ma_bai_viet')
                ->get();
        }

        return view('baiviet.index', compact('baiviets'));
    }

    /**
     * CHI TIẾT BÀI VIẾT
     */
    public function show($id)
    {
        $baiViet = BaiViet::with(['nguoiDang','nhaHang','anhBaiViets'])->findOrFail($id);
        return view('baiviet.show', compact('baiViet'));
    }

    /**
     * FORM TẠO BÀI VIẾT
     */
    public function create()
    {
        $userId   = session('ma_nguoi_dung');
        $userRole = session('user_role');

        if (!$userId || $userRole !== 'chu_quan') {
            return redirect()->route('baiviet.index')->with('error','⚠️ Chỉ chủ quán mới được tạo bài viết.');
        }

        $nhahangs = NhaHang::where('ma_chu_so_huu',$userId)->get();
        if ($nhahangs->isEmpty()) {
            return redirect()->route('nhahang.create')->with('error','⚠️ Vui lòng tạo nhà hàng trước.');
        }

        return view('baiviet.create', compact('nhahangs'));
    }

    /**
     * LƯU BÀI VIẾT
     */
    public function store(Request $request)
    {
        $userId = session('ma_nguoi_dung');
        if (!$userId) return redirect()->route('login');

        $request->validate([
            'ma_nha_hang'   => 'required|integer',
            'noi_dung'      => 'required|string',
            'anh_bai_viet.*'=> 'nullable|image|max:10240',
        ]);

        $baiViet = BaiViet::create([
            'ma_nha_hang'   => $request->ma_nha_hang,
            'ma_nguoi_dang' => $userId,
            'noi_dung'      => $request->noi_dung,
            'thoi_gian_tao' => now(),
        ]);

        // Upload ảnh
        if ($request->hasFile('anh_bai_viet')) {
            $uploadPath = $_SERVER['DOCUMENT_ROOT'].'/uploads/anh_bai_viet';
            if (!File::exists($uploadPath)) File::makeDirectory($uploadPath,0755,true);

            foreach ($request->file('anh_bai_viet') as $file) {
                $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move($uploadPath,$fileName);

                AnhBaiViet::create([
                    'ma_bai_viet'   => $baiViet->ma_bai_viet,
                    'duong_dan_anh' => 'uploads/anh_bai_viet/'.$fileName,
                ]);
            }
        }

        return redirect()->route('home')->with('success','Tạo bài viết thành công!');
    }

    /**
     * FORM CHỈNH SỬA
     */
    public function edit($id)
    {
        $baiViet = BaiViet::findOrFail($id);
        $userId   = session('ma_nguoi_dung');
        $userRole = session('user_role');

        if (!$userId || $userRole !== 'chu_quan' || $baiViet->ma_nguoi_dang != $userId) {
            return redirect()->route('baiviet.index')->with('error','⚠️ Không có quyền.');
        }

        $nhahangs = NhaHang::where('ma_chu_so_huu',$userId)->get();
        return view('baiviet.edit', compact('baiViet','nhahangs'));
    }

    /**
     * CẬP NHẬT
     */
    public function update(Request $request,$id)
    {
        $baiViet = BaiViet::with('anhBaiViets')->findOrFail($id);
        $userId   = session('ma_nguoi_dung');
        $userRole = session('user_role');

        if (!$userId || $userRole !== 'chu_quan' || $baiViet->ma_nguoi_dang != $userId) {
            return redirect()->route('baiviet.index')->with('error','⚠️ Không có quyền.');
        }

        $request->validate([
            'ma_nha_hang'   => 'required|integer',
            'noi_dung'      => 'required|string',
            'anh_bai_viet.*'=> 'nullable|image|max:10240',
        ]);

        // Xóa ảnh cũ
        if ($request->has('xoa_anh')) {
            foreach ($request->xoa_anh as $anhId) {
                $anh = AnhBaiViet::find($anhId);
                if ($anh) {
                    $path = $_SERVER['DOCUMENT_ROOT'].'/'.$anh->duong_dan_anh;
                    if (File::exists($path)) File::delete($path);
                    $anh->delete();
                }
            }
        }

        $baiViet->update([
            'noi_dung'   => $request->noi_dung,
            'ma_nha_hang'=> $request->ma_nha_hang,
        ]);

        // Thêm ảnh mới
        if ($request->hasFile('anh_bai_viet')) {
            $uploadPath = $_SERVER['DOCUMENT_ROOT'].'/uploads/anh_bai_viet';
            if (!File::exists($uploadPath)) File::makeDirectory($uploadPath,0755,true);

            foreach ($request->file('anh_bai_viet') as $file) {
                $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move($uploadPath,$fileName);

                AnhBaiViet::create([
                    'ma_bai_viet'   => $baiViet->ma_bai_viet,
                    'duong_dan_anh' => 'uploads/anh_bai_viet/'.$fileName,
                ]);
            }
        }

        return redirect()->route('baiviet.index')->with('success','Cập nhật bài viết thành công!');
    }

    /**
     * XÓA BÀI VIẾT
     */
    public function destroy($id)
    {
        $baiViet = BaiViet::with('anhBaiViets')->findOrFail($id);
        $userId   = session('ma_nguoi_dung');
        $userRole = session('user_role');

        if (!$userId || $userRole !== 'chu_quan' || $baiViet->ma_nguoi_dang != $userId) {
            return redirect()->route('baiviet.index')->with('error','⚠️ Không có quyền.');
        }

        foreach ($baiViet->anhBaiViets as $anh) {
            $path = $_SERVER['DOCUMENT_ROOT'].'/'.$anh->duong_dan_anh;
            if (File::exists($path)) File::delete($path);
            $anh->delete();
        }

        $baiViet->delete();
        return redirect()->route('baiviet.index')->with('success','Đã xóa bài viết.');
    }
}