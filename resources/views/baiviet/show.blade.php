@extends('layout.header')

@section('title', 'Chi tiết bài viết')

@section('maincontent')
<div class="container my-4">
    <h1 class="mb-3">Chi tiết bài viết</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Người đăng --}}
            <h5 class="card-title">
                {{ $baiViet->nguoiDang->ho_ten ?? 'Người dùng ẩn danh' }}
            </h5>

            {{-- Nội dung --}}
            <p class="card-text">{{ $baiViet->noi_dung }}</p>

            {{-- Ảnh bài viết --}}
            @if($baiViet->anhBaiViets->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($baiViet->anhBaiViets as $anh)
                                @php
                                    $path = $anh->duong_dan_anh;
                                    if (str_starts_with($path, 'storage/')) {
                                        $path = substr($path, 8);
                                    }
                                @endphp
                                <img src="{{ asset('storage/' . $path) }}"
                                     alt="Ảnh bài viết"
                                     class="rounded"
                                     style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Thời gian --}}
            <small class="text-muted">Đăng lúc: {{ $baiViet->thoi_gian_tao }}</small>

            <div class="mt-3 d-flex justify-content-between align-items-center">

                <div class="d-flex gap-2">

                    {{-- Like / Unlike --}}
                    @php
                        $daLike = $baiViet->luotThichs->contains('nguoi_dung_id', $user->ma_nguoi_dung ?? 0);
                    @endphp

                    <form action="{{ route('baiviet.like', $baiViet->ma_bai_viet) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $daLike ? 'btn-danger' : 'btn-outline-danger' }}">
                            {{ $daLike ? '❌ Unlike' : '👍 Like' }}
                            <span>{{ $baiViet->luotThichs->count() }}</span>
                        </button>
                    </form>

                    {{-- NÚT ĐÁNH GIÁ (Kết hợp Xem + Viết) --}}
                    <a href="{{ route('danhgia.index', ['ma_bai_viet' => $baiViet->ma_bai_viet]) }}"
                       class="btn btn-primary">
                        💬 Đánh giá
                        <span class="badge bg-light text-dark">
                            {{ $baiViet->danhGias()->count() }}
                        </span>
                    </a>

                </div>

            </div>

            {{-- ============================
                 HIỂN THỊ DANH SÁCH ĐÁNH GIÁ NGAY BÊN DƯỚI (optional)
            ============================= --}}
            @if($baiViet->danhGias && $baiViet->danhGias->count() > 0)
                <div class="mt-3">
                    @foreach($baiViet->danhGias as $danhGia)
                        <div class="d-flex mb-2">
                            <img src="{{ asset('storage/' . ($danhGia->nguoiDung->avatar ?? 'default.png')) }}"
                                 class="rounded-circle me-2"
                                 width="40" height="40" style="object-fit:cover;">
                            <div>
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
@endsection
