@extends('layout.header')

@section('title', 'Thông báo')

@section('maincontent')
<div class="container py-4">

    {{-- Tiêu đề --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="mb-0">
            <i class="bi bi-bell-fill text-warning me-2"></i>
            Thông báo của bạn
        </h3>

        <span class="badge bg-danger fs-6">
            {{ $soThongBaoChuaDoc }} chưa đọc
        </span>
    </div>

    {{-- Không có thông báo --}}
    @if($thongBaos->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
            Không có thông báo nào.
        </div>
    @else
        <ul class="list-group shadow-sm">

            @foreach($thongBaos as $tb)

                @php
                    switch ($tb->loai_thong_bao) {
                        case 'thich':
                        case 'danh_gia':
                            $href = route('baiviet.show', $tb->ma_doi_tuong);
                            break;

                        case 'theo_doi':
                            $href = route('trangcanhan.index', $tb->ma_nguoi_gui);
                            break;

                        default:
                            $href = '#';
                    }
                @endphp

                <li class="list-group-item d-flex justify-content-between align-items-start
                    {{ !$tb->da_doc ? 'list-group-item-warning' : '' }}">

                    {{-- Nội dung --}}
                    <div class="me-3">
                        <a href="{{ $href }}"
                           class="fw-semibold text-decoration-none text-dark">
                            {{ $tb->noi_dung }}
                        </a>

                        <div class="small text-muted mt-1">
                            <i class="bi bi-clock me-1"></i>
                            {{ $tb->thoi_gian_tao->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    {{-- Đánh dấu đã đọc --}}
                    @if(!$tb->da_doc)
                        <form action="{{ route('thongbao.dadoc', $tb->ma_thong_bao) }}"
                              method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Đánh dấu đã đọc">
                                <i class="bi bi-check2"></i>
                            </button>
                        </form>
                    @endif

                </li>
            @endforeach

        </ul>
    @endif
</div>
@endsection
