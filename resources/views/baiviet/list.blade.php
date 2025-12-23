@php
    $currentUserId   = session('ma_nguoi_dung');
    $currentUserRole = session('user_role'); // 'chu_quan', 'nhahang', 'user'
@endphp

<div class="row">
    @forelse($baiviets as $bv)
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow-sm h-100 border-0"> {{-- Thêm border-0 để trông hiện đại hơn --}}

                {{-- CHỈ HIỂN THỊ MEDIA NẾU CÓ DỮ LIỆU --}}
                @if($bv->anhBaiViets && $bv->anhBaiViets->count() > 0)
                    <div id="carouselBaiViet{{ $bv->ma_bai_viet }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="border-radius: 8px 8px 0 0;"> {{-- Bo góc trên ảnh --}}
                            @foreach($bv->anhBaiViets as $index => $media)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @php
                                        $ext = pathinfo($media->duong_dan_anh, PATHINFO_EXTENSION);
                                    @endphp
                                    <div style="height: 350px;"> {{-- Tăng chiều cao lên một chút cho thoáng --}}
                                        @if(in_array(strtolower($ext), ['mp4','webm','ogg']))
                                            <video class="d-block w-100 h-100" controls style="object-fit:cover;">
                                                <source src="{{ asset($media->duong_dan_anh) }}" type="video/{{ $ext }}">
                                            </video>
                                        @else
                                            <img src="{{ asset($media->duong_dan_anh) }}" class="d-block w-100 h-100" style="object-fit:cover;">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($bv->anhBaiViets->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselBaiViet{{ $bv->ma_bai_viet }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselBaiViet{{ $bv->ma_bai_viet }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @endif
                {{-- NẾU KHÔNG CÓ ẢNH, CARD SẼ BẮT ĐẦU NGAY TỪ PHẦN NỘI DUNG DƯỚI ĐÂY --}}

                <div class="card-body d-flex flex-column p-4"> {{-- Tăng padding để nội dung văn bản nổi bật hơn --}}
                    
                    {{-- Header: Thông tin người đăng --}}
                    @php
                        if ($bv->nhaHang && $bv->nhaHang->anh_dai_dien) {
                            $avatar = asset($bv->nhaHang->anh_dai_dien);
                        } elseif ($bv->nguoiDang && $bv->nguoiDang->anh_dai_dien) {
                            $avatar = asset($bv->nguoiDang->anh_dai_dien);
                        } else {
                            $avatar = 'https://ui-avatars.com/api/?name='.urlencode($bv->nhaHang?->ten_nha_hang ?? 'F');
                        }
                        $tenNhaHang = $bv->nhaHang?->ten_nha_hang ?? 'Nhà hàng';
                        $maNhaHang  = $bv->nhaHang?->ma_nha_hang;
                    @endphp
                    
                    <div class="d-flex align-items-center mb-3">
                        @if($maNhaHang)
                            <a href="{{ route('nhahang.show', $maNhaHang) }}">
                                <img src="{{ $avatar }}" class="rounded-circle me-3 border" width="45" height="45" style="object-fit:cover;">
                            </a>
                        @else
                            <img src="{{ $avatar }}" class="rounded-circle me-3 border" width="45" height="45" style="object-fit:cover;">
                        @endif
                        
                        <div>
                            @if($maNhaHang)
                                <a href="{{ route('nhahang.show', $maNhaHang) }}" class="text-decoration-none text-dark fw-bold">
                                    <h6 class="mb-0">{{ $tenNhaHang }}</h6>
                                </a>
                            @else
                                <h6 class="mb-0 fw-bold">{{ $tenNhaHang }}</h6>
                            @endif
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $bv->thoi_gian_tao ? \Carbon\Carbon::parse($bv->thoi_gian_tao)->diffForHumans() : '' }}
                            </small>
                        </div>
                    </div>

                    {{-- Nội dung bài viết --}}
                    <div class="card-text mb-4 text-secondary" style="line-height: 1.6; font-size: 1.05rem;">
                        {{ $bv->noi_dung ?? 'Không có nội dung.' }}
                    </div>

                    {{-- Nút hành động --}}
                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        @php
                            $isOwner = $currentUserId && $currentUserRole === 'chu_quan' && $currentUserId === $bv->ma_nguoi_dang;
                            $daLuu   = $currentUserId && in_array($bv->ma_bai_viet, $luuBaiVietIds ?? []);
                        @endphp

                        <div class="d-flex gap-2">
                            @if($isOwner)
                                <a href="{{ route('baiviet.edit', $bv->ma_bai_viet) }}" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Sửa
                                </a>
                                <form action="{{ route('baiviet.destroy', $bv->ma_bai_viet) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        <i class="bi bi-trash me-1"></i> Xóa
                                    </button>
                                </form>
                            @else
                                @if($currentUserId)
                                    <form action="{{ route('baiviet.save', $bv->ma_bai_viet) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm rounded-pill px-3 {{ $daLuu ? 'btn-danger' : 'btn-outline-primary' }}">
                                            <i class="bi {{ $daLuu ? 'bi-bookmark-fill' : 'bi-bookmark' }} me-1"></i>
                                            {{ $daLuu ? 'Đã lưu' : 'Lưu tin' }}
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>

                        <a href="{{ route('baiviet.show', $bv->ma_bai_viet) }}" class="btn btn-link text-decoration-none fw-bold p-0">
                            Xem chi tiết <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-journal-x display-1 text-muted"></i>
            <p class="text-muted mt-3">Hiện chưa có bài viết nào được đăng.</p>
        </div>
    @endforelse
</div>