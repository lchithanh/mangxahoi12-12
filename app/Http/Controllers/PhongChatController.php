<?php

namespace App\Http\Controllers;

use App\Models\PhongChat;
use Illuminate\Http\Request;

class PhongChatController extends Controller
{
    // Danh sách phòng chat của user hoặc nhà hàng
    public function index()
    {
        $user = session('user');
        if (!$user) return redirect()->route('login');

        $userVaiTro = $user->vai_tro ?? 'user';

        // Lấy tất cả phòng chat mà người này tham gia
        $phongChats = PhongChat::with(['nguoiDung1','nguoiDung2','nhaHang','tinNhans'])
            ->where(function($q) use ($user) {
                $q->where('ma_nguoi_dung_1', $user->ma_nguoi_dung)
                  ->orWhere('ma_nguoi_dung_2', $user->ma_nguoi_dung)
                  ->orWhere('ma_nha_hang', $user->ma_nha_hang ?? 0);
            })
            ->get();

        // Gán tên hiển thị cho từng phòng
        foreach ($phongChats as $phong) {
            if ($userVaiTro === 'nhahang') {
                // Nhà hàng xem -> hiển thị tên user
                $nguoiKhac = ($phong->ma_nguoi_dung_1 == $user->ma_nguoi_dung)
                    ? $phong->nguoiDung2
                    : $phong->nguoiDung1;
                $phong->tenHienThi = $nguoiKhac ? $nguoiKhac->ho_ten : 'Người dùng';
                $phong->vaiTroHienThi = 'Người dùng';
            } else {
                // User xem -> hiển thị tên nhà hàng nếu có, không thì người dùng khác
                if ($phong->nhaHang) {
                    $phong->tenHienThi = $phong->nhaHang->ten_nha_hang;
                    $phong->vaiTroHienThi = 'Nhà hàng';
                } else {
                    $nguoiKhac = ($phong->ma_nguoi_dung_1 == $user->ma_nguoi_dung)
                        ? $phong->nguoiDung2
                        : $phong->nguoiDung1;
                    $phong->tenHienThi = $nguoiKhac ? $nguoiKhac->ho_ten : 'Người dùng';
                    $phong->vaiTroHienThi = 'Người dùng';
                }
            }
        }

        return view('tinnhan.index', compact('phongChats','user','userVaiTro'));
    }

    // Tạo phòng chat nếu chưa có
    public function store(Request $request)
    {
        $user = session('user');
        if (!$user) abort(403);

        $request->validate([
            'loai_phong' => 'required|in:user_user,user_nhahang,nhahang_nhahang',
            'ma_nguoi_dung_2' => 'required|exists:nguoi_dung,ma_nguoi_dung',
            'ma_nha_hang' => 'nullable|exists:nha_hang,ma_nha_hang',
        ]);

        // Kiểm tra phòng chat đã tồn tại
        $phongChat = PhongChat::where('loai_phong', $request->loai_phong)
            ->where(function($q) use ($user, $request){
                $q->where([['ma_nguoi_dung_1',$user->ma_nguoi_dung], ['ma_nguoi_dung_2',$request->ma_nguoi_dung_2]])
                  ->orWhere([['ma_nguoi_dung_1',$request->ma_nguoi_dung_2], ['ma_nguoi_dung_2',$user->ma_nguoi_dung]]);
            })->first();

        if (!$phongChat) {
            $phongChat = PhongChat::create([
                'loai_phong' => $request->loai_phong,
                'ma_nguoi_dung_1' => $user->vai_tro === 'nhahang' ? $request->ma_nguoi_dung_2 : $user->ma_nguoi_dung,
                'ma_nguoi_dung_2' => $user->vai_tro === 'nhahang' ? $user->ma_nguoi_dung : $request->ma_nguoi_dung_2,
                'ma_nha_hang' => $user->vai_tro === 'nhahang' ? $user->ma_nha_hang : $request->ma_nha_hang,
            ]);
        }

        return redirect()->route('phongchat.show', $phongChat->id);
    }

    // Hiển thị tin nhắn trong phòng chat
    public function show($id)
    {
        $user = session('user');
        if (!$user) return redirect()->route('login');

        $phongChat = PhongChat::with(['tinNhans.nguoiGui','nguoiDung1','nguoiDung2','nhaHang'])
            ->findOrFail($id);

        // Kiểm tra quyền xem phòng chat
        $thamGia = ($phongChat->ma_nguoi_dung_1 == $user->ma_nguoi_dung) ||
                   ($phongChat->ma_nguoi_dung_2 == $user->ma_nguoi_dung) ||
                   ($user->vai_tro === 'nhahang' && $phongChat->ma_nha_hang == $user->ma_nha_hang);
        if (!$thamGia) abort(403);

        // Xác định tên hiển thị
        if ($user->vai_tro === 'nhahang') {
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
