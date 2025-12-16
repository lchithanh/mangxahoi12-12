<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TinNhan extends Model
{
    use HasFactory;

    protected $table = 'tin_nhan';
    protected $primaryKey = 'ma_tin_nhan';
    public $timestamps = true;

    protected $fillable = [
        'ma_phong_chat',
        'ma_nguoi_gui',
        'noi_dung',
        'da_doc',
    ];

    // Quan hệ với phòng chat
    public function phongChat()
    {
        return $this->belongsTo(PhongChat::class, 'ma_phong_chat', 'id');
    }

    // Quan hệ với người gửi
    public function nguoiGui()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_gui', 'ma_nguoi_dung');
    }
}
