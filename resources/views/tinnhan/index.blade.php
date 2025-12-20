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
                @php
                    $soTinMoi = $phong->tinNhans->where('da_doc', 0)->count();
                    $tinCuoi  = $phong->tinNhans->sortByDesc('thoi_gian_tao')->first();
                @endphp

               <a href="{{ route('tinnhan.phong.show', $phong->id) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">

                    <div>
                        <div>
                            <strong>{{ $phong->vaiTroHienThi }}:</strong>
                            {{ $phong->tenHienThi }}
                        </div>

                        @if($tinCuoi)
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($tinCuoi->thoi_gian_tao)->diffForHumans() }}
                            </small>
                        @endif
                    </div>

                    @if($soTinMoi > 0)
                        <span class="badge bg-danger">
                            {{ $soTinMoi }} tin mới
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
