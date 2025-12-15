<?php

namespace App\Http\Controllers;

use File;
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
            ->orderByDesc('ma_nha_hang') // nhà hàng mới nhất lên đầu
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

    // Chỉ chủ quán mới được tạo nhà hàng
    if (!$user || $user->vai_tro !== 'chu_quan') {
        return redirect()->route('nhahang.index')
            ->with('error', '⚠️ Chỉ chủ quán mới có thể tạo nhà hàng.');
    }

    // Lấy danh sách khu vực và phân loại hiện có
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
    if (!$user || $user->vai_tro !== 'chu_quan') {
        return redirect()->route('nhahang.index')
            ->with('error', '⚠️ Chỉ chủ quán mới có thể tạo nhà hàng.');
    }

    $request->validate([
        'ten_nha_hang' => 'required|string|max:255',
        'dia_chi'      => 'required|string|max:255',
        'ma_khu_vuc'   => 'nullable',
        'ten_khu_vuc_moi' => 'nullable|string|max:255',
        'phan_loai'    => 'nullable|string|max:255',
        'phan_loai_moi'=> 'nullable|string|max:255',
        'anh_dai_dien' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        'mo_ta'        => 'nullable|string|max:500',
    ]);

    // Xử lý khu vực
    if ($request->ma_khu_vuc === 'khac' && $request->ten_khu_vuc_moi) {
        $khuVuc = KhuVuc::create(['ten_khu_vuc' => $request->ten_khu_vuc_moi]);
        $maKhuVuc = $khuVuc->ma_khu_vuc;
    } else {
        $maKhuVuc = $request->ma_khu_vuc;
    }

    // Xử lý phân loại
    if ($request->phan_loai === 'khac' && $request->phan_loai_moi) {
        $phanLoai = PhanLoai::firstOrCreate(['ten_phan_loai' => $request->phan_loai_moi]);
    } else {
        $phanLoai = PhanLoai::firstOrCreate(['ten_phan_loai' => $request->phan_loai]);
    }

    $data = [
        'ten_nha_hang' => $request->ten_nha_hang,
        'dia_chi' => $request->dia_chi,
        'ma_khu_vuc' => $maKhuVuc,
        'ma_phan_loai' => $phanLoai->ma_phan_loai,
        'ma_chu_so_huu' => $user->ma_nguoi_dung,
        'mo_ta' => $request->mo_ta,
        'so_dien_thoai' => $request->so_dien_thoai,   // thêm dòng này
    'gio_mo_cua' => $request->gio_mo_cua,  
    ];

    if ($request->hasFile('anh_dai_dien')) {

    // Xóa ảnh cũ nếu có
    if (isset($nhaHang) && $nhaHang->anh_dai_dien && File::exists(public_path($nhaHang->anh_dai_dien))) {
        File::delete(public_path($nhaHang->anh_dai_dien));
    }

    $uploadPath = public_path('uploads/anh_nha_hang');
    if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
    }

    $file = $request->file('anh_dai_dien');
    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move($uploadPath, $fileName);

    $data['anh_dai_dien'] = 'uploads/anh_nha_hang/' . $fileName;
}


    NhaHang::create($data);

    return redirect()->route('nhahang.index')->with('success', 'Tạo nhà hàng thành công!');
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
        'dia_chi'      => 'required|string|max:255',
        'ma_khu_vuc'   => 'nullable',
        'ten_khu_vuc_moi' => 'nullable|string|max:255',
        'phan_loai'    => 'nullable|string|max:255',
        'phan_loai_moi'=> 'nullable|string|max:255',
        'anh_dai_dien' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        'mo_ta'        => 'nullable|string|max:500',
    ]);

    // Xử lý khu vực
    if ($request->ma_khu_vuc === 'khac' && $request->ten_khu_vuc_moi) {
        $khuVuc = KhuVuc::create(['ten_khu_vuc' => $request->ten_khu_vuc_moi]);
        $maKhuVuc = $khuVuc->ma_khu_vuc;
    } else {
        $maKhuVuc = $request->ma_khu_vuc;
    }

    // Xử lý phân loại
    if ($request->phan_loai === 'khac' && $request->phan_loai_moi) {
        $phanLoai = PhanLoai::firstOrCreate(['ten_phan_loai' => $request->phan_loai_moi]);
    } else {
        $phanLoai = PhanLoai::firstOrCreate(['ten_phan_loai' => $request->phan_loai]);
    }

    // Chuẩn bị dữ liệu update
    $data = [
        'ten_nha_hang' => $request->ten_nha_hang,
        'dia_chi' => $request->dia_chi,
        'ma_khu_vuc' => $maKhuVuc,
        'ma_phan_loai' => $phanLoai->ma_phan_loai,
        'mo_ta' => $request->mo_ta,
        'so_dien_thoai' => $request->so_dien_thoai,   // thêm dòng này
    'gio_mo_cua' => $request->gio_mo_cua,  
    ];

    // Cập nhật ảnh đại diện nếu có
    if ($request->hasFile('anh_dai_dien')) {

    // Xóa ảnh cũ nếu có
    if (isset($nhaHang) && $nhaHang->anh_dai_dien && File::exists(public_path($nhaHang->anh_dai_dien))) {
        File::delete(public_path($nhaHang->anh_dai_dien));
    }

    $uploadPath = public_path('uploads/anh_nha_hang');
    if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
    }

    $file = $request->file('anh_dai_dien');
    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move($uploadPath, $fileName);

    $data['anh_dai_dien'] = 'uploads/anh_nha_hang/' . $fileName;
}


    $nhaHang->update($data);

    return redirect()->route('nhahang.show', $nhaHang->ma_nha_hang)
        ->with('success', 'Cập nhật nhà hàng thành công!');
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
