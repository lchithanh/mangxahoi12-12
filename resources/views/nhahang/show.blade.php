@extends('layout.header')

@section('title', $nhaHang->ten_nha_hang ?? 'Chi tiết nhà hàng')

@section('maincontent')
@php
    $maNguoiDung = session('ma_nguoi_dung');
    $followersCount = $nhaHang->nguoiDungTheoDoi ? $nhaHang->nguoiDungTheoDoi->count() : 0;
@endphp

<div class="container py-4">
    {{-- 1. Hero Section --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="ratio ratio-21x9 bg-light">
            <img src="{{ $nhaHang->anh_dai_dien ? asset($nhaHang->anh_dai_dien) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4' }}" 
                 class="object-fit-cover" alt="Banner">
            <div class="position-absolute bottom-0 start-0 w-100 p-4 d-flex align-items-end" 
                 style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                <div class="text-white">
                    <span class="badge rounded-pill bg-warning text-dark mb-2 px-3 py-2 fw-bold">
                        <i class="bi bi-tag-fill me-1"></i> {{ optional($nhaHang->phanLoai)->ten_phan_loai }}
                    </span>
                    <h1 class="fw-bold display-6 mb-1">{{ $nhaHang->ten_nha_hang }}</h1>
                    <p class="mb-0 opacity-75 small"><i class="bi bi-geo-alt me-1"></i> {{ $nhaHang->dia_chi }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- 2. Sidebar trái --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 85px;">
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body p-4">
                        {{-- Thống kê --}}
                        <div class="d-flex justify-content-around text-center mb-4 py-3 bg-light rounded-3">
                            <div>
                                <h5 class="fw-bold mb-0 text-primary">{{ $followersCount }}</h5>
                                <small class="text-muted small">Theo dõi</small>
                            </div>
                            <div class="vr mx-2"></div>
                            <div>
                                <h5 class="fw-bold mb-0 text-primary">{{ $nhaHang->baiViets->count() }}</h5>
                                <small class="text-muted small">Bài viết</small>
                            </div>
                        </div>

                        {{-- Nút tương tác --}}
                        <div class="d-grid gap-2 mb-4">
                            @if($maNguoiDung && $maNguoiDung !== $nhaHang->ma_chu_so_huu)
                                @php $isFollowing = $nhaHang->nguoiDungTheoDoi?->contains('ma_nguoi_dung', $maNguoiDung); @endphp
                                <form action="{{ route('nhahang.toggleFollow', $nhaHang->ma_nha_hang) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-{{ $isFollowing ? 'light border' : 'primary' }} w-100 rounded-pill fw-bold">
                                        <i class="bi {{ $isFollowing ? 'bi-heartbreak' : 'bi-heart-fill' }} me-1"></i>
                                        {{ $isFollowing ? 'Bỏ theo dõi' : 'Theo dõi' }}
                                    </button>
                                </form>
                                @if($phongChat)
                        <a href="{{ route('tinnhan.phong.show', $phongChat->id) }}" class="btn btn-success w-100">
                            Nhắn tin
                        </a>
                    @else
                        <form action="{{ route('tinnhan.phong.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="loai_phong" value="user_nhahang">
                            <input type="hidden" name="ma_nguoi_dung_2" value="{{ $nhaHang->ma_chu_so_huu }}">
                            <input type="hidden" name="ma_nha_hang" value="{{ $nhaHang->ma_nha_hang }}">
                            <button type="submit" class="btn btn-success w-100">Nhắn tin</button>
                        </form>
                    @endif
                            @elseif($maNguoiDung === $nhaHang->ma_chu_so_huu)
                                <a href="{{ route('nhahang.edit', $nhaHang->ma_nha_hang) }}" class="btn btn-light border w-100 rounded-pill fw-bold">
                                    <i class="bi bi-gear-fill me-1"></i> Chỉnh sửa thông tin quán
                                </a>
                            @endif
                        </div>

                        {{-- Thông tin liên hệ --}}
                        <div class="small text-secondary">
                            <div class="d-flex mb-3"><i class="bi bi-telephone text-primary me-3"></i><span>{{ $nhaHang->so_dien_thoai }}</span></div>
                            <div class="d-flex mb-3"><i class="bi bi-clock text-warning me-3"></i><span>{{ $nhaHang->gio_mo_cua ?? '08:00 - 22:00' }}</span></div>
                            <div class="d-flex mb-3"><i class="bi bi-geo text-info me-3"></i><span>{{ optional($nhaHang->khuVuc)->ten_khu_vuc }}</span></div>
                            <div class="d-flex"><i class="bi bi-person text-success me-3"></i><span>Chủ: {{ optional($nhaHang->chuSoHuu)->ho_ten }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Nội dung chính --}}
        <div class="col-lg-8">
            {{-- Tabs --}}
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill gap-2" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-3 fw-bold py-2" data-bs-toggle="tab" data-bs-target="#tab-intro">
                                <i class="bi bi-shop me-2"></i>Giới thiệu
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-3 fw-bold py-2" data-bs-toggle="tab" data-bs-target="#tab-all-posts">
                                <i class="bi bi-grid-3x3 me-2"></i>Tất cả bài viết
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                {{-- TAB 1: Giới thiệu & Bài viết nổi bật --}}
                <div class="tab-pane fade show active" id="tab-intro">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        {{-- Mô tả --}}
                        <h5 class="fw-bold mb-3 border-start border-4 border-primary ps-3">Câu chuyện thương hiệu</h5>
                        <p class="text-muted lh-lg mb-4">{{ $nhaHang->mo_ta ?: 'Chưa có mô tả chi tiết.' }}</p>
                        
                        {{-- Gallery --}}
                        @if(!empty($nhaHang->gallery) && count($nhaHang->gallery) > 0)
                            <h6 class="fw-bold mb-3">Không gian & Món ăn</h6>
                            <div class="row g-2 mb-4">
                                @foreach($nhaHang->gallery as $img)
                                    <div class="col-4 col-md-3">
                                        <div class="ratio ratio-1x1">
                                            <img src="{{ asset('uploads/anh_nha_hang/'.$img->path) }}" class="rounded-3 object-fit-cover border" alt="Gallery">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <hr class="my-4 opacity-5">

                        {{-- HIỂN THỊ DANH SÁCH BÀI VIẾT (baiviet/list) --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Bài viết mới nhất</h5>
                            @if($maNguoiDung)
                                <a href="{{ route('baiviet.create', ['ma_nha_hang' => $nhaHang->ma_nha_hang]) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Tạo bài viết 
                                </a>
                            @endif
                        </div>

                        @include('baiviet.list', ['baiviets' => $nhaHang->baiViets])
                    </div>
                </div>

                {{-- TAB 2: Toàn bộ bài viết (Dạng đầy đủ) --}}
                <div class="tab-pane fade" id="tab-all-posts">
                    @include('baiviet.index', ['baiviets' => $nhaHang->baiViets])
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-pills .nav-link { color: #6c757d; }
    .nav-pills .nav-link.active { background-color: #0d6efd; color: white; }
    .object-fit-cover { object-fit: cover; }
</style>
@endsection