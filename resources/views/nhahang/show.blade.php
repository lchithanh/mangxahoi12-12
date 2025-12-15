@extends('layout.header')

@section('title', $nhaHang->ten_nha_hang ?? 'Nhà hàng')

@section('maincontent')
<div class="container py-4">

    <!-- Header nhà hàng -->
    <div class="header mb-4" 
        style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
               url('{{ $nhaHang->anh_dai_dien ? asset('storage/'.$nhaHang->anh_dai_dien) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4' }}') center/cover no-repeat; 
               border-radius:10px; padding:50px 20px; color:#fff; text-align:center;">
        <h1>{{ $nhaHang->ten_nha_hang ?? 'Nhà hàng' }}</h1>
        <p>{{ $nhaHang->mo_ta ?? '' }}</p>

        <!-- Thông tin nhanh -->
        <div class="info-bar mt-3 d-flex flex-wrap justify-content-center gap-3">
            <div class="info-item"><i class="fas fa-map-marker-alt"></i> {{ $nhaHang->dia_chi ?? 'Chưa có địa chỉ' }}</div>
            <div class="info-item"><i class="fas fa-phone"></i> {{ $nhaHang->so_dien_thoai ?? '(028) ...' }}</div>
            <div class="info-item"><i class="fas fa-star"></i> {{ $nhaHang->phanLoai->ten_phan_loai ?? 'Chưa phân loại' }}</div>
            <div class="info-item"><i class="fas fa-building"></i> {{ $nhaHang->khuVuc->ten_khu_vuc ?? 'Chưa xác định khu vực' }}</div>
            <div class="info-item"><i class="fas fa-user"></i> {{ $nhaHang->chuSoHuu->ho_ten ?? 'Chưa xác định' }}</div>
            
        </div>
        

        <!-- Nút Edit nhà hàng chỉ hiện khi người đăng nhập là chủ sở hữu -->
        @if(session('user') && session('user')->ma_nguoi_dung === $nhaHang->ma_chu_so_huu)
            <div class="mt-3">
                <a href="{{ route('nhahang.edit', $nhaHang->ma_nha_hang) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Chỉnh sửa nhà hàng
                </a>
            </div>
        @endif
    </div>

    <div class="row">
        <!-- Cột trái: thông tin & liên hệ -->
        <div class="col-lg-4 mb-4">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <!-- Ảnh đại diện nhà hàng -->
                    @if(!empty($nhaHang->anh_dai_dien))
                        <img src="{{ asset('storage/' . $nhaHang->anh_dai_dien) }}" 
                             class="rounded-circle mb-3" 
                             alt="{{ $nhaHang->ten_nha_hang }}" 
                             style="width:150px; height:150px; object-fit:cover;">
                    @else
                        <i class="fas fa-utensils fa-7x text-secondary mb-3"></i>
                    @endif

                    <!-- Thông tin cơ bản -->
                    <h4 class="card-title">{{ $nhaHang->ten_nha_hang ?? 'Tên chưa có' }}</h4>
                    <p class="text-muted">{{ $nhaHang->phanLoai->ten_phan_loai ?? 'Chưa phân loại' }}</p>
                    <p class="text-muted">{{ $nhaHang->khuVuc->ten_khu_vuc ?? 'Chưa xác định khu vực' }}</p>
                    <p class="text-muted">Chủ sở hữu: {{ $nhaHang->chuSoHuu->ho_ten ?? 'Chưa xác định' }}</p>
                    <p class="text-muted mb-0">Địa chỉ: {{ $nhaHang->dia_chi ?? 'Chưa có địa chỉ' }}</p>
                    <p class="text-muted mb-0">Điện thoại: {{ $nhaHang->so_dien_thoai ?? '(028) ...' }}</p>
                    <p class="text-muted mb-0">Giờ mở cửa: {{ $nhaHang->gio_mo_cua ?? 'Chưa cập nhật' }}</p>
                    

                    <!-- Nút theo dõi & nhắn tin -->
                    @if(session('user'))
                        <div class="d-flex gap-2 mt-3">
                            @php
                                $isFollowingNh = \App\Models\TheoDoi::where('ma_nguoi_dung', session('user')->ma_nguoi_dung)
                                    ->where('ma_nha_hang', $nhaHang->ma_nha_hang)
                                    ->exists();
                            @endphp

                            @if($isFollowingNh)
                                <a href="{{ route('follow.nhahang', $nhaHang->ma_nha_hang) }}" class="btn btn-outline-danger btn-sm flex-fill">
                                    <i class="bi bi-person-dash"></i> Hủy theo dõi
                                </a>
                            @else
                                <a href="{{ route('follow.nhahang', $nhaHang->ma_nha_hang) }}" class="btn btn-primary btn-sm flex-fill">
                                    <i class="bi bi-person-plus"></i> Theo dõi
                                </a>
                            @endif

                            @if($nhaHang->chuSoHuu)
                                <a href="{{ route('tinnhan.show', $nhaHang->ma_nha_hang) }}" class="btn btn-success btn-sm flex-fill">
                                    Nhắn tin
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mt-3">Đăng nhập để theo dõi và nhắn tin cho nhà hàng.</p>
                    @endif
                </div>
            </div>

            <!-- Thông tin liên hệ chi tiết -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Liên hệ</h5>
                    <p><i class="fas fa-phone"></i> {{ $nhaHang->so_dien_thoai ?? '' }}</p>
                    <p><i class="fas fa-envelope"></i> {{ $nhaHang->email ?? '' }}</p>
                    <p><i class="fas fa-map-marker-alt"></i> {{ $nhaHang->dia_chi ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Cột phải: gallery, menu, bài viết -->
        <div class="col-lg-8">

            <!-- Gallery hình ảnh -->
            @if(!empty($nhaHang->gallery) && count($nhaHang->gallery) > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Hình ảnh</h5>
                        <div class="row">
                            @foreach($nhaHang->gallery as $img)
                                <div class="col-4 mb-3">
                                    <img src="{{ asset('storage/'.$img->path) }}" alt="Ảnh" class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bài viết -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Bài Viết</h5>
                    @include('baiviet.list', ['baiviets' => $nhaHang->baiViets])
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body { background-color: #f8f9fa; }

    /* Header nhà hàng */
    .header h1 { font-size:3rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.5); }
    .header p { font-size:1.3rem; font-style:italic; }

    /* Thanh thông tin nhanh */
    .info-bar { display:flex; flex-wrap:wrap; justify-content:center; gap:15px; margin-top:20px; }
    .info-item { display:flex; align-items:center; gap:8px; background: rgba(0,0,0,0.5); padding:10px 15px; border-radius:8px; color:#fff; }

    /* Gallery & Menu */
    .gallery img, .menu-item img { object-fit:cover; border-radius:8px; }
    .menu-item { display:flex; gap:15px; margin-bottom:15px; }
</style>
@endpush
