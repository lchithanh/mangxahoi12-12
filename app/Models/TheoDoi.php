<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheoDoi extends Model
{
    use HasFactory;

    protected $table = 'theo_doi';
    protected $primaryKey = 'ma_theo_doi';
    public $timestamps = false;

    protected $fillable = [
        'ma_nguoi_dung',
        'ma_nguoi_duoc_theo_doi',
        'thoi_gian_tao'
    ];

    public function nguoiDung() {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    public function nguoiDuocTheoDoi() {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_duoc_theo_doi', 'ma_nguoi_dung');
    }
}
