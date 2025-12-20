@php
    $currentUser   = $user; 
    $currentUserId = $currentUser->ma_nguoi_dung ?? null;
@endphp

@if($nhaHangs->count() > 0)

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($nhaHangs as $nhaHang)
            <div class="col">
                <div class="card h-100 shadow-sm border-0 card-hover">

                    {{-- Ảnh đại diện --}}
                    @if(!empty($nhaHang->anh_dai_dien))
                        <img src="{{ asset($nhaHang->anh_dai_dien) }}" 
                             class="card-img-top" 
                             alt="{{ $nhaHang->ten_nha_hang }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:200px;">
                            <i class="bi bi-building fa-3x text-secondary"></i>
                        </div>
                    @endif

                    {{-- Nội dung card --}}
                    <div class="card-body">
                        <h5 class="card-title">{{ $nhaHang->ten_nha_hang }}</h5>
                        <p class="text-muted mb-2" style="font-size:0.9rem;">
                            {{ Str::limit($nhaHang->dia_chi, 60) }}
                        </p>

                        {{-- Badge phân loại và khu vực --}}
                        <div class="mb-2">
                            @if(optional($nhaHang->phanLoai)->ten_phan_loai)
                                <span class="badge bg-primary me-1">{{ $nhaHang->phanLoai->ten_phan_loai }}</span>
                            @endif
                            @if(optional($nhaHang->khuVuc)->ten_khu_vuc)
                                <span class="badge bg-success">{{ $nhaHang->khuVuc->ten_khu_vuc }}</span>
                            @endif
                        </div>

                        {{-- Chủ sở hữu --}}
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            <i class="bi bi-person-circle"></i> {{ optional($nhaHang->chuSoHuu)->ho_ten ?? 'Chưa xác định' }}
                        </p>
                    </div>

                    {{-- Footer --}}
                    <div class="card-footer text-center bg-white">

                        {{-- Nút xem chi tiết --}}
                        <a href="{{ route('nhahang.show', $nhaHang->ma_nha_hang) }}" 
                           class="btn btn-outline-primary btn-sm w-75 mb-2">
                            Xem chi tiết
                        </a>

                        {{-- Nút sửa / xóa: chỉ hiện với chủ quán là owner --}}
                        @if($currentUser && $currentUser->vai_tro === 'chu_quan' && $currentUserId === $nhaHang->ma_chu_so_huu)
                            <div class="d-flex justify-content-center gap-2 mt-2">

                                {{-- Sửa --}}
                                <a href="{{ route('nhahang.edit', $nhaHang->ma_nha_hang) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>

                                {{-- Xóa --}}
                                <form action="{{ route('nhahang.destroy', $nhaHang->ma_nha_hang) }}"
                                      method="POST"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhà hàng này?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>

                            </div>
                        @endif

                    </div>

                </div>
            </div>
        @endforeach
    </div>

@else
    <div class="text-center py-5">
        <i class="bi bi-emoji-frown fa-3x text-secondary"></i>
        <p class="mt-3 text-muted">Hiện chưa có nhà hàng nào.</p>
    </div>
@endif

{{-- CSS riêng cho hover card --}}
@push('styles')
<style>
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    .card-img-top {
        height: 200px;
        object-fit: cover;
    }
</style>
@endpush