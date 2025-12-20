@extends('layout.header')

@section('title', $nhaHang->ten_nha_hang ?? 'Nhà hàng')

@section('maincontent')
@php
    $maNguoiDung = session('ma_nguoi_dung'); // ID người dùng hiện tại
    $vaiTro      = session('user_role');     // Vai trò người dùng hiện tại
@endphp

<div class="container py-4">

    <!-- Header nhà hàng -->
    <div class="header mb-4"
        style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
               url('{{ $nhaHang->anh_dai_dien ? asset($nhaHang->anh_dai_dien) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4' }}') center/cover no-repeat;
               border-radius:10px; padding:50px 20px; color:#fff; text-align:center;">
        <h1>{{ $nhaHang->ten_nha_hang ?? 'Nhà hàng' }}</h1>
        <p>{{ $nhaHang->mo_ta ?? '' }}</p>

        <!-- Thông tin nhanh -->
        <div class="info-bar mt-3">
            <div class="info-item"><i class="fas fa-map-marker-alt"></i> {{ $nhaHang->dia_chi ?? 'Chưa có địa chỉ' }}</div>
            <div class="info-item"><i class="fas fa-phone"></i> {{ $nhaHang->so_dien_thoai ?? '(028) ...' }}</div>
            <div class="info-item"><i class="fas fa-star"></i> {{ optional($nhaHang->phanLoai)->ten_phan_loai ?? 'Chưa phân loại' }}</div>
            <div class="info-item"><i class="fas fa-building"></i> {{ optional($nhaHang->khuVuc)->ten_khu_vuc ?? 'Chưa xác định khu vực' }}</div>
            <div class="info-item"><i class="fas fa-user"></i> {{ optional($nhaHang->chuSoHuu)->ho_ten ?? 'Chưa xác định' }}</div>
        </div>

        <!-- Nút Edit / Xóa -->
        @if($maNguoiDung && $maNguoiDung === $nhaHang->ma_chu_so_huu)
            <div class="mt-3 d-flex justify-content-center gap-2">
                <a href="{{ route('nhahang.edit', $nhaHang->ma_nha_hang) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                </a>
                <form action="{{ route('nhahang.destroy', $nhaHang->ma_nha_hang) }}" method="POST"
                      onsubmit="return confirm('Bạn có chắc muốn xóa nhà hàng này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="row">
        <!-- Cột trái: thông tin & liên hệ -->
        <div class="col-lg-4 mb-4">
            <div class="card text-center mb-4">
                <div class="card-body">
                   <img src="{{ $nhaHang->anh_dai_dien ? asset($nhaHang->anh_dai_dien) : asset('images/no-image.png') }}"
                        alt="{{ $nhaHang->ten_nha_hang ?? 'Nhà hàng' }}"
                        class="img-fluid rounded mb-3"
                        style="max-height:300px; object-fit:cover;">

                    <h4 class="card-title">{{ $nhaHang->ten_nha_hang ?? 'Tên chưa có' }}</h4>
                    <p class="text-muted">{{ optional($nhaHang->phanLoai)->ten_phan_loai ?? 'Chưa phân loại' }}</p>
                    <p class="text-muted">{{ optional($nhaHang->khuVuc)->ten_khu_vuc ?? 'Chưa xác định khu vực' }}</p>
                    <p class="text-muted">Chủ sở hữu: {{ optional($nhaHang->chuSoHuu)->ho_ten ?? 'Chưa xác định' }}</p>
                    <p class="text-muted mb-0">Địa chỉ: {{ $nhaHang->dia_chi ?? 'Chưa có địa chỉ' }}</p>
                    <p class="text-muted mb-0">Điện thoại: {{ $nhaHang->so_dien_thoai ?? '(028) ...' }}</p>
                    <p class="text-muted mb-0">Giờ mở cửa: {{ $nhaHang->gio_mo_cua ?? 'Chưa cập nhật' }}</p>
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

        <!-- Cột phải: gallery & bài viết -->
        <div class="col-lg-8">
            <!-- Gallery hình ảnh -->
            @if(!empty($nhaHang->gallery) && count($nhaHang->gallery) > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Hình ảnh</h5>
                        <div class="row">
                            @foreach($nhaHang->gallery as $img)
                                <div class="col-4 mb-3">
                                    <img src="{{ asset('uploads/anh_nha_hang/'.$img->path) }}" alt="Ảnh" class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

@include('baiviet.list', ['baiviets' => $nhaHang->baiViets])        </div>
    </div>
</div>
@endsection