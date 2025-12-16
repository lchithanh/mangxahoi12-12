<?php

namespace App\Http\Controllers;

use App\Models\TinNhan;
use App\Models\PhongChat;
use Illuminate\Http\Request;

class TinNhanController extends Controller
{
    // Danh sách phòng chat
    public function index()
    {
        $user = session('user');
        if (!$user) return redirect()->route('login');

        $userVaiTro = $user->vai_tro ?? 'user';

        $phongChats = PhongChat::with(['nguoiDung1','nguoiDung2','nhaHang','tinNhans'])
            ->where(function($q) use ($user){
                $q->where('ma_nguoi_dung_1',$user->ma_nguoi_dung)
                  ->orWhere('ma_nguoi_dung_2',$user->ma_nguoi_dung)
                  ->orWhere('ma_nha_hang',$user->ma_nha_hang ?? 0);
            })
            ->get();

        // Gán tên hiển thị
        foreach($phongChats as $phong){
            if($userVaiTro === 'nhahang'){
                $nguoiKhac = ($phong->ma_nguoi_dung_1 == $user->ma_nguoi_dung)
                    ? $phong->nguoiDung2
                    : $phong->nguoiDung1;
                $phong->tenHienThi = $nguoiKhac ? $nguoiKhac->ho_ten : 'Người dùng';
                $phong->vaiTroHienThi = 'Người dùng';
            } else {
                if($phong->nhaHang){
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

    // Gửi tin nhắn
    public function store(Request $request)
    {
        $user = session('user');
        if (!$user) abort(403);

        $request->validate([
            'ma_phong_chat' => 'required|exists:phong_chat,id',
            'noi_dung' => 'required|string',
        ]);

        TinNhan::create([
            'ma_phong_chat' => $request->ma_phong_chat,
            'ma_nguoi_gui' => $user->ma_nguoi_dung,
            'noi_dung' => $request->noi_dung,
            'da_doc' => 0,
        ]);

        return back();
    }
}
