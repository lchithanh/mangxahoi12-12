@extends('layout.header')

@section('title', 'Danh sách nhà hàng')

@section('maincontent')
<div class="container py-5">
    <h2 class="mb-4 text-center">Danh sách nhà hàng</h2>

    {{-- Include partial hiển thị card --}}
    @include('nhahang.card', ['nhaHangs' => $nhaHangs])
</div>
@endsection
