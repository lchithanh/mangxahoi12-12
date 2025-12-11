@extends('layout.header')

@section('title', $user->ho_ten ?? 'Trang cá nhân')

@section('maincontent')
<div class="row g-4">
    <!-- Sidebar thông tin người dùng -->
    <div class="col-lg-4">
        <div class="card mb-4 shadow-sm text-center">
            <div class="position-relative" style="height: 200px; overflow: hidden;">
                <img src="{{ $user->anh_dai_dien && file_exists(storage_path('app/public/' . $user->anh_dai_dien))
                          ? asset('storage/' . $user->anh_dai_dien)
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

                @if(session('user') && session('user')->ma_nguoi_dung == $user->ma_nguoi_dung)
                    <a href="{{ route('trangcanhan.edit') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-pencil-square"></i> Chỉnh sửa hồ sơ
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content: Bài viết của người dùng -->
    <div class="col-lg-8">
        <h4 class="mb-4 fw-bold">Bài viết của {{ $user->ho_ten ?? 'Người dùng' }}</h4>

        @if($user->baiviets && $user->baiviets->count() > 0)
            @include('baiviet.list', ['baiviets' => $user->baiviets])
        @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-file-earmark-text" style="font-size:3rem;"></i>
                <p class="mt-3">Bạn chưa đăng bài viết nào.</p>
            </div>
        @endif
    </div>
</div>

<style>
/* Khoảng cách giữa các bài viết */
.row .col {
    margin-bottom: 1rem;
}

/* Responsive ảnh avatar */
@media (max-width: 768px) {
    .card-img {
        height: 150px !important;
    }
}
</style>
@endsection
