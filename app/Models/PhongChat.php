<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhongChat extends Model
{
    use HasFactory;

    protected $table = 'phong_chat';
    protected $primaryKey = 'id';
    public $timestamps = true; // created_at, updated_at

    protected $fillable = [
        'loai_phong',
        'ma_nguoi_dung_1',
        'ma_nguoi_dung_2',
        'ma_nha_hang',
    ];

    // Quan hệ với người dùng 1
    public function nguoiDung1()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung_1', 'ma_nguoi_dung');
    }

    // Quan hệ với người dùng 2
    public function nguoiDung2()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung_2', 'ma_nguoi_dung');
    }

    // Quan hệ với nhà hàng (nếu có)
    public function nhaHang()
    {
        return $this->belongsTo(NhaHang::class, 'ma_nha_hang', 'ma_nha_hang');
    }

    // Quan hệ với tin nhắn
    public function tinNhans()
    {
        return $this->hasMany(TinNhan::class, 'ma_phong_chat', 'id');
    }
}
