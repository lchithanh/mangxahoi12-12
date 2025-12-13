<?php
namespace App\Http\Controllers;

use App\Models\TinNhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\NguoiDung;
use App\Models\BaiViet;
use App\Models\NhaHang;
use App\Models\KhuVuc;
    use App\Models\LuotThich;
use App\Models\TheoDoi;


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
        $query->whereHas('nhaHang', function($q) use ($district) {
            $q->where('ma_khu_vuc', $district);
        });
    }

    if ($sort == 'followers') {
        $query->withCount(['nhaHang as followers_count' => function($q) {
            $q->join('theo_doi', 'nha_hang.ma_nha_hang', '=', 'theo_doi.ma_nha_hang');
        }])->orderByDesc('followers_count');
    } elseif ($sort == 'likes') {
        $query->orderByDesc('luot_thichs_count');
    } else {
        $query->orderByDesc('thoi_gian_tao');
    }

    $baiviets = $query->get();
    $khuVucList = KhuVuc::all();

    // --- Lấy dữ liệu chat cho sidebar-right ---
    $threads = [];
    $tinNhan = [];
    $nguoiNhanId = $request->query('nguoiNhanId');
$userId = $user->ma_nguoi_dung ?? null;

// Lấy danh sách các "thread" dựa trên những người đã nhắn với user
$threads = [];
if ($userId) {
    $threads = TinNhan::selectRaw('nguoi_gui_id,nguoi_nhan_id')
        ->where('nguoi_gui_id', $userId)
        ->orWhere('nguoi_nhan_id', $userId)
        ->groupBy('nguoi_gui_id', 'nguoi_nhan_id')
        ->get();
}

// Nếu người dùng chọn 1 người chat cụ thể
$tinNhan = [];
if ($nguoiNhanId) {
    $tinNhan = TinNhan::where(function($q) use($userId, $nguoiNhanId){
        $q->where('nguoi_gui_id', $userId)->where('nguoi_nhan_id', $nguoiNhanId);
    })->orWhere(function($q) use($userId, $nguoiNhanId){
        $q->where('nguoi_gui_id', $nguoiNhanId)->where('nguoi_nhan_id', $userId);
    })->orderBy('created_at')->get();
}


    return view('home', compact(
        'user', 'baiviets', 'khuVucList',
        'threads', 'tinNhan', 'nguoiNhanId'
    ));
}



}
