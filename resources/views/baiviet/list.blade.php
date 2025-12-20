@php
    $currentUserId   = session('ma_nguoi_dung');
    $currentUserRole = session('user_role'); // 'chu_quan', 'nhahang', 'user'
@endphp

<div class="row">
    @forelse($baiviets as $bv)
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow-sm h-100">

                {{-- Carousel ảnh/video --}}
                @if($bv->anhBaiViets && $bv->anhBaiViets->count() > 0)
                    <div id="carouselBaiViet{{ $bv->ma_bai_viet }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="height: 300px;">
                            @foreach($bv->anhBaiViets as $index => $media)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @php
                                        $ext = pathinfo($media->duong_dan_anh, PATHINFO_EXTENSION);
                                    @endphp
                                    @if(in_array(strtolower($ext), ['mp4','webm','ogg']))
                                        <video class="d-block w-100" controls style="height:300px; object-fit:cover;">
                                            <source src="{{ asset($media->duong_dan_anh) }}" type="video/{{ $ext }}">
                                        </video>
                                    @else
                                        <img src="{{ asset($media->duong_dan_anh) }}" class="d-block w-100" style="height:300px; object-fit:cover;">
                                    @endif
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
                @else
                    <img src="https://via.placeholder.com/600x300?text=No+Media" class="card-img-top" style="height:300px; object-fit:cover;">
                @endif

                {{-- Body bài viết --}}
                <div class="card-body d-flex flex-column mt-2">
                    {{-- Header: Avatar + tên nhà hàng --}}
                    @php
                        if ($bv->nhaHang && $bv->nhaHang->anh_dai_dien) {
                            $avatar = asset($bv->nhaHang->anh_dai_dien);
                        } elseif ($bv->nguoiDang && $bv->nguoiDang->anh_dai_dien) {
                            $avatar = asset($bv->nguoiDang->anh_dai_dien);
                        } else {
                            $avatar = 'https://via.placeholder.com/40';
                        }
                        $tenNhaHang = $bv->nhaHang?->ten_nha_hang ?? 'Nhà hàng';
                        $maNhaHang  = $bv->nhaHang?->ma_nha_hang;
                    @endphp
                    <div class="d-flex align-items-center mb-2">
                        @if($maNhaHang)
                            <a href="{{ route('nhahang.show', $maNhaHang) }}">
                                <img src="{{ $avatar }}" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover;">
                            </a>
                        @else
                            <img src="{{ $avatar }}" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover;">
                        @endif
                        <div>
                            @if($maNhaHang)
                                <a href="{{ route('nhahang.show', $maNhaHang) }}" class="text-decoration-none text-dark">
                                    <h6 class="mb-0">{{ $tenNhaHang }}</h6>
                                </a>
                            @else
                                <h6 class="mb-0">{{ $tenNhaHang }}</h6>
                            @endif
                            <small class="text-muted">
                                {{ $bv->thoi_gian_tao ? \Carbon\Carbon::parse($bv->thoi_gian_tao)->format('d/m/Y H:i') : '' }}
                            </small>
                        </div>
                    </div>

                    {{-- Nội dung --}}
                    <p class="card-text mb-3">{{ Str::limit($bv->noi_dung ?? '', 120) }}</p>

                    {{-- Nút hành động --}}
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        @php
                            $isOwner = $currentUserId && $currentUserRole === 'chu_quan' && $currentUserId === $bv->ma_nguoi_dang;
                            $daLuu   = $currentUserId && in_array($bv->ma_bai_viet, $luuBaiVietIds ?? []);
                        @endphp

                        @if($isOwner)
                            <div class="btn-group">
                                <a href="{{ route('baiviet.edit', $bv->ma_bai_viet) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('baiviet.destroy', $bv->ma_bai_viet) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        @else
                            @if($currentUserId)
                                <form action="{{ route('baiviet.save', $bv->ma_bai_viet) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $daLuu ? 'btn-danger' : 'btn-primary' }}">
                                        <i class="far fa-bookmark"></i>
                                        {{ $daLuu ? 'Hủy lưu' : 'Lưu bài viết' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">
                                    Đăng nhập để lưu
                                </a>
                            @endif
                        @endif

                        <a href="{{ route('baiviet.show', $bv->ma_bai_viet) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Chưa có bài viết nào.</p>
        </div>
    @endforelse
</div>