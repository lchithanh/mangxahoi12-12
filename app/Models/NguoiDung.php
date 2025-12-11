<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'ma_nguoi_dung';
    public $timestamps = false;

    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'vai_tro',
        'anh_dai_dien',
        'thoi_gian_tao',
        'ngay_sinh',
        'mo_ta'
    ];

    // Quan hệ với bài viết
    public function baiviets()
    {
        return $this->hasMany(BaiViet::class, 'ma_nguoi_dang', 'ma_nguoi_dung');
    }

    // Người theo dõi
    public function followers()
{
    // Những người theo dõi bạn
    return $this->belongsToMany(
        NguoiDung::class,
        'theo_doi',                // Tên bảng
        'ma_nguoi_duoc_theo_doi',  // foreign key của user hiện tại trên bảng theo_doi
        'ma_nguoi_dung'            // foreign key của người theo dõi
    );
}

public function following()
{
    // Những người bạn đang theo dõi
    return $this->belongsToMany(
        NguoiDung::class,
        'theo_doi',
        'ma_nguoi_dung',           // foreign key của user hiện tại trên bảng theo_doi
        'ma_nguoi_duoc_theo_doi'   // foreign key của người được theo dõi
    );
}

}
