@extends('layout.header')

@section('title', 'Trang Chủ')

@section('maincontent')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="container my-4">
    <div class="row">
        {{-- Sidebar trái: chiếm 4 cột --}}
        <div class="col-md-3">
            @include('layout.sidebar-left')
        </div>

        {{-- Nội dung chính: chiếm 8 cột --}}
        <div class="col-md-9">
            @include('baiviet.list')
        </div>
    </div>
</div>
@endsection
