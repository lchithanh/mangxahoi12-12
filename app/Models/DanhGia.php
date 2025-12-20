<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    protected $table = 'danh_gia';
    protected $primaryKey = 'ma_danh_gia';
    public $timestamps = false;

    protected $fillable = [
        'ma_nguoi_dung',
        'ma_nha_hang',
        'ma_bai_viet',
        'diem_danh_gia',
        'binh_luan',
        'thoi_gian_tao'
    ];

    protected $casts = [
        'thoi_gian_tao' => 'datetime',
    ];

    public function nguoiDung() {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    public function nhaHang() {
        return $this->belongsTo(NhaHang::class, 'ma_nha_hang', 'ma_nha_hang');
    }public function baiViet()
{
    return $this->belongsTo(BaiViet::class, 'ma_bai_viet', 'ma_bai_viet');
}

public function anhDanhGias()
{
    return $this->hasMany(AnhDanhGia::class, 'ma_danh_gia', 'ma_danh_gia');
}



}

