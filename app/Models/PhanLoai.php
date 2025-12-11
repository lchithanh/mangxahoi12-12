<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanLoai extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'phan_loai_nha_hang';

    // Khóa chính
    protected $primaryKey = 'ma_phan_loai';

    // Cho phép gán hàng loạt (mass assignment)
    protected $fillable = [
        'ten_phan_loai',
        'mo_ta',
        'thoi_gian_tao',
    ];

    // Quan hệ 1-n với nha_hang
    public function nhaHangs()
    {
        return $this->hasMany(NhaHang::class, 'ma_phan_loai', 'ma_phan_loai');
    }

    // Nếu muốn bỏ tự động cập nhật timestamp
    public $timestamps = false;
}
