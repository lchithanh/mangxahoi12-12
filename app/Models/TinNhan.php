<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhan extends Model
{
    use HasFactory;

    protected $table = 'tin_nhan';

    protected $primaryKey = 'ma_tin_nhan'; // ✅ ĐÚNG DB

    public $timestamps = true; // ✅ DB có created_at

    protected $fillable = [
        'ma_nha_hang',
        'ma_nguoi_gui',
        'noi_dung',
        'da_doc',
    ];



    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Tin nhắn thuộc về 1 nhà hàng
    public function nhaHang()
    {
        return $this->belongsTo(
            \App\Models\NhaHang::class,
            'ma_nha_hang',
            'ma_nha_hang'
        );
    }

    // Người gửi (chỉ có khi user gửi)
    public function nguoiGui()
    {
        return $this->belongsTo(
            \App\Models\NguoiDung::class,
            'ma_nguoi_gui',
            'ma_nguoi_dung'
        );
    }
}
