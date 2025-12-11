<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuVuc extends Model
{
    use HasFactory;

    protected $table = 'khu_vuc';
    protected $primaryKey = 'ma_khu_vuc';
    public $timestamps = false;

    protected $fillable = [
        'ten_khu_vuc',
        'ma_thanh_pho'
    ];

    public function thanhPho() {
        return $this->belongsTo(ThanhPho::class, 'ma_thanh_pho', 'ma_thanh_pho');
    }

    public function nhaHangs() {
        return $this->hasMany(NhaHang::class, 'ma_khu_vuc', 'ma_khu_vuc');
    }
}
