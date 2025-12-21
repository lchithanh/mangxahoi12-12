@extends('layout.header')

@section('title', 'Trang cá nhân - Chủ quán')

@section('maincontent')
<div class="container py-4">

    {{-- ===== THÔNG TIN CHỦ QUÁN ===== --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body d-flex align-items-center">
            <img
                src="{{ $user->anh_dai_dien
                        ? asset($user->anh_dai_dien)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->ho_ten) }}"
                class="rounded-circle me-4 border"
                width="100"
                height="100"
                alt="Avatar">

            <div>
                <h3 class="mb-1">{{ $user->ho_ten }}</h3>
                <p class="text-muted mb-3">{{ $user->mo_ta ?? 'Chưa có mô tả' }}</p>

                {{-- THỐNG KÊ --}}
                <div class="d-flex flex-wrap gap-3">
                    <div class="text-center">
                        <span class="fw-bold h5 d-block">{{ $user->baiviets_count }}</span>
                        <small class="text-muted">Bài viết</small>
                    </div>
                    

                    <a href="{{ route('trangcanhan.dangtheodoi', $user->ma_nguoi_dung) }}"
                       class="btn btn-outline-primary position-relative">
                        Đang theo dõi
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                            {{ $dangTheoDoiCount }}
                        </span>
                    </a>

                    <a href="{{ route('trangcanhan.theodoi', $user->ma_nguoi_dung) }}"
                       class="btn btn-outline-secondary position-relative">
                        Người theo dõi
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                            {{ $user->followers_count }}
                        </span>
                    </a>
                    {{-- Nút Setup --}}
        <a href="{{ route('trangcanhan.edit') }}" class="btn btn-warning">Setup trang cá nhân</a>

                </div>
                
            </div>
        </div>
    </div>

    {{-- ===== NHÀ HÀNG CỦA CHỦ QUÁN ===== --}}
    <h4 class="mb-3">Nhà hàng của bạn</h4>
    @if($nhaHangs->count())
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
            @foreach($nhaHangs as $nh)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img
                            src="{{ $nh->anh_dai_dien
                                    ? asset($nh->anh_dai_dien)
                                    : 'https://via.placeholder.com/300x200' }}"
                            class="card-img-top"
                            alt="{{ $nh->ten_nha_hang }}">

                        <div class="card-body">
                            <h5 class="card-title">{{ $nh->ten_nha_hang }}</h5>
                            <p class="card-text text-muted">
                                {{ Str::limit($nh->mo_ta, 100) }}
                            </p>
                            <a href="{{ route('nhahang.show', $nh->ma_nha_hang) }}"
                               class="btn btn-sm btn-outline-primary">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted mb-5">Bạn chưa có nhà hàng nào.</p>
    @endif

    {{-- ===== ĐÁNH GIÁ CỦA CHỦ QUÁN ===== --}}
    <h4 class="mb-3">Đánh giá của bạn</h4>
    @if($danhGias->count())
        <div class="list-group shadow-sm">
            @include('danhgia.list', ['danhGias' => $danhGias])
        </div>
    @else
        <p class="text-muted">Bạn chưa có đánh giá nào.</p>
    @endif

</div>
@endsection