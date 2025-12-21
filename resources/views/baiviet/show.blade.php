@extends('layout.header')

@section('title', 'Chi tiết bài viết')

@section('maincontent')
@php
    // Lấy thông tin user hiện tại từ session
    $currentUserId   = session('ma_nguoi_dung'); // ID người dùng đăng nhập
    $currentUserRole = session('user_role');     // 'chu_quan', 'nhahang', 'user'

    // Kiểm tra xem người đăng bài có phải là chủ quản không
    $isOwner = $currentUserId 
               && $currentUserRole === 'chu_quan' 
               && $currentUserId === $baiViet->ma_nguoi_dang;

    // Kiểm tra user đã like bài viết chưa
    $daLike = $currentUserId 
              && $baiViet->luotThichs->contains('nguoi_dung_id', $currentUserId);
@endphp

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- CARD BÀI VIẾT --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    {{-- Header: Người đăng + thời gian --}}
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('trangcanhan.index', $baiViet->nguoiDang->ma_nguoi_dung) }}" 
                        class="d-flex align-items-center text-decoration-none text-dark">
                            <img src="{{ asset($baiViet->nguoiDang->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                                class="rounded-circle me-2"
                                width="50" height="50"
                                style="object-fit:cover; cursor:pointer;">
                            <div>
                                <strong>{{ $baiViet->nguoiDang->ho_ten ?? 'Người dùng ẩn danh' }}</strong><br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($baiViet->thoi_gian_tao)->diffForHumans() }}</small>
                            </div>
                        </a>
                    </div>


                    {{-- Nội dung bài viết --}}
                    <p class="card-text">{{ $baiViet->noi_dung }}</p>

                    {{-- Ảnh / Video bài viết --}}
                    @if($baiViet->anhBaiViets->count() > 0)
                        <div class="mb-3">
                            @foreach($baiViet->anhBaiViets as $media)
                                @php
                                    $ext = pathinfo($media->duong_dan_anh, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($ext), ['mp4','webm','ogg']))
                                    <video class="w-100 mb-2" controls style="border-radius:8px;">
                                        <source src="{{ asset($media->duong_dan_anh) }}" type="video/{{ $ext }}">
                                    </video>
                                @else
                                    <img src="{{ asset($media->duong_dan_anh) }}"
                                         class="w-100 mb-2 rounded"
                                         style="object-fit:cover;">
                                @endif
                            @endforeach
                        </div>
                    @endif

                    {{-- Nút Like --}}
                    <div class="d-flex justify-content-start gap-2 border-top pt-2 mt-2">
                        @if($currentUserId)
                            <form action="{{ route('baiviet.like', $baiViet->ma_bai_viet) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $daLike ? 'btn-danger' : 'btn-outline-danger' }}">
                                    {{ $daLike ? '❌ Unlike' : '👍 Like' }} <span>{{ $baiViet->luotThichs->count() }}</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                👍 Like
                            </a>
                        @endif

                       <a href="{{ route('danhgia.index', $baiViet->ma_bai_viet) }}" class="btn btn-primary">
    💬 Đánh giá <span class="badge bg-light text-dark">{{ $baiViet->danhGias()->count() }}</span>
</a>
                    </div>

                   @foreach($baiViet->danhGias as $danhGia)
    <div class="d-flex mb-2">
        <a href="{{ route('trangcanhan.index', $danhGia->nguoiDung->ma_nguoi_dung) }}" 
           class="d-flex align-items-center text-decoration-none text-dark me-2">
            <img src="{{ asset($danhGia->nguoiDung->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                 class="rounded-circle"
                 width="40" height="40" style="object-fit:cover; cursor:pointer;">
        </a>
        <div class="flex-grow-1">
            <a href="{{ route('trangcanhan.index', $danhGia->nguoiDung->ma_nguoi_dung) }}" 
               style="text-decoration:none; color:inherit; cursor:pointer;">
                <strong>{{ $danhGia->nguoiDung->ho_ten ?? 'Người dùng' }}</strong>
            </a>
            <small class="text-muted d-block">
                {{ \Carbon\Carbon::parse($danhGia->thoi_gian_tao)->diffForHumans() }}
            </small>
            <div>{{ $danhGia->noi_dung }}</div>
        </div>
    </div>
@endforeach


                </div>
            </div>

        </div>
    </div>
</div>
@endsection
