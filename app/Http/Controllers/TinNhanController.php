<?php

namespace App\Http\Controllers;

use App\Models\TinNhan;
use App\Models\PhongChat;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TinNhanController extends Controller
{
    /**
     * =========================
     * GỬI TIN NHẮN
     * =========================
     */
    public function store(Request $request)
    {
        // Lấy id người dùng từ session
        $maNguoiDung = Session::get('ma_nguoi_dung');
        if (!$maNguoiDung) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập');
        }

        // Kiểm tra user có tồn tại trong DB
        $user = NguoiDung::find($maNguoiDung);
        if (!$user) {
            Session::forget('ma_nguoi_dung');
            return redirect()->route('login')->with('error', 'Phiên đăng nhập không hợp lệ.');
        }

        // Validate dữ liệu gửi tin nhắn
        $request->validate([
            'ma_phong_chat' => 'required|exists:phong_chat,id',
            'noi_dung'      => 'required|string',
        ]);

        // Kiểm tra user có thuộc phòng chat hay không
        $hopLe = PhongChat::where('id', $request->ma_phong_chat)
            ->where(function ($q) use ($maNguoiDung) {
                $q->where('ma_nguoi_dung_1', $maNguoiDung)
                  ->orWhere('ma_nguoi_dung_2', $maNguoiDung);
            })
            ->exists();

        if (!$hopLe) {
            return redirect()->back()->with('error', 'Bạn không có quyền gửi tin nhắn trong phòng này');
        }

        // Tạo tin nhắn mới
        TinNhan::create([
            'ma_phong_chat' => $request->ma_phong_chat,
            'ma_nguoi_gui'  => $maNguoiDung,
            'noi_dung'      => $request->noi_dung,
            'da_doc'        => 0,
        ]);

        return redirect()->back()->with('success', 'Tin nhắn đã được gửi');
    }
}