<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    protected $table = 'bai_viet';
    protected $primaryKey = 'ma_bai_viet';
    public $timestamps = false;

    protected $fillable = [
        'ma_nha_hang',
        'ma_nguoi_dang',
        'noi_dung',
        'thoi_gian_tao'
    ];

    // Quan hệ với người đăng bài
    public function nguoiDang() {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dang', 'ma_nguoi_dung');
    }

    // Quan hệ với nhà hàng
    public function nhaHang() {
        return $this->belongsTo(NhaHang::class, 'ma_nha_hang', 'ma_nha_hang');
    }

    // Quan hệ với ảnh bài viết
    public function anhBaiViets() {
        return $this->hasMany(AnhBaiViet::class, 'ma_bai_viet', 'ma_bai_viet');
    }

    // Quan hệ đánh giá thông qua nhà hàng
    public function danhGias()
    {
        return $this->hasManyThrough(
            \App\Models\DanhGia::class,
            \App\Models\NhaHang::class,
            'ma_nha_hang', // khóa ngoại NhaHang trong bảng DanhGia
            'ma_nha_hang', // khóa ngoại trong bảng DanhGia
            'ma_nha_hang', // khóa chính BaiViet → NhaHang
            'ma_nha_hang'
        );
    }

    // Quan hệ tương tác
    public function tuongTacs() {
        return $this->hasMany(TuongTac::class, 'ma_bai_viet', 'ma_bai_viet');
    }

    public function luotThichs() {
        return $this->hasMany(LuotThich::class, 'bai_viet_id', 'ma_bai_viet');
    }
}
