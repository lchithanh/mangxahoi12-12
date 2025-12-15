<?php

namespace App\Http\Controllers;

use App\Models\TinNhan;
use App\Models\NhaHang;
use Illuminate\Http\Request;

class TinNhanController extends Controller
{
    /**
     * INDEX
     * Danh sách phòng chat (Inbox)
     */
    public function index()
    {
        $user = session('user');

        if (!$user) {
            return redirect()->back()->with('error', 'Bạn chưa đăng nhập');
        }

        // USER: thấy các nhà hàng đã từng chat
        if ($user->vai_tro === 'nguoi_dung') {
            $nhaHangs = NhaHang::whereHas('tinNhans', function ($q) use ($user) {
                $q->where('ma_nguoi_gui', $user->ma_nguoi_dung);
            })
            ->withCount(['tinNhans as so_tin_chua_doc' => function ($q) {
                $q->where('da_doc', 0)
                  ->whereNull('ma_nguoi_gui'); // nhà hàng gửi
            }])
            ->get();
        }

        // CHỦ QUÁN: thấy nhà hàng mình sở hữu
        if ($user->vai_tro === 'chu_quan') {
            $nhaHangs = NhaHang::where('ma_chu_so_huu', $user->ma_nguoi_dung)
                ->withCount(['tinNhans as so_tin_chua_doc' => function ($q) {
                    $q->where('da_doc', 0)
                      ->whereNotNull('ma_nguoi_gui'); // user gửi
                }])
                ->get();
        }

        return view('tinnhan.index', compact('nhaHangs', 'user'));
    }

    /**
     * SHOW
     * Mở phòng chat của 1 nhà hàng
     */
    public function show($maNhaHang)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->back()->with('error', 'Bạn chưa đăng nhập');
        }

        $nhaHang = NhaHang::findOrFail($maNhaHang);

        // Lấy toàn bộ tin nhắn
        $messages = TinNhan::where('ma_nha_hang', $maNhaHang)
            ->orderBy('created_at')
            ->get();

        // Đánh dấu đã đọc
        if ($user->vai_tro === 'nguoi_dung') {
            TinNhan::where('ma_nha_hang', $maNhaHang)
                ->whereNull('ma_nguoi_gui')
                ->update(['da_doc' => 1]);
        }

        if ($user->vai_tro === 'chu_quan' && $nhaHang->ma_chu_so_huu == $user->ma_nguoi_dung) {
            TinNhan::where('ma_nha_hang', $maNhaHang)
                ->whereNotNull('ma_nguoi_gui')
                ->update(['da_doc' => 1]);
        }

        return view('tinnhan.show', compact('nhaHang', 'messages', 'user'));
    }

    /**
     * STORE
     * Gửi tin nhắn
     */
    public function store(Request $request)
    {
        $user = session('user');
        if (!$user) return response()->json(['error' => 'Chưa đăng nhập'], 401);

        $request->validate([
            'ma_nha_hang' => 'required|integer',
            'noi_dung'    => 'required|string|max:2000',
        ]);

        $nhaHang = NhaHang::findOrFail($request->ma_nha_hang);
        $maNguoiGui = $user->vai_tro === 'nguoi_dung' ? $user->ma_nguoi_dung : null;

        if ($user->vai_tro === 'chu_quan' && $nhaHang->ma_chu_so_huu != $user->ma_nguoi_dung) {
            return response()->json(['error' => 'Không có quyền'], 403);
        }

        $tinNhan = TinNhan::create([
            'ma_nha_hang'  => $nhaHang->ma_nha_hang,
            'ma_nguoi_gui' => $maNguoiGui,
            'noi_dung'     => $request->noi_dung,
            'da_doc'       => 0,
        ]);

        return response()->json($tinNhan);
    }

    /**
     * FETCH
     * Lấy tin nhắn mới (polling)
     */
    public function fetch(Request $request, $maNhaHang)
    {
        $user = session('user');
        if (!$user) return response()->json([], 401);

        $lastId = (int) $request->query('last_id', 0);

        $messages = TinNhan::where('ma_nha_hang', $maNhaHang)
            ->where('ma_tin_nhan', '>', $lastId) // đúng cột DB
            ->orderBy('ma_tin_nhan')
            ->get();

        return response()->json($messages);
    }
}
