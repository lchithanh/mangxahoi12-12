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
    use App\Models\ThongBao;
    use Illuminate\Support\Str;

    $user = session('user');

    if ($user) {
        $soThongBaoChuaDoc = ThongBao::where('ma_nguoi_nhan', $user->ma_nguoi_dung)
            ->where('da_doc', 0)
            ->count();

        $thongBaos = ThongBao::where('ma_nguoi_nhan', $user->ma_nguoi_dung)
            ->orderByDesc('thoi_gian_tao')
            ->limit(5)
            ->get();
    }
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">

        {{-- LOGO --}}
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            🍜 FoodSocial
        </a>

        {{-- SEARCH --}}
        <form class="d-none d-lg-flex ms-4 me-auto">
            <input class="form-control" type="search" placeholder="Tìm nhà hàng, món ăn...">
            <button class="btn btn-outline-primary ms-2">Tìm</button>
        </form>

        <div class="d-flex align-items-center gap-2">

        @if($user)

            {{-- ACTION --}}
            <a href="{{ route('baiviet.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Bài viết
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
    
            {{-- 🔔 THÔNG BÁO --}}
            <div class="dropdown">
                <button class="btn btn-outline-secondary position-relative"
                        data-bs-toggle="dropdown">
                    <i class="bi bi-bell-fill"></i>

                    @if($soThongBaoChuaDoc > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $soThongBaoChuaDoc }}
                        </span>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow"
                    style="width:320px; max-height:400px; overflow-y:auto;">

                    <li class="dropdown-header fw-bold">Thông báo</li>

                    @forelse($thongBaos as $tb)
                        <li>
                            <a href="{{ route('thongbao.index') }}"
                               class="dropdown-item {{ $tb->da_doc ? '' : 'fw-bold' }}">
                                <div>{{ $tb->noi_dung }}</div>
                                <small class="text-muted">
                                    {{ $tb->thoi_gian_tao->diffForHumans() }}
                                </small>
                            </a>
                        </li>
                    @empty
                        <li class="dropdown-item text-center text-muted">
                            Không có thông báo
                        </li>
                    @endforelse

                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a href="{{ route('thongbao.index') }}"
                           class="dropdown-item text-center text-primary">
                            Xem tất cả
                        </a>
                    </li>
                </ul>
            </div>

            {{-- AVATAR --}}
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown">

                    <img
                        src="{{ $user->anh_dai_dien
                            ? (Str::startsWith($user->anh_dai_dien, 'http')
                                ? $user->anh_dai_dien
                                : asset('storage/' . $user->anh_dai_dien))
                            : 'https://via.placeholder.com/40'
                        }}"
                        class="rounded-circle me-2"
                        width="40"
                        height="40"
                        style="object-fit:cover"
                    >

                    <span class="fw-semibold">{{ $user->ho_ten }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item"
                           href="{{ route('trangcanhan.index', ['id'=>$user->ma_nguoi_dung]) }}">
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

        @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary">Đăng nhập</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
        @endif

        </div>
    </div>
</nav>

<div class="container mt-4">
    @yield('maincontent')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
