@extends('layout.header')

@section('title', 'Thông báo')

@section('maincontent')
<div class="card shadow-sm border-0">
    <!-- Tiêu đề -->
    <div class="card-header bg-primary text-white fw-bold d-flex align-items-center">
        <i class="bi bi-bell-fill me-2"></i> Tất cả thông báo
    </div>

    <!-- Danh sách thông báo -->
    <ul class="list-group list-group-flush">
        @forelse($thongBaos as $tb)
            <li class="list-group-item d-flex justify-content-between align-items-center"
                style="cursor:pointer"
                onclick="window.location.href='{{ route('baiviet.show', $tb->ma_doi_tuong) }}'">
                
                <div class="d-flex align-items-start gap-3">
                    <!-- Icon theo loại -->
                    <div class="fs-4">
                        @if($tb->loai_thong_bao === 'theo_doi')
                            <i class="bi bi-person-plus text-primary"></i>
                        @elseif($tb->loai_thong_bao === 'thich')
                            <i class="bi bi-hand-thumbs-up text-success"></i>
                        @elseif($tb->loai_thong_bao === 'danh_gia')
                            <i class="bi bi-star text-warning"></i>
                        @else
                            <i class="bi bi-info-circle text-secondary"></i>
                        @endif
                    </div>

                    <!-- Nội dung -->
                    <div>
                        <div class="fw-semibold">{{ $tb->noi_dung }}</div>
                        <small class="text-muted">
                            {{ $tb->thoi_gian_tao->diffForHumans() }}
                        </small>
                    </div>
                </div>

                <!-- Nút đánh dấu đã đọc -->
                @if(!$tb->da_doc)
                    <form action="{{ route('thongbao.dadoc', $tb->ma_thong_bao) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-check2"></i>
                        </button>
                    </form>
                @endif
            </li>
        @empty
            <li class="list-group-item text-center text-muted py-4">
                <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                Không có thông báo
            </li>
        @endforelse
    </ul>
</div>
@endsection