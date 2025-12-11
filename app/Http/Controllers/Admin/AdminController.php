<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Kiểm tra xem đã có admin chưa
        if (!NguoiDung::where('vai_tro', 'quan_tri')->exists()) {
            NguoiDung::create([
                'ho_va_ten' => 'Quản trị viên',
                'ten_dang_nhap' => 'admin',
                'email' => 'admin@gmail.com',
                'mat_khau_ma_hoa' => Hash::make('123456'), // đổi sau
                'vai_tro' => 'quan_tri',
                'trang_thai' => 'hoat_dong',
            ]);
        }
        
    if (!session()->has('nguoi_dung_id') || session('vai_tro') !== 'quan_tri') {
        return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập!');
    }

    return view('admin.dashboard');
    }
}

