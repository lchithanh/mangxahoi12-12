<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnhDanhGia extends Model
{
    protected $table = 'anh_danh_gia';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'ma_danh_gia',
        'duong_dan_anh',
        'thoi_gian_tao',
    ];

    // Quan hệ belongsTo: ma_danh_gia là khóa ngoại, DanhGia dùng primaryKey 'ma_danh_gia'
    public function danhGia()
    {
        return $this->belongsTo(DanhGia::class, 'ma_danh_gia', 'ma_danh_gia');
    }
}
