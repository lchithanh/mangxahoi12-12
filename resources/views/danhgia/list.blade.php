<div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    {{-- Nếu là chủ quán thì thêm style scroll --}}
    <div class="card-body p-4"
         @if(session('user_role') === 'chu_quan')
             style="max-height:500px; overflow-y:auto;"
         @endif>

        <h5 class="fw-bold mb-4">Danh sách đánh giá</h5>

        {{-- Lặp qua danh sách đánh giá --}}
        @forelse($danhGias as $dg)
            <div class="list-group-item mb-3 p-3 shadow-sm rounded-4 border-0 bg-white" style="transition: all 0.3s ease;">
                <div class="d-flex align-items-start">

                    {{-- Avatar người đánh giá --}}
                    <img src="{{ asset(optional($dg->nguoiDung)->anh_dai_dien ?? 'uploads/anh_nguoi_dung/default.png') }}"
                         class="rounded-circle me-3 border shadow-sm"
                         width="50" height="50"
                         style="object-fit:cover;">

                    <div style="width:100%;">
                        {{-- Tên người đánh giá --}}
                        <div class="d-flex justify-content-between">
                            <strong class="text-dark">{{ optional($dg->nguoiDung)->ho_ten ?? 'Người dùng' }}</strong>
                            <small class="text-muted">
                                {{ $dg->thoi_gian_tao->diffForHumans() }}
                            </small>
                        </div>

                        {{-- Bài viết liên quan (Thiết kế nhẹ nhàng hơn) --}}
                        @if($dg->baiViet)
                            <div class="my-2 p-2 bg-light rounded-3 border-start border-primary border-3">
                                <small class="text-muted d-block mb-1">Đánh giá cho:</small>
                                <div class="d-flex align-items-center">
                                    @if($dg->baiViet->anhBaiViets->count() > 0)
                                        <img src="{{ asset($dg->baiViet->anhBaiViets->first()->duong_dan_anh) }}"
                                             alt="Ảnh bài viết"
                                             class="rounded me-2"
                                             style="width:60px; height:40px; object-fit:cover;">
                                    @endif
                                    <a href="{{ route('baiviet.show', $dg->baiViet->ma_bai_viet) }}" 
                                       class="text-decoration-none text-primary small fw-bold text-truncate" style="max-width: 250px;">
                                        {{ Str::limit($dg->baiViet->noi_dung, 60) }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Số sao --}}
                        <div class="text-warning my-2">
                            @for($i=1; $i<=5; $i++)
                                <i class="bi bi-star{{ $i <= $dg->diem_danh_gia ? '-fill' : '' }} small"></i>
                            @endfor
                            <span class="ms-2 text-muted small">({{ $dg->diem_danh_gia }}/5)</span>
                        </div>

                        {{-- Nội dung đánh giá --}}
                        <p class="text-secondary mb-2" style="font-size: 0.95rem;">{{ $dg->binh_luan }}</p>

                        {{-- Ảnh đánh giá --}}
                        @if ($dg->anhDanhGias->count() > 0)
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach($dg->anhDanhGias as $anh)
                                    <img src="{{ asset($anh->duong_dan_anh) }}"
                                         alt="Ảnh đánh giá"
                                         width="90"
                                         height="90"
                                         class="rounded-3 border shadow-xs"
                                         style="object-fit: cover;">
                                @endforeach
                            </div>
                        @endif

                        {{-- Nút sửa / xóa --}}
                        @php
                            $currentUserId = session('ma_nguoi_dung');
                        @endphp
                        @if($currentUserId && $currentUserId == $dg->ma_nguoi_dung)
                            <div class="mt-3 pt-2 border-top d-flex gap-3">
                                <a href="{{ route('danhgia.edit', $dg->ma_danh_gia) }}"
                                   class="btn btn-sm btn-link p-0 text-warning text-decoration-none fw-bold">Sửa</a>
                                
                                <form action="{{ route('danhgia.destroy', $dg->ma_danh_gia) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link p-0 text-danger text-decoration-none fw-bold">Xóa</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-light text-center border-0 shadow-sm rounded-4 py-4">
                <i class="bi bi-chat-dots text-muted display-6 mb-2 d-block"></i>
                <span class="text-muted">Chưa có đánh giá nào.</span>
            </div>
        @endforelse
    </div>
</div>

<!-- <style>
    /* Làm đẹp thanh cuộn */
    .card-body::-webkit-scrollbar {
        width: 5px;
    }
    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .card-body::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 10px;
    }
    .card-body::-webkit-scrollbar-thumb:hover {
        background: #ccc;
    }
</style> -->