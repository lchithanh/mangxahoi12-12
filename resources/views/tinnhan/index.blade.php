@extends('layout.header')

@section('title', 'Tin nhắn')

@section('maincontent')
<div class="container py-4">
    <h3 class="mb-4">Danh sách trò chuyện</h3>

    @if($nhaHangs->isEmpty())
        <p>Chưa có cuộc trò chuyện nào.</p>
    @else
        <div class="list-group">
            @foreach($nhaHangs as $nhaHang)
                @php
                    $lastMessage = $nhaHang->tinNhans()
                        ->orderByDesc('created_at')
                        ->first();
                @endphp

                <a href="{{ route('tinnhan.show', $nhaHang->ma_nha_hang) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center gap-3">
                        <img
                            src="{{ $nhaHang->anh_dai_dien ? asset('storage/'.$nhaHang->anh_dai_dien) : 'https://via.placeholder.com/40' }}"
                            class="rounded-circle"
                            width="40" height="40"
                            style="object-fit:cover;"
                        >
                        <div>
                            <h6 class="mb-0">{{ $nhaHang->ten_nha_hang }}</h6>
                            <small class="text-muted">
                                {{ $lastMessage ? \Illuminate\Support\Str::limit($lastMessage->noi_dung, 40) : 'Chưa có tin nhắn' }}
                            </small>
                        </div>
                    </div>

                    @if($lastMessage && $lastMessage->da_doc == 0)
                        <span class="badge bg-primary rounded-pill">Mới</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
