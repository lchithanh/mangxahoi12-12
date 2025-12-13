<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhan extends Model
{
    use HasFactory;

    protected $table = 'tin_nhan';
    protected $primaryKey = 'id';
    public $timestamps = true; // enable để Laravel tự động quản lý created_at, updated_at

    protected $fillable = [
        'nguoi_gui_id',
        'nha_hang_gui_id',
        'nguoi_nhan_id',
        'nha_hang_nhan_id',
        'noi_dung',
        'da_doc',
    ];

    public function nguoiGui()
    {
        return $this->belongsTo(\App\Models\NguoiDung::class, 'nguoi_gui_id', 'ma_nguoi_dung');
    }

    public function nguoiNhan()
    {
        return $this->belongsTo(\App\Models\NguoiDung::class, 'nguoi_nhan_id', 'ma_nguoi_dung');
    }

    public function scopeTinNhanGiuaHaiNguoi($query, $user1, $user2)
    {
        return $query->where(function($q) use ($user1, $user2) {
            $q->where('nguoi_gui_id', $user1)
              ->where('nguoi_nhan_id', $user2);
        })->orWhere(function($q) use ($user1, $user2) {
            $q->where('nguoi_gui_id', $user2)
              ->where('nguoi_nhan_id', $user1);
        })->orderBy('created_at', 'asc');
    }
}
