<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuongTac extends Model
{
    use HasFactory;

    protected $table = 'tuong_tac';
    protected $primaryKey = 'ma_tuong_tac';
    public $timestamps = false;

    protected $fillable = [
        'ma_bai_viet',
        'ma_nguoi_dung',
        'loai_tuong_tac',
        'thoi_gian_tao'
    ];

    public function baiViet() {
        return $this->belongsTo(BaiViet::class, 'ma_bai_viet', 'ma_bai_viet');
    }

    public function nguoiDung() {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }
}
