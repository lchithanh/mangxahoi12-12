<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuotThich extends Model
{
    protected $table = 'luot_thich';
    protected $primaryKey = 'luot_thich_id';
    public $timestamps = false;

    protected $fillable = ['bai_viet_id', 'nguoi_dung_id', 'ngay_thich'];

    public function baiViet()
    {
        return $this->belongsTo(BaiViet::class, 'bai_viet_id', 'ma_bai_viet');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id', 'ma_nguoi_dung');
    }
}
