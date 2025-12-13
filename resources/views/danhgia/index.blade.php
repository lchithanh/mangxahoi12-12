@extends('layout.header')

@section('title', 'Đánh giá bài viết')

@section('maincontent')
<div class="container py-4">

    {{-- ================= BÀI VIẾT ================= --}}
    <div class="card shadow-sm mb-4 mx-auto" style="max-width:900px;">
        <div class="card-body text-center">

            <h5 class="fw-bold mb-2">{{ $baiViet->nguoiDang->ho_ten ?? 'Người dùng ẩn danh' }}</h5>
            <small class="text-muted d-block mb-2">
                Đăng lúc {{ $baiViet->thoi_gian_tao->format('d/m/Y H:i') }}
            </small>

            <p>{{ $baiViet->noi_dung }}</p>

            {{-- Ảnh bài viết --}}
            @if($baiViet->anhBaiViets->count() > 0)
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                    @foreach($baiViet->anhBaiViets as $anh)
                        @php
                            $path = str_replace('storage/', '', $anh->duong_dan_anh);
                        @endphp
                        <img src="{{ asset('storage/' . $path) }}"
                             class="rounded shadow-sm border"
                             style="width:150px; height:150px; object-fit:cover;">
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <div class="row gx-4">

        {{-- ================= FORM VIẾT ĐÁNH GIÁ ================= --}}
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Viết đánh giá</h5>

                    @include('danhgia.create', [
                        'baiViet' => $baiViet,
                        'user' => session('user')
                    ])
                </div>
            </div>
        </div>

        {{-- ================= DANH SÁCH ĐÁNH GIÁ ================= --}}
        <div class="col-md-7">
            <div class="card shadow-sm mb-4" style="max-height:600px; overflow-y:auto;">
                <div class="card-body">

                    {{-- Tiêu đề + Bộ lọc --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            Danh sách đánh giá ({{ $danhGias->count() }})
                        </h5>

                        <form id="sortForm" method="GET" class="d-flex">
                            <select name="sort" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Gần đây nhất</option>
                                <option value="5to1" {{ request('sort')=='5to1' ? 'selected' : '' }}>5 → 1 sao</option>
                                <option value="1to5" {{ request('sort')=='1to5' ? 'selected' : '' }}>1 → 5 sao</option>
                            </select>
                        </form>
                    </div>

                    @php
    // Sắp xếp dựa trên danh sách gửi từ Controller
    if(request('sort') == '5to1'){
        $danhGias = $danhGias->sortByDesc('diem_danh_gia');
    }
    elseif(request('sort') == '1to5'){
        $danhGias = $danhGias->sortBy('diem_danh_gia');
    }
    else {
        $danhGias = $danhGias->sortByDesc('thoi_gian_tao');
    }
@endphp

@forelse($danhGias as $dg)
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body d-flex">

            {{-- Avatar --}}
            @php
                $avatar = $dg->nguoiDung->avatar ?? 'default.png';
            @endphp
            <img src="{{ asset('storage/avatar/' . $avatar) }}"
                 class="rounded-circle me-3"
                 width="45" height="45"
                 style="object-fit:cover;">

            <div style="width:100%;">

                <strong>{{ $dg->nguoiDung->ho_ten ?? 'Người dùng' }}</strong>

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

                @if ($dg->duong_dan_anh)
    @foreach (explode(',', $dg->duong_dan_anh) as $img)
        <img src="{{ asset($img) }}" width="120" class="rounded mb-2">
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
        </div>

    </div>
</div>
@endsection
