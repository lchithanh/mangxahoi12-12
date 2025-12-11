<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThanhPho extends Model
{
    use HasFactory;

    protected $table = 'thanh_pho';
    protected $primaryKey = 'ma_thanh_pho';
    public $timestamps = false;

    protected $fillable = [
        'ten_thanh_pho'
    ];

    public function khuVucs() {
        return $this->hasMany(KhuVuc::class, 'ma_thanh_pho', 'ma_thanh_pho');
    }
}
