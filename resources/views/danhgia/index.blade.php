@extends('layout.header')

@section('title', 'Đánh giá bài viết')

@section('maincontent')
@php
    $currentUserId = session('ma_nguoi_dung');
@endphp

<div class="container py-4">
    <div class="row gx-4">

        {{-- ================= BÀI VIẾT (BÊN TRÁI) ================= --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    {{-- Người đăng bài --}}
                    <a href="{{ route('trangcanhan.index', optional($baiViet->nguoiDang)->ma_nguoi_dung) }}"
                       class="d-flex align-items-center mb-3 text-decoration-none text-dark">
                        <img src="{{ asset(optional($baiViet->nguoiDang)->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                             class="rounded-circle me-2" width="50" height="50" style="object-fit:cover;">
                        <div>
                            <strong>{{ optional($baiViet->nguoiDang)->ho_ten ?? 'Người dùng ẩn danh' }}</strong><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($baiViet->thoi_gian_tao)->diffForHumans() }}</small>
                        </div>
                    </a>

                    {{-- Nội dung --}}
                    <p class="card-text">{{ $baiViet->noi_dung }}</p>

                    {{-- Ảnh / Video --}}
                    @if($baiViet->anhBaiViets->count() > 0)
                        <div class="mb-3">
                            @foreach($baiViet->anhBaiViets as $media)
                                @php $ext = pathinfo($media->duong_dan_anh, PATHINFO_EXTENSION); @endphp
                                @if(in_array(strtolower($ext), ['mp4','webm','ogg']))
                                    <video class="w-100 mb-2" controls style="border-radius:8px;">
                                        <source src="{{ asset($media->duong_dan_anh) }}" type="video/{{ $ext }}">
                                    </video>
                                @else
                                    <img src="{{ asset($media->duong_dan_anh) }}"
                                         class="w-100 mb-2 rounded" style="object-fit:cover;">
                                @endif
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ================= DANH SÁCH ĐÁNH GIÁ & FORM (BÊN PHẢI) ================= --}}
        <div class="col-md-6" id="danhgia-section">

            {{-- DANH SÁCH ĐÁNH GIÁ --}}
            <div class="card shadow-sm mb-3" style="max-height:500px; overflow-y:auto;">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Danh sách đánh giá ({{ $danhGias->count() }})</h5>
                        <form method="GET" action="{{ route('danhgia.index', $baiViet->ma_bai_viet) }}" class="d-flex">
                            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Gần đây nhất</option>
                                <option value="5to1" {{ request('sort')=='5to1' ? 'selected' : '' }}>5 → 1 sao</option>
                                <option value="1to5" {{ request('sort')=='1to5' ? 'selected' : '' }}>1 → 5 sao</option>
                            </select>
                        </form>
                    </div>

                    @forelse($danhGias as $dg)
                        <div class="list-group-item mb-2 shadow-sm rounded">
                            <div class="d-flex align-items-start">

                                {{-- Avatar người đánh giá --}}
                                <a href="{{ route('trangcanhan.index', optional($dg->nguoiDung)->ma_nguoi_dung) }}"
                                   class="me-3 text-decoration-none">
                                    <img src="{{ asset(optional($dg->nguoiDung)->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                                         class="rounded-circle" width="50" height="50" style="object-fit:cover;">
                                </a>

                                <div style="width:100%;">
                                    {{-- Tên người đánh giá --}}
                                    <a href="{{ route('trangcanhan.index', optional($dg->nguoiDung)->ma_nguoi_dung) }}"
                                       class="text-decoration-none text-dark">
                                        <strong>{{ optional($dg->nguoiDung)->ho_ten ?? 'Người dùng' }}</strong>
                                    </a>
                                    <small class="text-muted d-block">{{ $dg->thoi_gian_tao->diffForHumans() }}</small>

                                    {{-- Star rating --}}
                                    <div class="text-warning mb-1">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $dg->diem_danh_gia ? '-fill' : '' }}"></i>
                                        @endfor
                                        <span class="ms-2">({{ $dg->diem_danh_gia }}/5)</span>
                                    </div>

                                    <p>{{ $dg->binh_luan }}</p>

                                    {{-- Ảnh đánh giá --}}
                                    @if ($dg->anhDanhGias->count() > 0)
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            @foreach($dg->anhDanhGias as $anh)
                                                <img src="{{ asset($anh->duong_dan_anh) }}" width="100" class="rounded">
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Chỉnh sửa / xóa nếu là người đánh giá --}}
                                    @if($currentUserId && $currentUserId == $dg->ma_nguoi_dung)
                                        <div class="mt-2">
                                            <a href="{{ route('danhgia.edit', $dg->ma_danh_gia) }}" class="btn btn-sm btn-warning">Sửa</a>
                                            <form action="{{ route('danhgia.destroy', $dg->ma_danh_gia) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">Chưa có đánh giá nào.</div>
                    @endforelse

                </div>
            </div>

            {{-- FORM VIẾT ĐÁNH GIÁ --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Viết đánh giá</h5>

                    @if(!$currentUserId)
                        <div class="alert alert-warning text-center">
                            Bạn cần <a href="{{ route('login') }}">đăng nhập</a> để viết đánh giá.
                        </div>
                    @else
                        @include('danhgia.create', ['baiViet' => $baiViet])
                    @endif

                </div>
            </div>

        </div>

    </div>
</div>
@endsection
