@extends('layout.header')

@section('title', 'Trang Chủ')

@section('maincontent')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="row">
    {{-- Sidebar trái --}}
    <div class="col-md-4">
        @include('layout.sidebar-left')
    </div>

    {{-- Nội dung chính --}}
    <div class="col-md-6">
        @include('baiviet.list')
    </div>

   
</div>
@endsection
