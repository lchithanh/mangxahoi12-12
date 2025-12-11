@extends('layout.header')

@section('title', 'Đánh giá bài viết')

@section('maincontent')
<div class="container py-4">

    {{-- ================= BÀI VIẾT ================= --}}
    <div class="card shadow-sm mb-4 mx-auto" style="max-width:900px;">
        <div class="card-body text-center">
            <h5 class="fw-bold mb-2">{{ $baiViet->nguoiDang->ho_ten ?? 'Người dùng ẩn danh' }}</h5>
            <small class="text-muted d-block mb-2">
                Đăng lúc: {{ date('d/m/Y H:i', strtotime($baiViet->thoi_gian_tao)) }}
            </small>

            <p>{{ $baiViet->noi_dung }}</p>

            {{-- Ảnh bài viết --}}
            @if($baiViet->anhBaiViets->count() > 0)
    <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
        @foreach($baiViet->anhBaiViets as $anh)
            @php
                $path = $anh->duong_dan_anh;
                if (str_starts_with($path, 'storage/')) {
                    $path = substr($path, 8); // bỏ 'storage/' nếu có
                }
            @endphp
            <img src="{{ asset('storage/' . $path) }}"
                 class="rounded shadow-sm border"
                 style="width:150px; height:150px; object-fit:cover;">
        @endforeach
    </div>
@endif

        </div>
    </div>

    {{-- ================= LƯỚI 2 CỘT ================= --}}
    <div class="row gx-4">

        {{-- ---------- CỘT TRÁI: Viết đánh giá ---------- --}}
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Viết đánh giá</h5>

                    @include('danhgia.create', ['baiViet' => $baiViet, 'user' => session('user')])
                </div>
            </div>
        </div>

        {{-- ---------- CỘT PHẢI: Danh sách đánh giá ---------- --}}
        <div class="col-md-7">
            <div class="card shadow-sm mb-4" style="max-height:600px; overflow-y:auto;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Danh sách đánh giá ({{ $baiViet->danhGias->count() }})</h5>

                        {{-- Chọn lọc hiển thị --}}
                        <form id="sortForm" method="GET" class="d-flex gap-2">
                            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Gần đây nhất</option>
                                <option value="5to1" {{ request('sort')=='5to1' ? 'selected' : '' }}>5→1 sao</option>
                                <option value="1to5" {{ request('sort')=='1to5' ? 'selected' : '' }}>1→5 sao</option>
                            </select>
                        </form>
                    </div>

                    @php
                        $sortedDanhGias = $baiViet->danhGias;
                        if(request('sort') == '5to1'){
                            $sortedDanhGias = $sortedDanhGias->sortByDesc('diem_danh_gia');
                        } elseif(request('sort') == '1to5'){
                            $sortedDanhGias = $sortedDanhGias->sortBy('diem_danh_gia');
                        } else {
                            $sortedDanhGias = $sortedDanhGias->sortByDesc('thoi_gian_tao');
                        }
                    @endphp

                    @forelse($sortedDanhGias as $danhGia)
                        <div class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-body d-flex">
                                {{-- Avatar --}}
                                @php
    $avatar = $danhGia->nguoiDung->avatar ?? 'default.png';
@endphp

<img src="{{ asset('storage/avatar/' . $avatar) }}"
     class="rounded-circle me-2"
     width="40" height="40"
     style="object-fit:cover;">
                                <div>
                                    <strong>{{ $danhGia->nguoiDung->ho_ten ?? 'Người dùng' }}</strong>
                                    <small class="text-muted d-block">
                                        {{ \Carbon\Carbon::parse($danhGia->thoi_gian_tao)->diffForHumans() }}
                                    </small>

                                    {{-- Điểm sao --}}
                                    <div class="text-warning mb-1">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $danhGia->diem_danh_gia ? '-fill' : '' }} me-1"></i>
                                        @endfor
                                        <span class="text-dark ms-2">({{ $danhGia->diem_danh_gia }}/5)</span>
                                    </div>

                                    {{-- Ảnh đánh giá --}}
                                    @if($danhGia->duong_dan_anh)
                                        @php
                                            $images = explode(',', $danhGia->duong_dan_anh);
                                        @endphp
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach($images as $img)
                                                <img src="{{ asset('storage/' . $img) }}"
                                                     class="rounded shadow-sm border"
                                                     style="width:100px; height:100px; object-fit:cover;">
                                            @endforeach
                                        </div>
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
        </div>

    </div>
</div>
@endsection
