@extends('layout.header')

@section('title', $user->ho_ten ?? 'Trang cá nhân')

@section('maincontent')
@php
    $currentUserId = session('ma_nguoi_dung');
    $isOwner = $currentUserId && $currentUserId == $user->ma_nguoi_dung;
    $isFollowing = false;

    // Kiểm tra người dùng hiện tại đã theo dõi hay chưa
    if($currentUserId && !$isOwner) {
        // Sử dụng quan hệ followers() và chỉ rõ bảng để tránh ambiguous column
        $isFollowing = $user->followers()
            ->where('theo_doi.ma_nguoi_dung', $currentUserId)
            ->exists();
    }
@endphp

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

  <div class="d-flex justify-content-between mt-3">
    <div class="text-center">
        <h6 class="mb-0">{{ $user->baiviets_count }}</h6>
        <small>Bài viết</small>
    </div>
    <div class="text-center">
        <h6 class="mb-0">{{ $user->followers_count }}</h6>
        <small>Theo dõi</small>
    </div>
    <div class="text-center">
        <h6 class="mb-0">
            {{ $user->following_users_count + $user->following_nha_hangs_count }}
        </h6>
        <small>Đang theo dõi</small>
    </div>
</div>



                {{-- Nút Theo dõi / Hủy theo dõi --}}
                @if($currentUserId && !$isOwner)
                    <form action="{{ route('trangcanhan.toggle.user', $user->ma_nguoi_dung) }}" method="POST" class="mb-2">
    @csrf
    <button type="submit" class="btn btn-sm w-100 {{ $isFollowing ? 'btn-outline-danger' : 'btn-primary' }}">
        {{ $isFollowing ? 'Hủy theo dõi' : 'Theo dõi' }}
    </button>
</form>


                @endif

                {{-- Chỉ chính chủ mới được chỉnh sửa --}}
                @if($isOwner)
                    <a href="{{ route('trangcanhan.edit') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
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
