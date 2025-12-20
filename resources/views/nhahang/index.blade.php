@extends('layout.header')

@section('title', 'Danh sách nhà hàng')

@section('maincontent')
@php
    $user_id = session('ma_nguoi_dung');   // ID người dùng hiện tại
    $vai_tro = session('user_role');       // Vai trò người dùng hiện tại
@endphp

<div class="container py-5">
    <h2 class="mb-4 text-center">Danh sách nhà hàng</h2>

    {{-- Nếu đã đăng nhập và là chủ quán thì hiện nút tạo --}}
    @if($user_id && $vai_tro === 'chu_quan')
        <div class="mb-3 text-end">
            <a href="{{ route('nhahang.create') }}" class="btn btn-primary">+ Tạo nhà hàng mới</a>
        </div>
    @endif

    {{-- Include partial hiển thị card, truyền thêm session để partial kiểm tra quyền --}}
    @include('nhahang.card', [
        'nhaHangs'       => $nhaHangs,
        'sessionUserId'  => $user_id,
        'sessionVaiTro'  => $vai_tro
    ])
</div>
@endsection