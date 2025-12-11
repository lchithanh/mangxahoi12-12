<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    use HasFactory;

    protected $table = 'danh_gia';
    protected $primaryKey = 'ma_danh_gia';
    public $timestamps = false;

    protected $fillable = [
        'ma_nguoi_dung',
        'ma_nha_hang',
        'diem_danh_gia',
        'binh_luan',
        'duong_dan_anh',
        'thoi_gian_tao'
    ];

    public function nguoiDung() {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    public function nhaHang() {
        return $this->belongsTo(NhaHang::class, 'ma_nha_hang', 'ma_nha_hang');
    }
    

}
