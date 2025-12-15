<?php  
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhGia;
use App\Models\BaiViet;

class DanhGiaController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá của 1 bài viết
     */
    public function index($ma_bai_viet)
    {
        $baiViet = BaiViet::with(['nguoiDang', 'anhBaiViets'])->findOrFail($ma_bai_viet);

        // Lấy danh sách đánh giá mới nhất trước
        $danhGias = $baiViet->danhGias()
            ->with('nguoiDung')
            ->orderBy('thoi_gian_tao', 'desc')
            
            ->get();

            // Lấy người dùng hiện tại từ session
    $user = session('user');
        return view('danhgia.index', compact('baiViet', 'danhGias','user'));
    }

    /**
     * Lưu đánh giá
     */
    public function store(Request $request, $ma_bai_viet)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('baiviet.index')->with('error', 'Cần đăng nhập để viết đánh giá.');
        }

        $request->validate([
            'diem_danh_gia'   => 'required|integer|min:1|max:5',
            'binh_luan'       => 'required|string',
            'duong_dan_anh.*' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        $baiViet = BaiViet::findOrFail($ma_bai_viet);

        // Tạo đánh giá
        $danhGia = DanhGia::create([
            'ma_nguoi_dung'  => $user->ma_nguoi_dung,
            'ma_nha_hang'    => $baiViet->ma_nha_hang,
            'ma_bai_viet'    => $baiViet->ma_bai_viet, // ✅ thêm dòng này

            'diem_danh_gia'  => $request->diem_danh_gia,
            'binh_luan'      => $request->binh_luan,
            'thoi_gian_tao'  => now(),
            'duong_dan_anh'  => null,
        ]);

        // Lưu ảnh nếu có
        if ($request->hasFile('duong_dan_anh')) {
            $savedPaths = [];
            foreach ($request->file('duong_dan_anh') as $file) {
                $filename = time() . "_" . uniqid() . "." . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/danhgia'), $filename);
                $savedPaths[] = 'uploads/danhgia/' . $filename;
            }
            $danhGia->duong_dan_anh = implode(',', $savedPaths);
            $danhGia->save();
        }

        // Redirect về index ngay lập tức với đánh giá mới
        return redirect()->route('danhgia.index', $ma_bai_viet)
                         ->with('success', 'Đánh giá đã được gửi!');
    }
}
