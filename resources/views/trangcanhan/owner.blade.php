@extends('layout.header')

@section('title', $user->ho_ten ?? 'Trang cá nhân chủ quán')

@section('maincontent')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar: Chủ quán -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <img src="{{ $user->anh_dai_dien && file_exists(public_path($user->anh_dai_dien))
                                    ? asset($user->anh_dai_dien)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->ho_ten) }}"
                             class="rounded-circle border shadow-sm" width="128" height="128">
                    </div>
                    <h5>{{ $user->ho_ten }}</h5>
                    <p class="text-muted">{{ $user->dia_chi ?? 'Địa chỉ chưa cập nhật' }}</p>

                    <!-- Nút theo dõi chủ quán khác -->
                    @if(session('user') && session('user')->ma_nguoi_dung != $user->ma_nguoi_dung)
                        @php
                            $isFollowing = \App\Models\TheoDoi::where('ma_nguoi_dung', session('user')->ma_nguoi_dung)
                                            ->where('ma_nguoi_duoc_theo_doi', $user->ma_nguoi_dung)
                                            ->exists();
                        @endphp

                        @if($isFollowing)
                            <a href="{{ route('unfollow', $user->ma_nguoi_dung) }}" class="btn btn-outline-danger btn-sm w-100 mb-2">
                                <i class="bi bi-person-dash"></i> Hủy theo dõi
                            </a>
                        @else
                            <a href="{{ route('follow', $user->ma_nguoi_dung) }}" class="btn btn-primary btn-sm w-100 mb-2">
                                <i class="bi bi-person-plus"></i> Theo dõi
                            </a>
                        @endif
                    @endif

                    <a href="{{ route('trangcanhan.edit') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-pencil-square"></i> Chỉnh sửa hồ sơ
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content: Danh sách nhà hàng -->
        <!-- Main Content: Danh sách nhà hàng -->
<div class="col-lg-9 col-md-8">
    <h4>Danh sách nhà hàng</h4>
    @if($nhaHangs && $nhaHangs->count() > 0)
        <div class="row g-4">
            @foreach($nhaHangs as $nh)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <a href="{{ route('nhahang.show', $nh->ma_nha_hang) }}">
                            <img src="{{ $nh->anh_dai_dien ? asset($nh->anh_dai_dien) : 'https://via.placeholder.com/400x250' }}"
                                 class="card-img-top" style="height:200px; object-fit:cover;">
                            <div class="card-body">
                                <h6>{{ $nh->ten_nha_hang }}</h6>

                                @if(session('user') && session('user')->ma_nguoi_dung != $user->ma_nguoi_dung)
                                    <!-- Xem nhà hàng của người khác: hiện nút theo dõi -->
                                    @php
                                        $isFollowingNh = \App\Models\TheoDoi::where('ma_nguoi_dung', session('user')->ma_nguoi_dung)
                                            ->where('ma_nha_hang', $nh->ma_nha_hang)
                                            ->exists();
                                    @endphp

                                    @if($isFollowingNh)
                                        <a href="{{ route('unfollow.nhahang', $nh->ma_nha_hang) }}" class="btn btn-outline-danger btn-sm mt-2 w-100">Hủy theo dõi</a>
                                    @else
                                        <a href="{{ route('follow.nhahang', $nh->ma_nha_hang) }}" class="btn btn-primary btn-sm mt-2 w-100">Theo dõi</a>
                                    @endif
                                @else
                                    <!-- Xem chính nhà hàng của mình: hiện số lượt theo dõi -->
                                    @php
                                        $followersCount = \App\Models\TheoDoi::where('ma_nha_hang', $nh->ma_nha_hang)->count();
                                    @endphp
                                    <p class="text-muted mt-2">Lượt theo dõi: {{ $followersCount }}</p>
                                @endif

                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Chưa có nhà hàng nào.</p>
    @endif
</div>

    </div>
</div>
@endsection
