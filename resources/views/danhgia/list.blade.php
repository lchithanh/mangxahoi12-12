@forelse($danhGias as $dg)
    <div class="list-group-item mb-2 shadow-sm rounded">
        <div class="d-flex align-items-start">
            {{-- Avatar người đánh giá --}}
            <img src="{{ asset(optional($dg->nguoiDung)->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                 class="rounded-circle me-3" width="50" height="50" style="object-fit:cover;">

            <div style="width:100%;">
                {{-- Tên người đánh giá --}}
                <strong>{{ optional($dg->nguoiDung)->ho_ten ?? 'Người dùng' }}</strong>
                <small class="text-muted d-block">{{ $dg->thoi_gian_tao->diffForHumans() }}</small>

                {{-- Bài viết liên quan --}}
                @if($dg->baiViet)
                    <div class="mb-2 p-2 bg-light rounded">
                        <strong>Bài viết liên quan:</strong>
                        <a href="{{ route('baiviet.show', $dg->baiViet->ma_bai_viet) }}">
                            {{ Str::limit($dg->baiViet->noi_dung, 80) }}
                        </a>
                        @if($dg->baiViet->anhBaiViets->count() > 0)
                            <img src="{{ asset($dg->baiViet->anhBaiViets->first()->duong_dan_anh) }}"
                                 alt="Ảnh bài viết"
                                 class="rounded mt-1"
                                 style="width:100px; height:60px; object-fit:cover;">
                        @endif
                    </div>
                @endif

                {{-- Số sao --}}
                <div class="text-warning mb-1">
                    @for($i=1; $i<=5; $i++)
                        <i class="bi bi-star{{ $i <= $dg->diem_danh_gia ? '-fill' : '' }}"></i>
                    @endfor
                    <span class="ms-2">({{ $dg->diem_danh_gia }}/5)</span>
                </div>

                {{-- Nội dung đánh giá --}}
                <p>{{ $dg->binh_luan }}</p>

                {{-- Ảnh đánh giá --}}
                @if ($dg->duong_dan_anh)
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        @foreach(explode(',', $dg->duong_dan_anh) as $img)
                            <img src="{{ asset($img) }}" width="100" class="rounded">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Chưa có đánh giá nào.</div>
@endforelse
