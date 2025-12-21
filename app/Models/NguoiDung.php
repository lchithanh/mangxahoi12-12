<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'ma_nguoi_dung';
    public $timestamps = false;

    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'vai_tro',
        'anh_dai_dien',
        'thoi_gian_tao',
        'ngay_sinh',
        'mo_ta'
        
    ];

    // Quan hệ với bài viết
    public function baiviets()
    {
        return $this->hasMany(BaiViet::class, 'ma_nguoi_dang', 'ma_nguoi_dung');
    }

    public function nhaHang() {
        return $this->hasOne(NhaHang::class, 'ma_chu_so_huu', 'ma_nha_hang');
    }


    // Quan hệ với đánh giá
    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }
    // Người theo dõi
  // Những người mà tôi đang theo dõi
// Những người mà tôi đang theo dõi (nguoi dung)
public function followingUsers() {
    return $this->belongsToMany(
        NguoiDung::class,
        'theo_doi',
        'ma_nguoi_dung',
        'ma_nguoi_duoc_theo_doi'
    );
}

// Nhà hàng mà tôi đang theo dõi
public function followingNhaHangs() {
    return $this->belongsToMany(
        NhaHang::class,
        'theo_doi',
        'ma_nguoi_dung',
        'ma_nha_hang'
    );
}

// Những người đang theo dõi tôi
public function followers() {
    return $this->belongsToMany(
        NguoiDung::class,
        'theo_doi',
        'ma_nguoi_duoc_theo_doi',
        'ma_nguoi_dung'
    );
}


}
