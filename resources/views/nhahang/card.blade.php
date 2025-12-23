@php
    $currentUser   = $user; 
    $currentUserId = $currentUser->ma_nguoi_dung ?? null;
@endphp

@if($nhaHangs->count() > 0)
    <div class="row g-3"> {{-- Giảm khoảng cách g-4 xuống g-3 để trông khít và hiện đại hơn --}}
        @foreach($nhaHangs as $nhaHang)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    
                    {{-- Ảnh đại diện: Dùng class ratio để ảnh luôn đồng nhất kích thước mà không cần CSS --}}
                    <div class="ratio ratio-16x9">
                        @if(!empty($nhaHang->anh_dai_dien))
                            <img src="{{ asset($nhaHang->anh_dai_dien) }}" 
                                 class="card-img-top rounded-top-3" 
                                 style="object-fit: cover;"
                                 alt="{{ $nhaHang->ten_nha_hang }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-top-3">
                                <i class="bi bi-image text-secondary h1"></i>
                            </div>
                        @endif
                    </div>

                    <div class="card-body p-3">
                        {{-- Tiêu đề & Link: stretched-link giúp bấm vào đâu trên card cũng đi tới chi tiết --}}
                        <h6 class="card-title fw-bold mb-1">
                            <a href="{{ route('nhahang.show', $nhaHang->ma_nha_hang) }}" class="text-dark text-decoration-none stretched-link">
                                {{ $nhaHang->ten_nha_hang }}
                            </a>
                        </h6>
                        
                        <p class="text-muted small mb-2 text-truncate">
                            <i class="bi bi-geo-alt small"></i> {{ $nhaHang->dia_chi }}
                        </p>

                        <div class="d-flex flex-wrap gap-1 mb-2">
                            @if(optional($nhaHang->phanLoai)->ten_phan_loai)
                                <span class="badge bg-light text-primary border border-primary-subtle fw-normal">{{ $nhaHang->phanLoai->ten_phan_loai }}</span>
                            @endif
                            @if(optional($nhaHang->khuVuc)->ten_khu_vuc)
                                <span class="badge bg-light text-success border border-success-subtle fw-normal">{{ $nhaHang->khuVuc->ten_khu_vuc }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Footer: Chỉ hiện nút sửa xóa nếu là chủ quán --}}
                    @if($currentUser && $currentUser->vai_tro === 'chu_quan' && $currentUserId === $nhaHang->ma_chu_so_huu)
                        <div class="card-footer bg-transparent border-top-0 p-3 pt-0" style="position: relative; z-index: 2;">
                            <div class="d-flex gap-2">
                                <a href="{{ route('nhahang.edit', $nhaHang->ma_nha_hang) }}"
                                   class="btn btn-sm btn-light border flex-grow-1">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>
                                <form action="{{ route('nhahang.destroy', $nhaHang->ma_nha_hang) }}"
                                      method="POST" class="flex-grow-1"
                                      onsubmit="return confirm('Xóa nhà hàng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5 border rounded-3 bg-light">
        <i class="bi bi-shop h1 text-muted"></i>
        <p class="mt-2 text-muted">Chưa có nhà hàng nào được đăng ký.</p>
    </div>
@endif