<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'ho_ten'   => 'required',
            'email'    => 'required|email|unique:nguoi_dung,email',
            'mat_khau' => 'required|min:3',
            'vai_tro'  => 'required',
            'ngay_sinh'=> 'nullable|date',
        ]);

        $nguoiDung = NguoiDung::create([
            'ho_ten'     => $request->ho_ten,
            'email'      => $request->email,
            'mat_khau'   => Hash::make($request->mat_khau),
            'vai_tro'    => $request->vai_tro,
            'ngay_sinh'  => $request->ngay_sinh ?? null,
        ]);

        // Nếu là chủ quán → redirect tới form đăng ký nhà hàng
        if($request->vai_tro === 'chu_quan') {
            return redirect()->route('nhahang.create', ['ma_chu_so_huu' => $nguoiDung->ma_nguoi_dung]);
        }

        return redirect()->route('login')->with('success', 'Đăng ký thành công, mời đăng nhập');
    }
}
