@extends('layout.header')

@section('title', 'Chi tiết bài viết')

@section('maincontent')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- CARD BÀI VIẾT --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    {{-- Header: Người đăng + thời gian --}}
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset($baiViet->nguoiDang->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                             class="rounded-circle me-2" width="50" height="50" style="object-fit:cover;">
                        <div>
                            <strong>{{ $baiViet->nguoiDang->ho_ten ?? 'Người dùng ẩn danh' }}</strong>
                            <br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($baiViet->thoi_gian_tao)->diffForHumans() }}</small>
                        </div>
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

                    {{-- Nút Like & Đánh giá --}}
                    <div class="d-flex justify-content-start gap-2 border-top pt-2 mt-2">
                        @php
                            $daLike = $baiViet->luotThichs->contains('nguoi_dung_id', $user->ma_nguoi_dung ?? 0);
                        @endphp

                        <form action="{{ route('baiviet.like', $baiViet->ma_bai_viet) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $daLike ? 'btn-danger' : 'btn-outline-danger' }}">
                                {{ $daLike ? '❌ Unlike' : '👍 Like' }} <span>{{ $baiViet->luotThichs->count() }}</span>
                            </button>
                        </form>

                        <a href="{{ route('danhgia.index', ['ma_bai_viet' => $baiViet->ma_bai_viet]) }}"
                           class="btn btn-primary">
                            💬 Đánh giá <span class="badge bg-light text-dark">{{ $baiViet->danhGias()->count() }}</span>
                        </a>
                    </div>

                    {{-- Danh sách đánh giá --}}
                    @if($baiViet->danhGias && $baiViet->danhGias->count() > 0)
                        <div class="mt-3 border-top pt-2">
                            @foreach($baiViet->danhGias as $danhGia)
                                <div class="d-flex mb-2">
                                    <img src="{{ asset($danhGia->nguoiDung->avatar ?? 'uploads/anh_nguoi_dung/default.png') }}"
                                         class="rounded-circle me-2"
                                         width="40" height="40" style="object-fit:cover;">
                                    <div class="flex-grow-1">
                                        <strong>{{ $danhGia->nguoiDung->ho_ten ?? 'Người dùng' }}</strong>
                                        <small class="text-muted d-block">
                                            {{ \Carbon\Carbon::parse($danhGia->thoi_gian_tao)->diffForHumans() }}
                                        </small>
                                        <div>{{ $danhGia->noi_dung }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
