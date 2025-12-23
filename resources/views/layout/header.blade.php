<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodSocial')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Chỉ một chút CSS để Header trông cao cấp hơn */
        .navbar { backdrop-filter: blur(10px); background-color: rgba(255, 255, 255, 0.95) !important; }
        .nav-link-icon { font-size: 1.25rem; color: #6c757d; transition: color 0.2s; }
        .nav-link-icon:hover { color: #0d6efd; }
        .search-input { border-radius: 20px; background-color: #f8f9fa; border: none; }
        .search-input:focus { background-color: #fff; box-shadow: 0 0 0 0.25rem rgba(13,110,253,.07); }
    </style>
</head>
<body>

@php
    $currentUserId = session('ma_nguoi_dung');
    $soThongBaoChuaDoc = $currentUserId ? \App\Models\ThongBao::where('ma_nguoi_nhan', $currentUserId)->where('da_doc', 0)->count() : 0;
@endphp

<nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top border-bottom">
    <div class="container">
        {{-- LOGO --}}
        <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('home') }}">
            🍜 <span class="d-none d-sm-inline">FoodSocial</span>
        </a>

        {{-- Nút Toggle cho Mobile --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            {{-- THANH TÌM KIẾM --}}
            <form class="ms-lg-4 my-3 my-lg-0 flex-grow-1" style="max-width: 400px;" method="GET" action="{{ route('home') }}">
                <div class="input-group">
                    <input name="keyword" value="{{ request('keyword') }}" class="form-control search-input ps-3" 
                           placeholder="Tìm món ngon, nhà hàng...">
                    <button class="btn btn-link text-secondary position-absolute end-0 z-3" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                @if($currentUserId)
                    @php
                        $user = \App\Models\NguoiDung::find($currentUserId);
                        $avatar = $user->anh_dai_dien ? (Str::startsWith($user->anh_dai_dien, 'http') ? $user->anh_dai_dien : asset($user->anh_dai_dien)) : asset('uploads/anh_nguoi_dung/default.png');
                    @endphp

                    {{-- Link điều hướng chính dạng Icon --}}
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link nav-link-icon" title="Trang chủ">
                            <i class="bi bi-house"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('nhahang.index') }}" class="nav-link nav-link-icon" title="Khám phá nhà hàng">
                            <i class="bi bi-compass"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('luu_baiviet.index') }}" class="nav-link nav-link-icon" title="Đã lưu">
                            <i class="bi bi-bookmark"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tinnhan.index') }}" class="nav-link nav-link-icon" title="Tin nhắn">
                            <i class="bi bi-chat-left-text"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('thongbao.index') }}" class="nav-link nav-link-icon position-relative" title="Thông báo">
                            <i class="bi bi-bell"></i>
                            @if($soThongBaoChuaDoc > 0)
                                <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    {{ $soThongBaoChuaDoc > 9 ? '9+' : $soThongBaoChuaDoc }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item ms-lg-2 d-none d-lg-block border-start ps-3" style="height: 24px;"></li>

                    {{-- DROP_DOWN USER --}}
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ $avatar }}" class="rounded-circle border" width="32" height="32" style="object-fit:cover">
                            <span class="fw-semibold small d-lg-none d-xl-inline">{{ $user->ho_ten }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                            <li><h6 class="dropdown-header small text-muted">Tài khoản cá nhân</h6></li>
                            <li><a class="dropdown-item py-2" href="{{ route('trangcanhan.index', ['id'=>$currentUserId]) }}">
                                <i class="bi bi-person me-2"></i> Trang cá nhân
                            </a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('baiviet.create') }}">
                                <i class="bi bi-plus-square me-2 text-primary"></i> Đăng bài viết
                            </a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('nhahang.create') }}">
                                <i class="bi bi-shop me-2 text-success"></i> Đăng ký nhà hàng
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}">
                                <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                            </a></li>
                        </ul>
                    </li>

                @else
                    <li class="nav-item"><a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-dark fw-semibold">Đăng nhập</a></li>
                    <li class="nav-item"><a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Đăng ký</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @foreach (['success', 'error', 'warning'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="bi {{ $msg == 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }} me-2"></i>
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    @yield('maincontent')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>