@extends('layout.header')

@section('title', 'Chi tiết bài viết')

@section('maincontent')
@php
    $currentUserId   = session('ma_nguoi_dung');
    $currentUserRole = session('user_role');

    // Kiểm tra quyền sở hữu bài viết
    $isOwner = $currentUserId 
               && $currentUserRole === 'chu_quan' 
               && $currentUserId === $baiViet->ma_nguoi_dang;

    // Kiểm tra trạng thái Like
    $daLike = $currentUserId 
              && $baiViet->luotThichs->contains('nguoi_dung_id', $currentUserId);
@endphp

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            {{-- CARD BÀI VIẾT --}}
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                
                {{-- MEDIA: Carousel ảnh/video --}}
                @if($baiViet->anhBaiViets->count() > 0)
                    <div id="carouselDetail" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($baiViet->anhBaiViets as $index => $media)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @php $ext = pathinfo($media->duong_dan_anh, PATHINFO_EXTENSION); @endphp
                                    <div style="height: 450px; background-color: #000;">
                                        @if(in_array(strtolower($ext), ['mp4','webm','ogg']))
                                            <video class="d-block w-100 h-100" controls style="object-fit:contain;">
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
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetail" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselDetail" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @endif

                <div class="card-body p-4">
                    {{-- Header: Người đăng --}}
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('trangcanhan.index', $baiViet->nguoiDang->ma_nguoi_dung) }}" class="text-decoration-none">
                            <img src="{{ asset($baiViet->nguoiDang->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                                 class="rounded-circle me-3 border"
                                 width="50" height="50"
                                 style="object-fit:cover;">
                        </a>
                        <div>
                            <a href="{{ route('trangcanhan.index', $baiViet->nguoiDang->ma_nguoi_dung) }}" class="text-decoration-none text-dark fw-bold">
                                <h6 class="mb-0">{{ $baiViet->nguoiDang->ho_ten ?? 'Người dùng ẩn danh' }}</h6>
                            </a>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($baiViet->thoi_gian_tao)->diffForHumans() }}
                            </small>
                        </div>
                    </div>

                    {{-- Nội dung bài viết --}}
                    <div class="card-text mb-4 text-secondary" style="line-height: 1.6; font-size: 1.1rem;">
                        {{ $baiViet->noi_dung }}
                    </div>

                    {{-- Nút hành động --}}
                    <div class="pt-3 border-top d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex gap-2">
                            @if($currentUserId)
                                <form action="{{ route('baiviet.like', $baiViet->ma_bai_viet) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm rounded-pill px-4 {{ $daLike ? 'btn-danger' : 'btn-outline-danger' }}">
                                        {{ $daLike ? '❤️ Unlike' : '👍 Like' }} <span class="badge ms-1">{{ $baiViet->luotThichs->count() }}</span>
                                    </button>
                                </form>
                                                       

                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">👍 Like</a>
                            @endif

                            @if($isOwner)
                                <a href="{{ route('baiviet.edit', $baiViet->ma_bai_viet) }}" class="btn btn-sm btn-outline-warning rounded-pill px-4">
                                    <i class="bi bi-pencil-square me-1"></i> Sửa
                                </a>
                            @endif
                        </div>
                        
                        <div class="text-muted fw-bold">
                            <a href="{{ route('danhgia.index', $baiViet->ma_bai_viet) }}" class="btn btn-primary">
                                <i class="bi bi-chat-left-text me-1"></i> Đánh giá <span class="badge bg-light text-dark">{{ $baiViet->danhGias()->count() }}</span>
                            </a>
                        </div>
                    </div>

                    {{-- PHẦN ĐÁNH GIÁ (BÌNH LUẬN) --}}
                    <h6 class="fw-bold mb-3">Tất cả đánh giá</h6>
                    @forelse($baiViet->danhGias as $danhGia)
                        <div class="d-flex mb-3 p-3 rounded-3 bg-light border-0 shadow-sm">
                            <a href="{{ route('trangcanhan.index', $danhGia->nguoiDung->ma_nguoi_dung) }}" class="me-3">
                                <img src="{{ asset($danhGia->nguoiDung->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                                     class="rounded-circle border"
                                     width="40" height="40" style="object-fit:cover;">
                            </a>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="text-dark">{{ $danhGia->nguoiDung->ho_ten ?? 'Người dùng' }}</strong>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($danhGia->thoi_gian_tao)->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="text-secondary mt-1 small">{{ $danhGia->noi_dung }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small italic">Chưa có đánh giá nào cho bài viết này.</p>
                    @endforelse

                </div>
            </div> {{-- End Card --}}

        </div>
    </div>
</div>
@endsection