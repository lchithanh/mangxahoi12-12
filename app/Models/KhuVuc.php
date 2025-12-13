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
    ];


    public function nhaHangs() {
        return $this->hasMany(NhaHang::class, 'ma_khu_vuc', 'ma_khu_vuc');
    }
}
