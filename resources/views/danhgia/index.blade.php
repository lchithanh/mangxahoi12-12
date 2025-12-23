@extends('layout.header')

@section('title', 'Đánh giá bài viết')

@section('maincontent')
@php
    $currentUserId = session('ma_nguoi_dung');
@endphp

<div class="container py-5">
    <div class="row gx-lg-5">

        {{-- CỘT TRÁI: THÔNG TIN BÀI VIẾT (STICKY) --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden sticky-top" style="top: 20px;">
                @if($baiViet->anhBaiViets->count() > 0)
                    <div id="carouselBaiViet" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($baiViet->anhBaiViets as $index => $media)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @php $ext = pathinfo($media->duong_dan_anh, PATHINFO_EXTENSION); @endphp
                                    <div style="height: 350px; background: #f8f9fa;">
                                        @if(in_array(strtolower($ext), ['mp4','webm','ogg']))
                                            <video class="d-block w-100 h-100" controls style="object-fit:cover;">
                                                <source src="{{ asset($media->duong_dan_anh) }}" type="video/{{ $ext }}">
                                            </video>
                                        @else
                                            <img src="{{ asset($media->duong_dan_anh) }}" class="d-block w-100 h-100" style="object-fit:cover;">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($baiViet->anhBaiViets->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselBaiViet" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselBaiViet" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @endif

                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset(optional($baiViet->nguoiDang)->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                             class="rounded-circle me-3 border" width="45" height="45" style="object-fit:cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ optional($baiViet->nguoiDang)->ho_ten ?? 'Người dùng ẩn danh' }}</h6>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($baiViet->thoi_gian_tao)->diffForHumans() }}</small>
                        </div>
                    </div>
                    <p class="card-text text-secondary mb-4">{{ $baiViet->noi_dung }}</p>
                    
                    <a href="{{ route('baiviet.show', $baiViet->ma_bai_viet) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại bài viết
                    </a>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: FORM & DANH SÁCH ĐÁNH GIÁ --}}
        <div class="col-lg-6">
            {{-- Form tạo đánh giá --}}
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-chat-left-heart me-2 text-primary"></i>Viết đánh giá</h5>
                    @if(!$currentUserId)
                        <div class="text-center py-3 bg-light rounded-3 border">
                            <p class="mb-2 text-muted">Đăng nhập để chia sẻ cảm nhận của bạn</p>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-4">Đăng nhập ngay</a>
                        </div>
                    @else
                        @include('danhgia.create', ['baiViet' => $baiViet])
                    @endif
                </div>
            </div>

            {{-- Toolbar: Tổng số & Bộ lọc --}}
            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                <h5 class="fw-bold mb-0">Tất cả đánh giá ({{ $danhGias->count() }})</h5>
                <form method="GET" action="{{ route('danhgia.index', $baiViet->ma_bai_viet) }}">
                    <select name="sort" class="form-select form-select-sm border-0 shadow-sm rounded-pill px-3" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="5to1" {{ request('sort')=='5to1' ? 'selected' : '' }}>5 sao → 1 sao</option>
                        <option value="1to5" {{ request('sort')=='1to5' ? 'selected' : '' }}>1 sao → 5 sao</option>
                    </select>
                </form>
            </div>

            {{-- Include danh sách đánh giá từ file riêng --}}
            @include('danhgia.list')
            
        </div>
    </div>
</div>
@endsection