<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\NguoiDung;

class LoginController extends Controller
{
    // Hiển thị form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'mat_khau'  => 'required|string',
        ]);

        $user = NguoiDung::with('nhaHang')
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->mat_khau, $user->mat_khau)) {
            return back()->with('error', 'Email hoặc mật khẩu không đúng!');
        }

        // ✅ CHỈ LƯU ID & THÔNG TIN CẦN THIẾT
        Session::put('ma_nguoi_dung', $user->ma_nguoi_dung);
        Session::put('user_role', $user->vai_tro);

        if ($user->vai_tro === 'nhahang' && $user->nhaHang) {
            Session::put('ma_nha_hang', $user->nhaHang->ma_nha_hang);
        }

        return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
    }

    // Đăng xuất
    public function logout()
    {
        Session::flush(); // xoá toàn bộ session
        return redirect()->route('home')->with('success', 'Bạn đã đăng xuất.');
    }
}
