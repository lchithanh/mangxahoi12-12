@extends('layout.header')

@section('title', 'Bài viết đã lưu')

@section('maincontent')
<div class="container py-4">
    <h2 class="mb-4">Bài viết đã lưu</h2>

    @if($luuBaiViets->isEmpty())
        <div class="text-center py-5">
            <p class="text-muted">Bạn chưa lưu bài viết nào.</p>
        </div>
    @else
        <div class="row">
            @foreach($luuBaiViets as $luu)
                @php
                    $bv = $luu->baiViet;
                    $avatar = $bv->nhaHang?->anh_dai_dien ?? $bv->nguoiDang?->anh_dai_dien ?? 'https://via.placeholder.com/40';
                    $tenNhaHang = $bv->nhaHang?->ten_nha_hang ?? 'Nhà hàng';
                    $maNhaHang = $bv->nhaHang?->ma_nha_hang;
                @endphp

                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm h-100">

                        {{-- Ảnh/Video --}}
                        @if($bv->anhBaiViets && $bv->anhBaiViets->count() > 0)
                            <div id="carouselBaiViet{{ $bv->ma_bai_viet }}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner" style="height: 250px;">
                                    @foreach($bv->anhBaiViets as $index => $media)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            @php $ext = pathinfo($media->duong_dan_anh, PATHINFO_EXTENSION); @endphp
                                            @if(in_array(strtolower($ext), ['mp4','webm','ogg']))
                                                <video class="d-block w-100" controls style="height:250px; object-fit:cover;">
                                                    <source src="{{ asset($media->duong_dan_anh) }}" type="video/{{ $ext }}">
                                                    Trình duyệt của bạn không hỗ trợ video.
                                                </video>
                                            @else
                                                <img src="{{ asset($media->duong_dan_anh) }}" class="d-block w-100" style="height:250px; object-fit:cover;">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if($bv->anhBaiViets->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBaiViet{{ $bv->ma_bai_viet }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselBaiViet{{ $bv->ma_bai_viet }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>
                        @else
                            <img src="https://via.placeholder.com/600x250?text=No+Media" 
                                 class="card-img-top" style="height:250px; object-fit:cover;">
                        @endif

                        {{-- Body bài viết --}}
                        <div class="card-body d-flex flex-column mt-2">
                            <div class="d-flex align-items-center mb-2">
                                @if($maNhaHang)
                                    <a href="{{ route('nhahang.show', $maNhaHang) }}">
                                        <img src="{{ asset('storage/'.$avatar) }}" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover;">
                                    </a>
                                @else
                                    <img src="{{ $avatar }}" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover;">
                                @endif
                                <div>
                                    @if($maNhaHang)
                                        <a href="{{ route('nhahang.show', $maNhaHang) }}" class="text-decoration-none text-dark">
                                            <h6 class="mb-0">{{ $tenNhaHang }}</h6>
                                        </a>
                                    @else
                                        <h6 class="mb-0">{{ $tenNhaHang }}</h6>
                                    @endif
                                    <small class="text-muted">{{ $bv->thoi_gian_tao ? \Carbon\Carbon::parse($bv->thoi_gian_tao)->format('d/m/Y H:i') : '' }}</small>
                                </div>
                            </div>

                            <p class="card-text mb-3">{{ Str::limit($bv->noi_dung ?? '', 120) }}</p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <a href="{{ route('baiviet.show', $bv->ma_bai_viet) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>

                                {{-- Nút xóa khỏi danh sách lưu --}}
                                <form action="{{ route('baiviet.save', $bv->ma_bai_viet) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Bỏ lưu</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            @endforeach
        </div>
    @endif
</div>
@endsection
