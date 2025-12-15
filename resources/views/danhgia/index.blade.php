@extends('layout.header')

@section('title', 'Đánh giá bài viết')

@section('maincontent')
<div class="container py-4">
    <div class="row gx-4">

        {{-- ================= BÀI VIẾT (BÊN TRÁI) ================= --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    {{-- Header bài viết --}}
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset(optional($baiViet->nguoiDang)->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                             class="rounded-circle me-2" width="50" height="50" style="object-fit:cover;">
                        <div>
                            <strong>{{ optional($baiViet->nguoiDang)->ho_ten ?? 'Người dùng ẩn danh' }}</strong>
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

                </div>
            </div>
        </div>

        {{-- ================= DANH SÁCH ĐÁNH GIÁ & FORM (BÊN PHẢI) ================= --}}
        <div class="col-md-6" id="danhgia-section">

            {{-- DANH SÁCH ĐÁNH GIÁ --}}
            <div class="card shadow-sm mb-3" style="max-height:500px; overflow-y:auto;">
                <div class="card-body">

                    {{-- Tiêu đề + bộ lọc --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Danh sách đánh giá ({{ $danhGias->count() }})</h5>

                        <form id="sortForm" method="GET" class="d-flex">
                            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Gần đây nhất</option>
                                <option value="5to1" {{ request('sort')=='5to1' ? 'selected' : '' }}>5 → 1 sao</option>
                                <option value="1to5" {{ request('sort')=='1to5' ? 'selected' : '' }}>1 → 5 sao</option>
                            </select>
                        </form>
                    </div>

                    @forelse($danhGias as $dg)
                        <div class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-body d-flex">
                                {{-- Avatar --}}
                                <img src="{{ asset(optional($dg->nguoiDung)->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                                     class="rounded-circle me-3"
                                     width="45" height="45"
                                     style="object-fit:cover;">
                                <div style="width:100%;">
                                    <strong>{{ optional($dg->nguoiDung)->ho_ten ?? 'Người dùng' }}</strong>
                                    <small class="text-muted d-block">
                                        {{ $dg->thoi_gian_tao->diffForHumans() }}
                                    </small>

                                    {{-- Số sao --}}
                                    <div class="text-warning mb-1">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $dg->diem_danh_gia ? '-fill' : '' }}"></i>
                                        @endfor
                                        <span class="text-dark ms-2">({{ $dg->diem_danh_gia }}/5)</span>
                                    </div>

                                    {{-- Nội dung --}}
                                    <p class="mb-2">{{ $dg->binh_luan }}</p>

                                    {{-- Ảnh đánh giá --}}
                                    @if ($dg->duong_dan_anh)
                                        @foreach (explode(',', $dg->duong_dan_anh) as $img)
                                            <img src="{{ asset($img) }}" width="120" class="rounded mb-2 me-2">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info rounded-3 shadow-sm">
                            Chưa có đánh giá nào cho bài viết này.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- FORM VIẾT ĐÁNH GIÁ --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Viết đánh giá</h5>

                    @include('danhgia.create', [
                        'baiViet' => $baiViet,
                        'user' => session('user')
                    ])
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
