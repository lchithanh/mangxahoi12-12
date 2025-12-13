@extends('layout.header')

@section('title', 'Tin nhắn')

@section('maincontent')
<div class="container py-4">
    <h3 class="mb-4">Danh sách trò chuyện</h3>

    @if($threads->isEmpty())
        <p>Chưa có cuộc trò chuyện nào.</p>
    @else
        <div class="list-group">
            @foreach($threads as $nguoiId => $messages)
                @php
                    $lastMessage = $messages->first();
                    $nguoi = $lastMessage->nguoi_gui_id == $userId ? $lastMessage->nguoiNhan : $lastMessage->nguoiGui;
                @endphp
                <a href="{{ route('nhantin.show', $nguoi->ma_nguoi_dung) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $nguoi->avatar ? asset('storage/'.$nguoi->avatar) : 'https://via.placeholder.com/40' }}" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                        <div>
                            <h6 class="mb-0">{{ $nguoi->ho_ten }}</h6>
                            <small class="text-muted">{{ Str::limit($lastMessage->noi_dung, 40) }}</small>
                        </div>
                    </div>
                    @if($lastMessage->da_doc == 0 && $lastMessage->nguoi_nhan_id == $userId)
                        <span class="badge bg-primary rounded-pill">Mới</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
