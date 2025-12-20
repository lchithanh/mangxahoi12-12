@extends('layout.header')

@section('title', 'Bài viết đã lưu')

@section('maincontent')
<div class="container py-4">

    <h2 class="mb-4">Bài viết đã lưu</h2>

    @php
        // Lấy thông tin user từ session
        $currentUserId   = session('ma_nguoi_dung');
        $currentUserRole = session('user_role'); // 'chu_quan', 'nhahang', 'user'
    @endphp

    @if(isset($baiviets) && $baiviets->count() > 0)
        {{-- INCLUDE LIST --}}
        @include('baiviet.list', [
            'baiviets' => $baiviets,
            'luuBaiVietIds' => $luuBaiVietIds ?? [],
            'currentUserId' => $currentUserId,
            'currentUserRole' => $currentUserRole
        ])
    @else
        <div class="text-center py-5">
            <p class="text-muted">Bạn chưa lưu bài viết nào.</p>
        </div>
    @endif

</div>
@endsection
