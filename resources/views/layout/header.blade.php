<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodSocial')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand fw-bold" href="{{ route('home') }}">FoodSocial</a>

            {{-- Search --}}
            <form class="d-flex me-3">
                <input class="form-control" type="search" placeholder="Tìm kiếm nhà hàng, món ăn..." aria-label="Search">
                <button class="btn btn-outline-primary ms-2" type="submit">Tìm</button>
            </form>
            {{-- Link đến danh sách nhà hàng --}}
            <a href="{{ route('nhahang.index') }}" 
            class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-shop-window"></i>
                <span>Nhà hàng</span>
            </a>

            <div class="d-flex align-items-center gap-3">

                @if(session('user'))

                    {{-- NÚT TẠO BÀI VIẾT --}}
                    <a href="{{ route('baiviet.create') }}" 
                       class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                       title="Tạo bài viết">
                        <i class="bi bi-journal-plus text-white"></i>
                        <span class="text-white">Tạo bài viết</span>
                    </a>

                    {{-- NÚT THÊM NHÀ HÀNG --}}
                    <a href="{{ route('nhahang.create') }}" 
                       class="btn btn-success btn-sm d-flex align-items-center gap-1"
                       title="Thêm nhà hàng">
                        <i class="bi bi-shop text-white"></i>
                        <span class="text-white">Thêm nhà hàng</span>
                    </a>

                    {{-- Avatar + Dropdown --}}
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" 
                           data-bs-toggle="dropdown">

                            @php
                            // Ưu tiên ảnh nhà hàng
                            if (!empty($bv->nhaHang?->anh_dai_dien)) {
                                $avatar = asset('storage/' . $bv->nhaHang->anh_dai_dien);
                            }
                            // Nếu không có, dùng avatar người đăng
                            elseif (!empty($bv->nguoiDang?->avatar)) {
                                $avatar = asset('storage/' . $bv->nguoiDang->avatar);
                            }
                            // Không có gì → ảnh mặc định
                            else {
                                $avatar = 'https://via.placeholder.com/40';
                            }
                        @endphp

                        <img src="{{ $avatar }}" 
                            class="rounded-circle me-2" 
                            width="40" 
                            height="40"
                            style="object-fit: cover;">
                            <span>{{ session('user')->ho_ten ?? 'Người dùng' }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" 
                                   href="{{ route('trangcanhan.index', ['id' => session('user')->ma_nguoi_dung]) }}">
                                    Trang cá nhân
                                </a>
                            </li>

                            <li><a class="dropdown-item" href="{{ route('logout') }}">Đăng xuất</a></li>
                        </ul>
                    </div>

                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
                @endif

            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <div class="container mt-4">
        @yield('maincontent')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
