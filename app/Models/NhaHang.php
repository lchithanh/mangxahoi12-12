<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaHang extends Model
{
    protected $table = 'nha_hang';
    protected $primaryKey = 'ma_nha_hang';
    public $incrementing = true;
    protected $keyType = 'int';

    // Tắt timestamps
    public $timestamps = false;

    protected $fillable = [
        'ten_nha_hang',
        'dia_chi',
        'so_dien_thoai',
        'gio_mo_cua',
        'ma_chu_so_huu',
        'ma_khu_vuc',
        'ma_phan_loai',
        'anh_dai_dien',
        'mo_ta',
    ];

    // Quan hệ với chủ sở hữu
    public function chuSoHuu()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_chu_so_huu', 'ma_nguoi_dung');
    }

    // Quan hệ với khu vực
    public function khuVuc()
    {
        return $this->belongsTo(KhuVuc::class, 'ma_khu_vuc', 'ma_khu_vuc');
    }

    // Quan hệ với phân loại nhà hàng
    public function phanLoai()
    {
        return $this->belongsTo(PhanLoai::class, 'ma_phan_loai', 'ma_phan_loai');
    }

    // Quan hệ bài viết
    public function baiViets()
    {
        return $this->hasMany(BaiViet::class, 'ma_nha_hang', 'ma_nha_hang');
    }

    // Quan hệ đánh giá
    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'ma_nha_hang', 'ma_nha_hang');
    }
 public function tinNhans()
{
    return $this->hasManyThrough(
        TinNhan::class,   // Model cuối cùng
        PhongChat::class, // Model trung gian
        'ma_nha_hang',    // Khóa ngoại trên bảng phong_chat trỏ tới nha_hang
        'ma_phong_chat',  // Khóa ngoại trên bảng tin_nhan trỏ tới phong_chat
        'ma_nha_hang',    // Khóa chính trên bảng nha_hang
        'id'              // Khóa chính trên bảng phong_chat (chính là id)
    );
}
}
