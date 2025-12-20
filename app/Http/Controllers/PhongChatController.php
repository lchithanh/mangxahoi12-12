<?php

namespace App\Http\Controllers;

use App\Models\PhongChat;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PhongChatController extends Controller
{
    /**
     * =========================
     * DANH SÁCH PHÒNG CHAT
     * =========================
     */
    public function index()
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');
        if (!$maNguoiDung) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập');
        }

        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return redirect()->route('login')->with('error', 'Phiên đăng nhập không hợp lệ.');
        }

        $userVaiTro = Session::get('user_role') ?? 'user';
        $maNhaHang  = Session::get('ma_nha_hang');

        // Lấy tất cả phòng chat mà người này tham gia
        $phongChats = PhongChat::with(['nguoiDung1','nguoiDung2','nhaHang','tinNhans'])
            ->where(function($q) use ($user, $maNhaHang) {
                $q->where('ma_nguoi_dung_1', $user->ma_nguoi_dung)
                  ->orWhere('ma_nguoi_dung_2', $user->ma_nguoi_dung);

                if ($maNhaHang) {
                    $q->orWhere('ma_nha_hang', $maNhaHang);
                }
            })
            ->get();

        // Gán tên hiển thị cho từng phòng
        foreach ($phongChats as $phong) {
            if ($userVaiTro === 'nhahang') {
                $nguoiKhac = ($phong->ma_nguoi_dung_1 == $user->ma_nguoi_dung)
                    ? $phong->nguoiDung2
                    : $phong->nguoiDung1;
                $phong->tenHienThi   = $nguoiKhac ? $nguoiKhac->ho_ten : 'Người dùng';
                $phong->vaiTroHienThi = 'Người dùng';
            } else {
                if ($phong->nhaHang) {
                    $phong->tenHienThi   = $phong->nhaHang->ten_nha_hang;
                    $phong->vaiTroHienThi = 'Nhà hàng';
                } else {
                    $nguoiKhac = ($phong->ma_nguoi_dung_1 == $user->ma_nguoi_dung)
                        ? $phong->nguoiDung2
                        : $phong->nguoiDung1;
                    $phong->tenHienThi   = $nguoiKhac ? $nguoiKhac->ho_ten : 'Người dùng';
                    $phong->vaiTroHienThi = 'Người dùng';
                }
            }
        }

        return view('tinnhan.index', compact('phongChats','user','userVaiTro'));
    }

    /**
     * =========================
     * TẠO PHÒNG CHAT
     * =========================
     */
    public function store(Request $request)
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');
        if (!$maNguoiDung) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập');
        }

        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return redirect()->route('login')->with('error', 'Phiên đăng nhập không hợp lệ.');
        }

        $userRole  = Session::get('user_role');
        $maNhaHang = Session::get('ma_nha_hang');

        $request->validate([
            'loai_phong'      => 'required|in:user_user,user_nhahang,nhahang_nhahang',
            'ma_nguoi_dung_2' => 'required|exists:nguoi_dung,ma_nguoi_dung',
            'ma_nha_hang'     => 'nullable|exists:nha_hang,ma_nha_hang',
        ]);

        // Kiểm tra phòng chat đã tồn tại
        $phongChat = PhongChat::where('loai_phong', $request->loai_phong)
            ->where(function($q) use ($user, $request){
                $q->where([
                    ['ma_nguoi_dung_1',$user->ma_nguoi_dung],
                    ['ma_nguoi_dung_2',$request->ma_nguoi_dung_2]
                ])
                ->orWhere([
                    ['ma_nguoi_dung_1',$request->ma_nguoi_dung_2],
                    ['ma_nguoi_dung_2',$user->ma_nguoi_dung]
                ]);
            })
            ->when($request->ma_nha_hang, function($q) use ($request){
                $q->where('ma_nha_hang', $request->ma_nha_hang);
            })
            ->first();

        // Nếu chưa có thì tạo mới
        if (!$phongChat) {
            $phongChat = PhongChat::create([
                'loai_phong'      => $request->loai_phong,
                'ma_nguoi_dung_1' => $user->ma_nguoi_dung,
                'ma_nguoi_dung_2' => $request->ma_nguoi_dung_2,
                'ma_nha_hang'     => $request->ma_nha_hang,
            ]);
        }

        return redirect()->route('phongchat.show', $phongChat->id);
    }

    /**
     * =========================
     * HIỂN THỊ PHÒNG CHAT
     * =========================
     */
    public function show($id)
    {
        $maNguoiDung = Session::get('ma_nguoi_dung');
        if (!$maNguoiDung) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập');
        }

        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return redirect()->route('login')->with('error', 'Phiên đăng nhập không hợp lệ.');
        }

        $userRole  = Session::get('user_role');
        $maNhaHang = Session::get('ma_nha_hang');

        $phongChat = PhongChat::with(['tinNhans.nguoiGui','nguoiDung1','nguoiDung2','nhaHang'])
            ->findOrFail($id);

        // Kiểm tra quyền tham gia
        $thamGia = ($phongChat->ma_nguoi_dung_1 == $user->ma_nguoi_dung)
                || ($phongChat->ma_nguoi_dung_2 == $user->ma_nguoi_dung)
                || ($userRole === 'nhahang' && $phongChat->ma_nha_hang == $maNhaHang);

        if (!$thamGia) abort(403);

        // Xác định tên hiển thị
        if ($userRole === 'nhahang') {
            $nguoiKhac = ($phongChat->ma_nguoi_dung_1 == $user->ma_nguoi_dung)
                ? $phongChat->nguoiDung2
                : $phongChat->nguoiDung1;
            $tenPhong = $nguoiKhac ? $nguoiKhac->ho_ten : 'Người dùng';
        } else {
            $tenPhong = $phongChat->nhaHang ? $phongChat->nhaHang->ten_nha_hang : 'Người dùng khác';
        }

        // Đánh dấu tin nhắn đã đọc
        $phongChat->tinNhans()
            ->where('ma_nguoi_gui','<>',$user->ma_nguoi_dung)
            ->update(['da_doc'=>1]);

        return view('phongchat.show', compact('phongChat','user','tenPhong'));
    }
}