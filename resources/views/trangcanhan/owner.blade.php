@extends('layout.header')

@section('title', 'Trang cá nhân - Chủ quán')

@section('maincontent')
<div class="container py-4">

    {{-- ===== THÔNG TIN CHỦ QUÁN ===== --}}
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center flex-wrap flex-md-nowrap">
                <img src="{{ $user->anh_dai_dien ? asset($user->anh_dai_dien) : 'https://ui-avatars.com/api/?name=' . urlencode($user->ho_ten) }}"
                     class="rounded-circle border shadow-sm mb-3 mb-md-0"
                     width="110" height="110" style="object-fit: cover;" alt="Avatar">

                <div class="ms-md-4 flex-grow-1">
                    <h3 class="fw-bold mb-1 text-dark">{{ $user->ho_ten }}</h3>
                    <p class="text-muted mb-3">{{ $user->mo_ta ?? 'Chưa có mô tả cá nhân.' }}</p>

                    {{-- NÚT THAO TÁC & THỐNG KÊ --}}
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div class="text-center px-3 border-end">
                            <span class="fw-bold d-block">{{ $user->baiviets_count }}</span>
                            <small class="text-muted">Bài viết</small>
                        </div>

                        <a href="{{ route('trangcanhan.dangtheodoi', $user->ma_nguoi_dung) }}"
                           class="btn btn-sm btn-outline-dark rounded-pill px-3">
                            Đang theo dõi <span class="badge text-dark bg-light ms-1">{{ $dangTheoDoiCount }}</span>
                        </a>

                        <a href="{{ route('trangcanhan.theodoi', $user->ma_nguoi_dung) }}"
                           class="btn btn-sm btn-outline-dark rounded-pill px-3">
                            Người theo dõi <span class="badge text-dark bg-light ms-1">{{ $user->followers_count }}</span>
                        </a>

                        <a href="{{ route('trangcanhan.edit') }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold">
                            <i class="bi bi-gear-fill me-1"></i> Setup
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== NHÀ HÀNG CỦA CHỦ QUÁN ===== --}}
<div class="mb-4">
    <h5 class="fw-bold mb-3 border-start border-primary border-4 ps-2">
        Nhà hàng của bạn
    </h5>

    @if($nhaHangs->count())
        @include('nhahang.card', [
            'nhaHangs'       => $nhaHangs,
            'sessionUserId'  => session('ma_nguoi_dung'),
            'sessionVaiTro'  => session('user_role')
        ])
    @else
        <div class="bg-light rounded-4 p-4 text-center border">
            <i class="bi bi-shop h3 text-muted"></i>
            <p class="text-muted mb-0 small">
                Bạn chưa đăng ký nhà hàng nào.
            </p>
        </div>
    @endif
</div>


    {{-- ===== ĐÁNH GIÁ CỦA CHỦ QUÁN ===== --}}
    <div>
        <h5 class="fw-bold mb-3 border-start border-warning border-4 ps-2">Đánh giá của bạn</h5>
        @if($danhGias->count())
            <div class="rounded-4 overflow-hidden border shadow-sm bg-white">
                @include('danhgia.list', ['danhGias' => $danhGias])
            </div>
        @else
            <div class="bg-light rounded-4 p-4 text-center border">
                <p class="text-muted mb-0 small">Bạn chưa để lại đánh giá nào.</p>
            </div>
        @endif
    </div>

</div>

<style>
    /* CSS đơn giản để tinh chỉnh */
    .card { transition: none; } /* Tắt hiệu ứng hover để giao diện ổn định */
    .btn-outline-dark { border-color: #dee2e6; color: #444; }
    .btn-outline-dark:hover { background-color: #f8f9fa; color: #000; border-color: #ced4da; }
    .rounded-4 { border-radius: 1rem !important; }
    .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important; }
</style>
@endsection