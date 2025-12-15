<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\NguoiDung;
use App\Models\BaiViet;
use App\Models\KhuVuc;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = null;

        if (Session::has('ma_nguoi_dung')) {
            $user = NguoiDung::find(Session::get('ma_nguoi_dung'));
        }

        $district = $request->query('district');
        $sort = $request->query('sort');

        $query = BaiViet::with(['nguoiDang', 'anhBaiViets', 'nhaHang.khuVuc'])
                        ->withCount('luotThichs');

        if ($district) {
            $query->whereHas('nhaHang', function ($q) use ($district) {
                $q->where('ma_khu_vuc', $district);
            });
        }

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

        $baiviets = $query->get();
        $khuVucList = KhuVuc::all();

        return view('home', compact('user', 'baiviets', 'khuVucList'));
    }
}
