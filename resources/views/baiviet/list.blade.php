<div class="row">
    @forelse($baiviets as $bv)
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow-sm h-100">

                {{-- ===============================
                    HIỂN THỊ CAROUSEL ẢNH BÀI VIẾT
                ================================ --}}
                @if($bv->anhBaiViets && $bv->anhBaiViets->count() > 0)
                    <div id="carouselBaiViet{{ $bv->ma_bai_viet }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="height: 300px;">

                            {{-- Duyệt ảnh và đánh dấu ảnh đầu tiên là active --}}
                            @foreach($bv->anhBaiViets as $index => $anh)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset($anh->duong_dan_anh) }}" 
                                         class="d-block w-100"
                                         style="object-fit: cover; height: 300px;">
                                </div>
                            @endforeach
                        </div>

                        {{-- Nút điều hướng carousel (nếu có nhiều ảnh) --}}
                        @if($bv->anhBaiViets->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselBaiViet{{ $bv->ma_bai_viet }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselBaiViet{{ $bv->ma_bai_viet }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    {{-- Nếu bài viết không có ảnh → hiện ảnh placeholder --}}
                    <img src="https://via.placeholder.com/600x300?text=No+Image" 
                         class="card-img-top"
                         style="object-fit: cover; height: 300px;">
                @endif

                <div class="card-body d-flex flex-column mt-2">

                    {{-- ============================================
                        PHẦN HEADER: AVATAR + TÊN NHÀ HÀNG (CLICKABLE)
                        ============================================ --}}
                    <div class="d-flex align-items-center mb-2">

                        @php
                            // Ưu tiên ảnh đại diện của nhà hàng
                            if (!empty($bv->nhaHang?->anh_dai_dien)) {
                                $avatar = asset('storage/' . $bv->nhaHang->anh_dai_dien);
                            }
                            // Nếu nhà hàng không có ảnh → dùng avatar người đăng
                            elseif (!empty($bv->nguoiDang?->avatar)) {
                                $avatar = asset('storage/' . $bv->nguoiDang->avatar);
                            }
                            // Không có avatar → ảnh mặc định
                            else {
                                $avatar = 'https://via.placeholder.com/40';
                            }
                        @endphp

                        {{-- CLICK VÀO ẢNH → ĐI ĐẾN TRANG CHI TIẾT NHÀ HÀNG --}}
                        <a href="{{ route('nhahang.show', $bv->ma_nha_hang) }}">
                            <img src="{{ $avatar }}" 
                                 class="rounded-circle me-2" 
                                 width="40" 
                                 height="40"
                                 style="object-fit: cover;">
                        </a>

                        <div>

                            {{-- CLICK VÀO TÊN NHÀ HÀNG → ĐI ĐẾN TRANG CHI TIẾT --}}
                            <a href="{{ route('nhahang.show', $bv->ma_nha_hang) }}"
                               class="text-decoration-none text-dark">
                                <h6 class="mb-0">
                                    {{ $bv->nhaHang->ten_nha_hang ?? 'Nhà hàng' }}
                                </h6>
                            </a>

                            {{-- Thời gian đăng bài --}}
                            <small class="text-muted">
                                {{ $bv->thoi_gian_tao ? \Carbon\Carbon::parse($bv->thoi_gian_tao)->format('d/m/Y H:i') : '' }}
                            </small>
                        </div>
                    </div>

                    {{-- ================================
                        NỘI DUNG RÚT GỌN CỦA BÀI VIẾT
                    ================================= --}}
                    <p class="card-text mb-3">
                        {{ Str::limit($bv->noi_dung ?? '', 120) }}
                    </p>

                    {{-- ================================
                        NÚT XEM CHI TIẾT BÀI VIẾT
                    ================================= --}}
                    <a href="{{ route('baiviet.show', $bv->ma_bai_viet) }}" 
                       class="btn btn-outline-primary mt-auto">
                        Xem chi tiết
                    </a>

                </div>
            </div>
        </div>

    @empty
        {{-- Nếu không có bài viết nào --}}
        <div class="col-12 text-center py-5">
            <p class="text-muted">Chưa có bài viết nào.</p>
        </div>
    @endforelse
</div>
