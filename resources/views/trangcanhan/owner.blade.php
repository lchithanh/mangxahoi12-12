@extends('layout.header')

@section('title', $user->ho_ten ?? 'Trang cá nhân chủ quán')

@section('maincontent')
<div class="container py-4">
    <div class="row g-4">
        <!-- Sidebar: Thông tin chủ quán -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <img src="{{ $user->anh_dai_dien 
                                    ? asset($user->anh_dai_dien)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->ho_ten) }}"
                             class="rounded-circle border shadow-sm" width="128" height="128">
                    </div>
                    <h5>{{ $user->ho_ten }}</h5>
                    <p class="text-muted">{{ $user->mo_ta ?? 'chưa cập nhật' }}</p>

                    <a href="{{ route('trangcanhan.edit') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="bi bi-pencil-square"></i> Chỉnh sửa hồ sơ
                    </a>

                    <!-- Thông tin cơ bản -->
                    <div class="mt-3 text-start">
                        <h6>Bài viết: {{ $user->baiviets_count ?? 0 }}</h6>
                        <h6>Theo dõi: {{ $user->followers_count ?? 0 }}</h6>
                        <h6>Đang theo dõi: {{ $user->following_count ?? 0 }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Danh sách nhà hàng -->
        <div class="col-lg-9 col-md-8">
            <h4 class="mb-3">Danh sách nhà hàng của bạn</h4>

            {{-- Include nhahang/card.blade.php --}}
            @if(isset($nhaHangs) && $nhaHangs->count() > 0)
                @include('nhahang.card', ['nhaHangs' => $nhaHangs])
            @else
                <div class="text-muted">Bạn chưa có nhà hàng nào.</div>
            @endif

            <!-- Danh sách đánh giá đã gửi -->
            @if(isset($danhGias) && $danhGias->count() > 0)
                <div class="mt-4">
                    <h4>Đánh giá đã gửi</h4>
                    <div class="list-group">
                        @include('danhgia.list', ['danhGias' => $danhGias])
                    </div>
                </div>
            @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-file-earmark-text" style="font-size:2.5rem;"></i>
                    <p class="mt-2">Chưa gửi đánh giá nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection