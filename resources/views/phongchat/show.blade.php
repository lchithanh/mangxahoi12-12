@extends('layout.header')

@section('title', $tenPhong)

@section('maincontent')
<div class="container py-4">
    <h3 class="mb-4">Phòng chat với {{ $tenPhong }}</h3>

    <div class="card mb-3">
        <div class="card-body" style="height:400px; overflow-y:auto;" id="chatBox">
            @foreach($phongChat->tinNhans as $tin)
                @php
                    $isMe = ($tin->ma_nguoi_gui == $user->ma_nguoi_dung);
                @endphp
                <div class="mb-2 d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="p-2 rounded {{ $isMe ? 'bg-primary text-white' : 'bg-light' }}" style="max-width:70%;">
                        <strong>{{ $isMe ? 'Bạn' : ($tin->nguoiGui->ho_ten ?? 'Người khác') }}:</strong>
                        <p class="mb-0">{{ $tin->noi_dung }}</p>
                        <small class="text-muted">{{ $tin->created_at->format('H:i d/m/Y') }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <form action="{{ route('tinnhan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="ma_phong_chat" value="{{ $phongChat->id }}">
        <div class="input-group">
            <input type="text" name="noi_dung" class="form-control" placeholder="Nhập tin nhắn..." required>
            <button type="submit" class="btn btn-primary">Gửi</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Auto scroll xuống cuối chat khi load trang
    var chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
