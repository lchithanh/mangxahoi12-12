@extends('layout.header')

@section('title', 'Nhắn tin')

@section('maincontent')
<div class="container py-4">
    @php
        $user = session('user');
    @endphp

    <h3 class="mb-4">Trò chuyện với {{ $nhaHang->ten_nha_hang }}</h3>

    <div class="card mb-3">
        <div class="card-body" style="height:400px; overflow-y:auto;" id="chatBox">
            @forelse($messages as $msg)
                @if($msg->ma_nguoi_gui)
                    <div class="text-end mb-2">
                        <span class="badge bg-primary p-2" style="max-width:70%; display:inline-block;">
                            {{ $msg->noi_dung }}
                        </span>
                    </div>
                @else
                    <div class="text-start mb-2">
                        <span class="badge bg-secondary p-2" style="max-width:70%; display:inline-block;">
                            {{ $msg->noi_dung }}
                        </span>
                    </div>
                @endif
            @empty
                <p class="text-muted text-center">Chưa có tin nhắn</p>
            @endforelse
        </div>
    </div>

    @if($user)
    <form id="chatForm" class="mt-3 d-flex gap-2">
        @csrf
        <input type="hidden" id="ma_nha_hang" value="{{ $nhaHang->ma_nha_hang }}">
        <input type="text" id="noi_dung" class="form-control" placeholder="Nhập tin nhắn..." required>
        <button class="btn btn-success">Gửi</button>
    </form>
    @endif
</div>

@push('scripts')
<script>
const chatBox   = document.getElementById('chatBox');
const chatForm  = document.getElementById('chatForm');
const noiDungEl = document.getElementById('noi_dung');
const maNhaHang = document.getElementById('ma_nha_hang')?.value;

// lastId lấy từ message cuối cùng
let lastId = {{ $messages->last()->ma_tin_nhan ?? 0 }};

// AUTO SCROLL KHI LOAD
chatBox.scrollTop = chatBox.scrollHeight;

// APPEND MESSAGE
function appendMessage(msg) {
    const div = document.createElement('div');
    div.className = msg.ma_nguoi_gui ? 'text-end mb-2' : 'text-start mb-2';
    div.innerHTML = `
        <span class="badge ${msg.ma_nguoi_gui ? 'bg-primary' : 'bg-secondary'} p-2" 
              style="max-width:70%; display:inline-block;">
            ${msg.noi_dung}
        </span>`;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
    lastId = msg.ma_tin_nhan;
}

// GỬI TIN NHẮN
if(chatForm && maNhaHang) {
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const noiDung = noiDungEl.value.trim();
        if(!noiDung) return;

        fetch("{{ route('tinnhan.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                ma_nha_hang: maNhaHang,
                noi_dung: noiDung
            })
        })
        .then(res => res.json())
        .then(msg => {
            appendMessage(msg);
            noiDungEl.value = '';
        })
        .catch(err => console.error(err));
    });
}

// LOAD TIN NHẮN MỚI
function loadNewMessages() {
    if(!maNhaHang) return;

    fetch(`/tin-nhan/fetch/${maNhaHang}?last_id=${lastId}`)
    .then(res => res.json())
    .then(data => {
        data.forEach(msg => appendMessage(msg));
    })
    .catch(err => console.error(err));
}

setInterval(loadNewMessages, 2000);
</script>
@endpush
@endsection
