
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Quản lý bài viết</h3>

        {{-- Chỉ chủ quán mới tạo bài viết --}}
        @php
            $currentUserId   = session('ma_nguoi_dung');
            $currentUserRole = session('user_role');
            $hasNhaHang = $currentUserId ? \App\Models\NhaHang::where('ma_chu_so_huu',$currentUserId)->exists() : false;
        @endphp

        @if($currentUserId && $currentUserRole === 'chu_quan')
            @if($hasNhaHang)
                <a href="{{ route('baiviet.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tạo bài viết
                </a>
            @else
                <a href="{{ route('nhahang.create', ['ma_chu_so_huu' => $currentUserId]) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-plus-lg"></i> Đăng ký Nhà Hàng
                </a>
            @endif
        @endif
    </div>

    {{-- Thông báo session --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Danh sách bài viết --}}
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Người đăng</th>
                <th>Nhà hàng</th>
                <th>Nội dung</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($baiviets as $bv)
                <tr>
                    <td>{{ $bv->ma_bai_viet }}</td>
                    <td>{{ $bv->nguoiDang->ho_ten ?? 'Người dùng' }}</td>
                    <td>{{ $bv->nhaHang->ten_nha_hang ?? '-' }}</td>
                    <td>{{ Str::limit($bv->noi_dung, 50) }}</td>
                    <td>{{ $bv->thoi_gian_tao }}</td>
                    <td>
                        {{-- Nút xem luôn hiển thị --}}
                        <a href="{{ route('baiviet.show', $bv->ma_bai_viet) }}" class="btn btn-sm btn-info">
                            Xem
                        </a>

                        {{-- Chỉ chủ bài viết mới có thể sửa/xóa --}}
                        @if($currentUserId && $currentUserId == $bv->ma_nguoi_dang)
                            <a href="{{ route('baiviet.edit', $bv->ma_bai_viet) }}" class="btn btn-sm btn-warning">
                                Sửa
                            </a>

                            <form action="{{ route('baiviet.destroy', $bv->ma_bai_viet) }}"
                                method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">
                                    Xóa
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Chưa có bài viết nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
