<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnhBaiViet extends Model
{
    use HasFactory;

    protected $table = 'anh_bai_viet';
    protected $primaryKey = 'ma_anh';
    public $timestamps = false;

    protected $fillable = [
        'ma_bai_viet',
        'duong_dan_anh'
    ];

    public function baiViet() {
        return $this->belongsTo(BaiViet::class, 'ma_bai_viet', 'ma_bai_viet');
    }
}
