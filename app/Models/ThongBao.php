<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThongBao extends Model
{
    use HasFactory;

    protected $table = 'thong_bao';          // tên bảng
    protected $primaryKey = 'ma_thong_bao';  // khóa chính
    public $timestamps = false;              // vì dùng thoi_gian_tao riêng

    protected $fillable = [
        'ma_nguoi_nhan',
        'ma_nguoi_gui',
        'loai_thong_bao',
        'ma_doi_tuong',
        'noi_dung',
        'da_doc',
        'thoi_gian_tao',
    ];

    protected $casts = [
        'da_doc' => 'boolean',
        'thoi_gian_tao' => 'datetime',
    ];

    /* ====== Quan hệ ====== */

    // Người nhận thông báo
    public function nguoiNhan()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_nhan', 'ma_nguoi_dung');
    }

    // Người gửi thông báo
    public function nguoiGui()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_gui', 'ma_nguoi_dung');
    }
}
