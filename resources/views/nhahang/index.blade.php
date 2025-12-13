@extends('layout.header')

@section('title', 'Danh sách nhà hàng')

@section('maincontent')
<div class="container py-5">
    <h2 class="mb-4 text-center">Danh sách nhà hàng</h2>

    @if($nhaHangs->count() > 0)
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($nhaHangs as $nhaHang)
                <div class="col">
                    <div class="card h-100 shadow-sm card-hover">
                        <!-- Ảnh đại diện -->
                        @if($nhaHang->anh_dai_dien && file_exists(storage_path('app/public/' . $nhaHang->anh_dai_dien)))
                            <img src="{{ asset('storage/' . $nhaHang->anh_dai_dien) }}" 
                                 class="card-img-top" 
                                 alt="{{ $nhaHang->ten_nha_hang }}">
                        @else
                            <div class="text-center p-5 bg-light">
                                <i class="fas fa-utensils fa-3x text-secondary"></i>
                                <p class="mt-2 text-muted">Chưa có ảnh</p>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $nhaHang->ten_nha_hang }}</h5>
                            <p class="text-muted mb-1">{{ Str::limit($nhaHang->dia_chi, 60) }}</p>
                            <div class="mb-2">
                                @if($nhaHang->phanLoai)
                                    <span class="badge bg-primary me-1">{{ $nhaHang->phanLoai->ten_phan_loai }}</span>
                                @endif
                                @if($nhaHang->khuVuc)
                                    <span class="badge bg-success">{{ $nhaHang->khuVuc->ten_khu_vuc }}</span>
                                @endif
                            </div>
                            <p class="text-muted mb-0" style="font-size:0.85rem;">
                                Chủ sở hữu: {{ $nhaHang->chuSoHuu->ho_ten ?? 'Chưa xác định' }}
                            </p>
                        </div>

                        <div class="card-footer text-center bg-white">
                            <a href="{{ route('nhahang.show', $nhaHang->ma_nha_hang) }}" 
                               class="btn btn-outline-primary btn-sm w-75">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-muted py-5">Hiện chưa có nhà hàng nào.</p>
    @endif
</div>

<!-- Custom CSS -->
@push('styles')
<style>
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    .card-img-top {
        height: 200px;
        object-fit: cover;
    }
</style>
@endpush

<!-- Bootstrap & FontAwesome nếu chưa có trong header -->
@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush
@endsection
