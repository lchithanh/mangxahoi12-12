@extends('layout.header')

@section('title', 'Nhắn tin')

@section('maincontent')
<div class="container py-4">
    @php
        $currentUserId = session('ma_nguoi_dung');
        $otherUser = $tinNhan->first()?->nguoi_gui_id == $currentUserId
                     ? $tinNhan->first()?->nguoiNhan
                     : $tinNhan->first()?->nguoiGui;
    @endphp

    <h3 class="mb-4">Trò chuyện với {{ $otherUser->ho_ten ?? 'Người dùng' }}</h3>

    <div class="card">
        <div class="card-body" style="height:400px; overflow-y:auto;" id="chatBox">
            @foreach($tinNhan as $tin)
                @if($tin->nguoi_gui_id == $currentUserId)
                    <div class="text-end mb-2">
                        <span class="badge bg-primary p-2" style="max-width:70%; display:inline-block;">
                            {{ $tin->noi_dung }}
                        </span>
                    </div>
                @else
                    <div class="text-start mb-2">
                        <span class="badge bg-secondary p-2" style="max-width:70%; display:inline-block;">
                            {{ $tin->noi_dung }}
                        </span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <form action="{{ route('nhantin.store') }}" method="POST" class="mt-3 d-flex gap-2">
        @csrf
        <input type="hidden" name="ma_nguoi_nhan" value="{{ $otherUser->ma_nguoi_dung ?? '' }}">
        <input type="text" name="noi_dung" class="form-control" placeholder="Nhập tin nhắn..." required>
        <button type="submit" class="btn btn-success">Gửi</button>
    </form>
</div>

@push('scripts')
<script>
    var chatBox = document.getElementById('chatBox');
    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
@endsection
