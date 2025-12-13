<?php

namespace App\Http\Controllers;

use App\Models\TinNhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TinNhanController extends Controller
{
    public function index()
    {
        $userId = session('ma_nguoi_dung');

        $threads = TinNhan::where('nguoi_gui_id', $userId)
            ->orWhere('nguoi_nhan_id', $userId)
            ->with(['nguoiGui', 'nguoiNhan'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($item) use ($userId) {
                return $item->nguoi_gui_id == $userId ? $item->nguoi_nhan_id : $item->nguoi_gui_id;
            });

        return view('tinnhan.index', compact('threads', 'userId'));
    }

    public function show($nguoiNhanId)
    {
        $userId = session('ma_nguoi_dung');

        $tinNhan = TinNhan::TinNhanGiuaHaiNguoi($userId, $nguoiNhanId)
            ->with(['nguoiGui', 'nguoiNhan'])
            ->get();

        TinNhan::where('nguoi_gui_id', $nguoiNhanId)
               ->where('nguoi_nhan_id', $userId)
               ->update(['da_doc' => 1]);

        return view('tinnhan.show', compact('tinNhan', 'nguoiNhanId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ma_nguoi_nhan' => 'required|integer',
            'noi_dung' => 'required|string|max:2000',
        ]);

        $userId = session('ma_nguoi_dung');

       TinNhan::create([
    'nguoi_gui_id' => $userId,
    'nguoi_nhan_id' => $request->ma_nguoi_nhan, // đây phải đúng
    'nha_hang_gui_id' => null,
    'nha_hang_nhan_id' => null,
    'noi_dung' => $request->noi_dung,
    'da_doc' => 0,
]);


        return redirect()->back();
    }
}
