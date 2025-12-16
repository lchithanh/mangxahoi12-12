@extends('layout.header')

@section('title', 'Danh sách phòng chat')

@section('maincontent')
<div class="container py-4">
    <h3 class="mb-4">Danh sách phòng chat</h3>

    @if($phongChats->isEmpty())
        <p>Chưa có phòng chat nào.</p>
    @else
        <div class="list-group">
            @foreach($phongChats as $phong)
                <a href="{{ route('phongchat.show', $phong->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $phong->vaiTroHienThi }}:</strong> {{ $phong->tenHienThi }}
                    </div>
                    <span class="badge bg-secondary">
                        {{ $phong->tinNhans->where('da_doc',0)->count() }} tin mới
                    </span>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
