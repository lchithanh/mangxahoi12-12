<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TheoDoi extends Model
{
    use HasFactory;

    protected $table = 'theo_doi'; // tên bảng
    protected $primaryKey = 'ma_theo_doi';
    public $timestamps = false; // nếu không dùng created_at, updated_at

    protected $fillable = ['ma_nguoi_dung', 'ma_nguoi_duoc_theo_doi', 'ma_nha_hang', 'thoi_gian_tao'];


    // Quan hệ với người dùng
    public function nguoiDung() {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung');
    }

    public function nguoiDuocTheoDoi() {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_duoc_theo_doi');
    }
}
