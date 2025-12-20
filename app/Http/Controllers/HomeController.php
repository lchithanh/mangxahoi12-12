<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ThongBao;
use App\Models\NguoiDung;
use App\Models\BaiViet;
use App\Models\KhuVuc;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        /* ========== LẤY USER TỪ SESSION ID ========== */
        $maNguoiDung = Session::get('ma_nguoi_dung');
        $user = null;

        if ($maNguoiDung) {
            $user = NguoiDung::find($maNguoiDung);
        }

        /* ========== THÔNG BÁO ========== */
        $soThongBaoChuaDoc = 0;
        $thongBaos = collect();

        if ($user) {
            $soThongBaoChuaDoc = ThongBao::where('ma_nguoi_nhan', $user->ma_nguoi_dung)
                ->where('da_doc', 0)
                ->count();

            $thongBaos = ThongBao::where('ma_nguoi_nhan', $user->ma_nguoi_dung)
                ->orderByDesc('thoi_gian_tao')
                ->limit(5)
                ->get();
        }

        /* ========== FILTER & SEARCH ========== */
        $district = $request->query('district');
        $category = $request->query('category');
        $sort     = $request->query('sort');
        $keyword  = $request->query('keyword');

        /* ========== QUERY BÀI VIẾT ========== */
        $query = BaiViet::with([
                'nguoiDang',
                'anhBaiViets',
                'nhaHang.khuVuc',
                'nhaHang.phanLoai'
            ])
            ->withCount('luotThichs');

        // Lọc theo khu vực
        if ($district) {
            $query->whereHas('nhaHang', function ($q) use ($district) {
                $q->where('ma_khu_vuc', $district);
            });
        }

        // Lọc theo phân loại
        if ($category) {
            $query->whereHas('nhaHang', function ($q) use ($category) {
                $q->where('ma_phan_loai', $category);
            });
        }

        // Tìm kiếm
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('noi_dung', 'like', "%{$keyword}%")
                  ->orWhereHas('nhaHang', function ($nh) use ($keyword) {
                      $nh->where('ten_nha_hang', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('nguoiDang', function ($nd) use ($keyword) {
                      $nd->where('ho_ten', 'like', "%{$keyword}%");
                  });
            });
        }

        /* ========== SẮP XẾP ========== */
        if ($sort === 'followers') {
            $query->withCount([
                'nhaHang as followers_count' => function ($q) {
                    $q->join('theo_doi', 'nha_hang.ma_nha_hang', '=', 'theo_doi.ma_nha_hang');
                }
            ])->orderByDesc('followers_count');
        } elseif ($sort === 'likes') {
            $query->orderByDesc('luot_thichs_count');
        } else {
            $query->orderByDesc('thoi_gian_tao');
        }

        /* ========== LẤY DỮ LIỆU ========== */
        $baiviets   = $query->get();
        $khuVucList = KhuVuc::all();

        return view('home', compact(
            'user',
            'baiviets',
            'khuVucList',
            'soThongBaoChuaDoc',
            'thongBaos'
        ));
    }
}
