<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Quản lý bài viết</h3>

        @php
            $currentUserId   = session('ma_nguoi_dung');
            $currentUserRole = session('user_role');

            $hasNhaHang = false;
            if ($currentUserId && $currentUserRole === 'chu_quan') {
                $hasNhaHang = \App\Models\NhaHang::where(
                    'ma_chu_so_huu',
                    $currentUserId
                )->exists();
            }
        @endphp

        {{-- ====== NÚT / THÔNG BÁO THEO VAI TRÒ ====== --}}

        {{-- Chủ quán có nhà hàng --}}
        @if($currentUserId && $currentUserRole === 'chu_quan' && $hasNhaHang)
            <a href="{{ route('baiviet.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tạo bài viết
            </a>

        {{-- Chủ quán nhưng chưa có nhà hàng --}}
        @elseif($currentUserId && $currentUserRole === 'chu_quan' && !$hasNhaHang)
            <a href="{{ route('nhahang.create', ['ma_chu_so_huu' => $currentUserId]) }}"
               class="btn btn-warning btn-sm">
                <i class="bi bi-shop"></i> Đăng ký Nhà Hàng
            </a>

        {{-- Người dùng thường --}}
        @elseif($currentUserId && $currentUserRole === 'nguoi_dung')
            <div class="alert alert-info py-2 px-3 mb-0">
                <i class="bi bi-info-circle"></i>
                Chỉ <strong>cửa hàng</strong> mới có thể thêm bài viết.
            </div>
        @endif
    </div>
