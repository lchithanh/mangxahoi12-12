{{-- resources/views/trangcanhan/followers.blade.php --}}

@extends('layout.header')

@section('title', $user->ho_ten . ' đang theo dõi tôi')

@section('maincontent')
<div class="container py-5">

    <h3 class="text-center mb-5">{{ $user->ho_ten }} đang theo dõi tôi</h3>

    <div class="row g-4">

        {{-- Người dùng --}}
        <div class="col-12 col-md-6">
            <h5 class="mb-3 text-primary">Người dùng</h5>
            @if($followers->count() > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1 g-3">
                    @foreach($followers as $u)
                        <div class="col">
                            <a href="{{ route('trangcanhan.index', ['id' => $u->ma_nguoi_dung]) }}" class="card shadow-sm h-100 text-decoration-none text-dark hover-card">
                                <div class="card-body d-flex align-items-center">
                                    <img src="{{ $u->anh_dai_dien ? asset($u->anh_dai_dien) : 'https://ui-avatars.com/api/?name=' . urlencode($u->ho_ten) }}"
                                         class="rounded-circle me-3" width="60" height="60">
                                    <div>
                                        <h6 class="mb-1">{{ $u->ho_ten }}</h6>
                                        <small class="text-muted">{{ $u->baiviets_count ?? 0 }} bài viết</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Chưa có người dùng nào theo dõi bạn.</p>
            @endif
        </div>

       
    </div>
</div>

{{-- CSS tinh tế --}}
<style>
.hover-card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.card-body img {
    border: 2px solid #eee;
}
</style>
@endsection
