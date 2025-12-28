<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\PhongChat;
use App\Models\NhaHang;
use App\Models\NguoiDung;
use App\Models\KhuVuc;
use App\Models\PhanLoai;

class NhaHangController extends Controller
{
    /**
     * Danh sách nhà hàng
     */
    public function index()
    {
        $userId = Session::get('ma_nguoi_dung');
        $user   = $userId ? NguoiDung::find($userId) : null;

        $nhaHangs = NhaHang::with(['chuSoHuu', 'khuVuc', 'phanLoai'])
            ->orderByDesc('ma_nha_hang')
            ->get();

        return view('nhahang.index', compact('nhaHangs', 'user'));
    }

    /**
     * Chi tiết nhà hàng
     */
    public function show($id)
    {
        $userId = Session::get('ma_nguoi_dung');
        $user   = $userId ? NguoiDung::find($userId) : null;

        $nhaHang = NhaHang::with([
            'chuSoHuu',
            'khuVuc',
            'phanLoai',
            'baiViets',
            'danhGias',
            'tinNhans'
        ])->findOrFail($id);

        $phongChat = null;
        if ($user) {
            $phongChat = PhongChat::where('loai_phong', 'user_nhahang')
                ->where('ma_nha_hang', $nhaHang->ma_nha_hang)
                ->where(function ($q) use ($user) {
                    $q->where('ma_nguoi_dung_1', $user->ma_nguoi_dung)
                      ->orWhere('ma_nguoi_dung_2', $user->ma_nguoi_dung);
                })
                ->first();
        }

        return view('nhahang.show', compact('nhaHang', 'user', 'phongChat'));
    }

    /**
     * Form tạo nhà hàng
     */
    public function create()
    {
        if (Session::get('user_role') !== 'chu_quan') {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Chỉ chủ quán mới được tạo nhà hàng.');
        }

        return view('nhahang.create', [
            'user'      => NguoiDung::find(Session::get('ma_nguoi_dung')),
            'khuVucs'   => KhuVuc::all(),
            'phanLoais' => PhanLoai::all()
        ]);
    }

    /**
     * Lưu nhà hàng mới
     */
    public function store(Request $request)
    {
        if (Session::get('user_role') !== 'chu_quan') {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Chỉ chủ quán mới được tạo nhà hàng.');
        }

        $userId = Session::get('ma_nguoi_dung');

        $request->validate([
            'ten_nha_hang' => 'required|string|max:255',
            'dia_chi'      => 'required|string|max:255',
            'anh_dai_dien' => 'nullable|image|max:2048',
        ]);

        // Khu vực
        $maKhuVuc = $request->ma_khu_vuc;
        if ($request->ma_khu_vuc === 'khac' && $request->ten_khu_vuc_moi) {
            $maKhuVuc = KhuVuc::create([
                'ten_khu_vuc' => $request->ten_khu_vuc_moi
            ])->ma_khu_vuc;
        }

        // Phân loại
        $phanLoai = null;
        if ($request->phan_loai === 'khac' && $request->phan_loai_moi) {
            $phanLoai = PhanLoai::firstOrCreate([
                'ten_phan_loai' => $request->phan_loai_moi
            ]);
        } elseif ($request->phan_loai) {
            $phanLoai = PhanLoai::firstOrCreate([
                'ten_phan_loai' => $request->phan_loai
            ]);
        }

        $data = [
            'ten_nha_hang'  => $request->ten_nha_hang,
            'dia_chi'       => $request->dia_chi,
            'ma_khu_vuc'    => $maKhuVuc,
            'ma_phan_loai'  => $phanLoai?->ma_phan_loai,
            'ma_chu_so_huu' => $userId,
            'mo_ta'         => $request->mo_ta,
            'so_dien_thoai' => $request->so_dien_thoai,
            'gio_mo_cua'    => $request->gio_mo_cua,
        ];

        /* Upload ảnh đại diện */
        if ($request->hasFile('anh_dai_dien')) {
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/anh_nha_hang';

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file     = $request->file('anh_dai_dien');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);

            $data['anh_dai_dien'] = 'uploads/anh_nha_hang/' . $fileName;
        }

        NhaHang::create($data);

        return redirect()->route('nhahang.index')
            ->with('success', 'Tạo nhà hàng thành công!');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $userId  = Session::get('ma_nguoi_dung');
        $nhaHang = NhaHang::findOrFail($id);

        if ($userId !== $nhaHang->ma_chu_so_huu) {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Không có quyền.');
        }

        return view('nhahang.edit', [
            'nhaHang'   => $nhaHang,
            'khuVucs'   => KhuVuc::all(),
            'phanLoais' => PhanLoai::all()
        ]);
    }

    /**
     * Cập nhật nhà hàng
     */
    public function update(Request $request, $id)
{
    $userId  = Session::get('ma_nguoi_dung');
    $nhaHang = NhaHang::findOrFail($id);

    if ($userId !== $nhaHang->ma_chu_so_huu) {
        return redirect()->route('nhahang.index')
            ->with('error', '⚠️ Không có quyền.');
    }

    // 1. Xử lý Khu vực (Giống logic bên store)
    $maKhuVuc = $request->ma_khu_vuc;
    if ($request->ma_khu_vuc === 'khac' && $request->ten_khu_vuc_moi) {
        $khuVucMoi = KhuVuc::create(['ten_khu_vuc' => $request->ten_khu_vuc_moi]);
        $maKhuVuc = $khuVucMoi->ma_khu_vuc;
    }

    // 2. XỬ LÝ PHÂN LOẠI (Cập nhật logic này)
    $maPhanLoai = null;
    if ($request->phan_loai === 'khac' && $request->phan_loai_moi) {
        // Nếu chọn khác -> Tạo mới hoặc lấy loại đã có trùng tên
        $pl = PhanLoai::firstOrCreate(['ten_phan_loai' => $request->phan_loai_moi]);
        $maPhanLoai = $pl->ma_phan_loai;
    } elseif ($request->phan_loai) {
        // Nếu chọn loại có sẵn -> Tìm ID dựa trên tên đã chọn ở Select
        $pl = PhanLoai::where('ten_phan_loai', $request->phan_loai)->first();
        $maPhanLoai = $pl ? $pl->ma_phan_loai : null;
    }

    // 3. Chuẩn bị dữ liệu cập nhật
    $data = $request->only([
        'ten_nha_hang', 'dia_chi', 'mo_ta', 'so_dien_thoai', 'gio_mo_cua'
    ]);
    
    $data['ma_khu_vuc'] = $maKhuVuc;
    $data['ma_phan_loai'] = $maPhanLoai;

    // 4. Xử lý ảnh đại diện
    if ($request->hasFile('anh_dai_dien')) {
        if ($nhaHang->anh_dai_dien) {
            $old = $_SERVER['DOCUMENT_ROOT'] . '/' . $nhaHang->anh_dai_dien;
            if (File::exists($old)) File::delete($old);
        }

        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/anh_nha_hang';
        File::ensureDirectoryExists($uploadPath);

        $file = $request->file('anh_dai_dien');
        $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $file->move($uploadPath, $fileName);

        $data['anh_dai_dien'] = 'uploads/anh_nha_hang/'.$fileName;
    }

    $nhaHang->update($data);

    return redirect()->route('nhahang.show', $id)
        ->with('success', 'Cập nhật thành công!');
}

    /**
     * Xóa nhà hàng
     */
    public function destroy($id)
    {
        $userId  = Session::get('ma_nguoi_dung');
        $nhaHang = NhaHang::findOrFail($id);

        if ($userId !== $nhaHang->ma_chu_so_huu) {
            return redirect()->route('nhahang.index')
                ->with('error', '⚠️ Không có quyền.');
        }

        // Xóa ảnh nếu có
        if ($nhaHang->anh_dai_dien) {
            $old = $_SERVER['DOCUMENT_ROOT'] . '/' . $nhaHang->anh_dai_dien;
            if (File::exists($old)) File::delete($old);
        }

        $nhaHang->delete();

        return redirect()->route('nhahang.index')
            ->with('success', 'Xóa nhà hàng thành công!');
    }
}