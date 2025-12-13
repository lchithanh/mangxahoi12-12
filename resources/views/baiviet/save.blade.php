@extends('layout.header')

@section('title', 'Bài viết đã lưu')

@section('maincontent')
<div class="container py-4">
    <h2 class="mb-4">Bài viết đã lưu</h2>
    <div class="row">
        @forelse($luuBaiViets as $luu)
            @php
                $bv = $luu->baiViet;
                $avatar = $bv->nhaHang?->anh_dai_dien ?? 'https://via.placeholder.com/40';
                $tenNhaHang = $bv->nhaHang?->ten_nha_hang ?? 'Nhà hàng';
                $maNhaHang = $bv->nhaHang?->ma_nha_hang;
            @endphp
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm h-100">
                    @if($bv->anhBaiViets->count() > 0)
                        <img src="{{ asset($bv->anhBaiViets->first()->duong_dan_anh) }}" class="card-img-top" style="height:300px; object-fit:cover;">
                    @else
                        <img src="https://via.placeholder.com/600x300?text=No+Media" class="card-img-top" style="height:300px; object-fit:cover;">
                    @endif

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
                                <a href="{{ $maNhaHang ? route('nhahang.show', $maNhaHang) : '#' }}" class="text-decoration-none text-dark">
                                    <h6 class="mb-0">{{ $tenNhaHang }}</h6>
                                </a>
                                <small class="text-muted">{{ $bv->thoi_gian_tao ? \Carbon\Carbon::parse($bv->thoi_gian_tao)->format('d/m/Y H:i') : '' }}</small>
                            </div>
                        </div>
                        <p class="card-text mb-3">{{ Str::limit($bv->noi_dung, 120) }}</p>
                        <div class="mt-auto d-flex justify-content-between">
                            <form action="{{ route('baiviet.save', $bv->ma_bai_viet) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Hủy lưu</button>
                            </form>
                            <a href="{{ route('baiviet.show', $bv->ma_bai_viet) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Bạn chưa lưu bài viết nào.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
