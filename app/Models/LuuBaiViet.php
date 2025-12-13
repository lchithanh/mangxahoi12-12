<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuuBaiViet extends Model
{
    protected $table = 'bai_viet_da_luu';
    protected $primaryKey = 'ma_luu';
    public $timestamps = false;

    protected $fillable = [
        'ma_bai_viet',
        'ma_nguoi_dung',
        'thoi_gian_tao',
    ];

    // Quan hệ với người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    // Quan hệ với bài viết
    public function baiViet()
    {
        return $this->belongsTo(BaiViet::class, 'ma_bai_viet', 'ma_bai_viet');
    }
}
