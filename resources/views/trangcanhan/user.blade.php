@extends('layout.header')

@section('title', $user->ho_ten ?? 'Trang cá nhân')

@section('maincontent')
<div class="row g-4">
    <!-- Sidebar: Thông tin người dùng -->
    <div class="col-lg-4">
        <div class="card mb-4 shadow-sm text-center">
            <div class="position-relative" style="height: 200px; overflow: hidden;">
                <img src="{{ $user->anh_dai_dien && file_exists(public_path($user->anh_dai_dien))
                     ? asset($user->anh_dai_dien)
                     : 'https://via.placeholder.com/400x200?text=No+Avatar' }}"
                     class="card-img"
                     style="object-fit: cover; height:100%;"
                     alt="{{ $user->ho_ten }}">
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $user->ho_ten ?? 'Người dùng' }}</h5>
                <p class="text-muted mb-2"><i class="bi bi-geo-alt"></i> {{ $user->dia_chi ?? 'Địa chỉ chưa cập nhật' }}</p>

                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h6 class="mb-0">{{ $user->baiviets_count ?? 0 }}</h6>
                        <small>Bài viết</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $user->followers_count ?? 0 }}</h6>
                        <small>Theo dõi</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $user->following_count ?? 0 }}</h6>
                        <small>Đang theo dõi</small>
                    </div>
                </div>

                <!-- Nút Theo dõi người dùng khác -->
                @if(session('user') && session('user')->ma_nguoi_dung != $user->ma_nguoi_dung)
                    @php
                        $isFollowing = \App\Models\TheoDoi::where('ma_nguoi_dung', session('user')->ma_nguoi_dung)
                                        ->where('ma_nguoi_duoc_theo_doi', $user->ma_nguoi_dung)
                                        ->exists();
                    @endphp

                    @if($isFollowing)
                        <a href="{{ route('unfollow', $user->ma_nguoi_dung) }}" class="btn btn-outline-danger btn-sm w-100 mb-2">
                            <i class="bi bi-person-dash"></i> Hủy theo dõi
                        </a>
                    @else
                        <a href="{{ route('follow', $user->ma_nguoi_dung) }}" class="btn btn-primary btn-sm w-100 mb-2">
                            <i class="bi bi-person-plus"></i> Theo dõi
                        </a>
                    @endif
                @endif

                <!-- Chỉnh sửa hồ sơ -->
                @if(session('user') && session('user')->ma_nguoi_dung == $user->ma_nguoi_dung)
                    <a href="{{ route('trangcanhan.edit') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-pencil-square"></i> Chỉnh sửa hồ sơ
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content: Đánh giá -->
    <div class="col-lg-8">
        <h4>Các đánh giá của {{ $user->ho_ten ?? 'Người dùng' }}</h4>

        @if($danhGias && $danhGias->count() > 0)
            @foreach($danhGias as $dg)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">
                            {{ $dg->baiViet->nhaHang->ten_nha_hang ?? 'Nhà hàng đã xóa' }} - {{ $dg->thoi_gian_tao->format('d/m/Y H:i') }}
                        </h6>
                        <p>{{ $dg->binh_luan }}</p>
                        <p><strong>Điểm:</strong> {{ $dg->diem_danh_gia }}/5</p>

                        @if($dg->duong_dan_anh)
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach(explode(',', $dg->duong_dan_anh) as $anh)
                                    <img src="{{ asset($anh) }}" alt="Ảnh đánh giá" class="rounded" style="width:80px; height:80px; object-fit:cover;">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-file-earmark-text" style="font-size:3rem;"></i>
                <p class="mt-3">Chưa gửi đánh giá nào.</p>
            </div>
        @endif
    </div>
</div>
@endsection
