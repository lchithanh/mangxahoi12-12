<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $nhaHang->ten_nha_hang ?? 'Nhà hàng' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .header { 
            position: relative; text-align:center; color:#fff; 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                        url('{{ $nhaHang->anh_dai_dien ? asset("storage/".$nhaHang->anh_dai_dien) : "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4" }}') center/cover no-repeat;
            padding:50px 20px; border-radius:10px; margin-bottom:30px;
        }
        .header h1 { font-size:3rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.5); }
        .header p { font-size:1.3rem; font-style:italic; }
        .info-bar { display:flex; flex-wrap:wrap; justify-content:center; gap:15px; margin-top:20px; }
        .info-item { display:flex; align-items:center; gap:8px; background: rgba(0,0,0,0.5); padding:10px 15px; border-radius:8px; }
        .info-item i { color:#ffa726; }
        .gallery img, .menu-item img { width:100%; object-fit:cover; border-radius:8px; }
        .menu-item { display:flex; gap:15px; margin-bottom:15px; }
        .card-title { font-weight:bold; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header mb-4">
            <h1>{{ $nhaHang->ten_nha_hang ?? 'Nhà hàng' }}</h1>
            <p>{{ $nhaHang->mo_ta ?? '' }}</p>
            <div class="info-bar">
                <div class="info-item"><i class="fas fa-map-marker-alt"></i> {{ $nhaHang->dia_chi ?? 'Chưa có địa chỉ' }}</div>
                <div class="info-item"><i class="fas fa-phone"></i> {{ $nhaHang->so_dien_thoai ?? '(028) ...' }}</div>
                <div class="info-item"><i class="fas fa-star"></i> {{ $nhaHang->phanLoai->ten_phan_loai ?? 'Chưa phân loại' }}</div>
                <div class="info-item"><i class="fas fa-building"></i> {{ $nhaHang->khuVuc->ten_khu_vuc ?? 'Chưa xác định khu vực' }}</div>
                <div class="info-item"><i class="fas fa-user"></i> {{ $nhaHang->chuSoHuu->ho_ten ?? 'Chưa xác định' }}</div>
            </div>
        </div>

        <div class="row">
            <!-- Left column -->
            <div class="col-lg-4 mb-4">
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        @if(!empty($nhaHang->anh_dai_dien))
                            <img src="{{ asset('storage/' . $nhaHang->anh_dai_dien) }}" 
                                 class="rounded-circle mb-3" 
                                 alt="{{ $nhaHang->ten_nha_hang ?? 'Nhà hàng' }}" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <i class="fas fa-utensils fa-7x text-secondary mb-3"></i>
                        @endif
                        <h4 class="card-title">{{ $nhaHang->ten_nha_hang ?? 'Tên chưa có' }}</h4>
                        <p class="text-muted">{{ $nhaHang->phanLoai->ten_phan_loai ?? 'Chưa phân loại' }}</p>
                        <p class="text-muted">{{ $nhaHang->khuVuc->ten_khu_vuc ?? 'Chưa xác định khu vực' }}</p>
                        <p class="text-muted">Chủ sở hữu: {{ $nhaHang->chuSoHuu->ho_ten ?? 'Chưa xác định' }}</p>
                        <p class="text-muted mb-0">Địa chỉ: {{ $nhaHang->dia_chi ?? 'Chưa có địa chỉ' }}</p>
                        <p class="text-muted mb-0">Điện thoại: {{ $nhaHang->so_dien_thoai ?? '(028) ...' }}</p>
                        <p class="text-muted mb-0">Giờ mở cửa: {{ $nhaHang->gio_mo_cua ?? 'Chưa cập nhật' }}</p>
                       
                    </div>
                </div>

                <!-- Liên hệ -->
                <div class="card mb-4">
                    <div class="card-body contact">
                        <h5>Liên Hệ</h5>
                        <p><i class="fas fa-phone"></i> {{ $nhaHang->so_dien_thoai ?? '' }}</p>
                        <p><i class="fas fa-envelope"></i> {{ $nhaHang->email ?? '' }}</p>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $nhaHang->dia_chi ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-lg-8">
                <!-- Gallery -->
                @if(!empty($nhaHang->gallery) && count($nhaHang->gallery) > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Hình Ảnh</h5>
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

                <!-- Menu nổi bật -->
                @if(!empty($nhaHang->menu) && count($nhaHang->menu) > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Menu Nổi Bật</h5>
                        @foreach($nhaHang->menu as $m)
                        <div class="menu-item">
                            <img src="{{ asset('storage/'.$m->anh) }}" alt="{{ $m->ten_mon }}" style="width:100px;height:100px;">
                            <div>
                                <h6>{{ $m->ten_mon }}</h6>
                                <p>{{ $m->mo_ta }}</p>
                                <p class="text-danger fw-bold">{{ number_format($m->gia) }} VNĐ</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Bài viết -->
                <div class="card mb-4">
                   
                        {{-- resources/views/baiviet/list.blade.php --}}

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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
