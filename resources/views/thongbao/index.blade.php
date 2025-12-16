@extends('layout.header')

@section('title', 'Thông báo')

@section('maincontent')
<div class="card shadow-sm">
    <div class="card-header fw-bold">Tất cả thông báo</div>

    <ul class="list-group list-group-flush">
        @forelse($thongBaos as $tb)
            <li class="list-group-item">
                <div>{{ $tb->noi_dung }}</div>
                <small class="text-muted">
                    {{ $tb->thoi_gian_tao->diffForHumans() }}
                </small>
            </li>
        @empty
            <li class="list-group-item text-center text-muted">
                Không có thông báo
            </li>
        @endforelse
    </ul>
</div>
@endsection
