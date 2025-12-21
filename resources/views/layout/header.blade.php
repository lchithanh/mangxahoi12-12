<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodSocial')</title>

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

@php
    // Lấy thông tin user từ session
    $currentUserId   = session('ma_nguoi_dung'); // session id
    $currentUserRole = session('user_role');     // 'chu_quan', 'nhahang', 'user'

    // Thông báo
    $soThongBaoChuaDoc = 0;
    $thongBaosHeader = [];

    if($currentUserId) {
        // 5 thông báo mới nhất cho dropdown / header
        $thongBaosHeader = \App\Models\ThongBao::where('ma_nguoi_nhan', $currentUserId)
            ->orderByDesc('thoi_gian_tao')
            ->limit(5)
            ->get();

        // Số thông báo chưa đọc
        $soThongBaoChuaDoc = \App\Models\ThongBao::where('ma_nguoi_nhan', $currentUserId)
            ->where('da_doc', 0)
            ->count();
    }

@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">

        {{-- LOGO --}}
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            🍜 FoodSocial
        </a>

        {{-- SEARCH --}}
        <form class="d-none d-lg-flex ms-4 me-auto" method="GET" action="{{ route('home') }}">
            <input name="keyword"
                   value="{{ request('keyword') }}"
                   class="form-control"
                   placeholder="Tìm bài viết, nhà hàng, người đăng...">
            <button class="btn btn-outline-primary ms-2">Tìm</button>
        </form>

        @if($currentUserId)
            @php
                // Lấy thông tin user trực tiếp từ DB dựa trên session id
                $user = \App\Models\NguoiDung::find($currentUserId);
                $avatar = $user->anh_dai_dien
                    ? (Str::startsWith($user->anh_dai_dien, 'http') ? $user->anh_dai_dien : asset($user->anh_dai_dien))
                    : asset('uploads/anh_nguoi_dung/default.png');
                $hoTen = $user->ho_ten ?? 'Người dùng';
            @endphp

            {{-- ACTIONS --}}
            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('baiviet.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Bài viết
                </a>
                <a href="{{ route('nhahang.create') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Nhà hàng
                </a>
                <a href="{{ route('nhahang.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-shop"></i> Nhà hàng
                </a>
                <a href="{{ route('luu_baiviet.index') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-bookmark-fill"></i> Bài viết đã lưu
                </a>
                <a href="{{ route('tinnhan.index') }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-envelope-fill"></i>
                </a>
                {{-- ICON THÔNG BÁO --}}
                <a href="{{ route('thongbao.index') }}" class="btn btn-outline-dark btn-sm position-relative">
    <i class="bi bi-bell"></i>
    @if($soThongBaoChuaDoc > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $soThongBaoChuaDoc }}
        </span>
    @endif
</a>



                {{-- AVATAR --}}
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                       data-bs-toggle="dropdown">
                        <img src="{{ $avatar }}" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover">
                        <span class="fw-semibold">{{ $hoTen }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('trangcanhan.index', ['id'=>$currentUserId]) }}">
                                Trang cá nhân
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        @else
            {{-- Chưa đăng nhập --}}
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
            </div>
        @endif

    </div>
</nav>

<div class="container mt-4">
    {{-- Flash messages --}}
    @foreach (['success', 'error', 'warning'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
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
