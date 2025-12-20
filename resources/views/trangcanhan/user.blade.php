@extends('layout.header')

@section('title', $user->ho_ten ?? 'Trang cá nhân')

@section('maincontent')
<div class="row g-4">

    <!-- Sidebar: Thông tin người dùng -->
    <div class="col-lg-4">
        <div class="card mb-4 shadow-sm text-center">
            <div class="position-relative" style="height: 200px; overflow: hidden;">
                <img src="{{ $user->anh_dai_dien 
                    ? asset($user->anh_dai_dien) 
                    : 'https://via.placeholder.com/400x200?text=No+Avatar' }}"
                class="card-img"
                style="object-fit: cover; height:100%;"
                alt="{{ $user->ho_ten }}">
            </div>

            <div class="card-body">
                <h5 class="card-title">{{ $user->ho_ten ?? 'Người dùng' }}</h5>
                <p class="text-muted mb-2"><i class="bi bi-info-circle"></i> {{ $user->mo_ta ?? 'Chưa cập nhật' }}</p>

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

                {{-- Chỉ chính chủ mới được chỉnh sửa --}}
                @php
                    $currentUserId = session('ma_nguoi_dung');
                @endphp
                @if($currentUserId && $currentUserId == $user->ma_nguoi_dung)
                    <a href="{{ route('trangcanhan.edit') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-pencil-square"></i> Chỉnh sửa hồ sơ
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content: Các đánh giá -->
    <div class="col-lg-8">
        <h4>Các đánh giá của {{ $user->ho_ten ?? 'Người dùng' }}</h4>

        @if(isset($danhGias) && $danhGias->count() > 0)
            @include('danhgia.list', [
                'baiViet' => null,
                'danhGias' => $danhGias,
                'user' => $user
            ])
        @else
            <div class="text-center text-muted py-3">
                <i class="bi bi-file-earmark-text" style="font-size:2.5rem;"></i>
                <p class="mt-2">Chưa có đánh giá nào.</p>
            </div>
        @endif
    </div>
</div>
@endsection