<div class="card shadow-sm mb-4 mx-auto" style="max-width:900px;">
    <div class="card-body text-center">

        {{-- Người đăng --}}
        <h5 class="fw-bold mb-2">{{ $baiViet->nguoiDang->ho_ten ?? 'Người dùng ẩn danh' }}</h5>
        <small class="text-muted d-block mb-2">
            Đăng lúc {{ \Carbon\Carbon::parse($baiViet->thoi_gian_tao)->format('d/m/Y H:i') }}
        </small>

        {{-- Nội dung --}}
        <p>{{ $baiViet->noi_dung }}</p>

        {{-- Ảnh bài viết --}}
        @if($baiViet->anhBaiViets->count() > 0)
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                @foreach($baiViet->anhBaiViets as $anh)
                    <img src="{{ asset(str_replace('storage/', '', $anh->duong_dan_anh)) }}"
                         class="rounded shadow-sm border"
                         style="width:150px; height:150px; object-fit:cover;">
                @endforeach
            </div>
        @endif

        {{-- Like + Đánh giá --}}
        <div class="mt-3 d-flex justify-content-between align-items-center">
            @if(isset($user))
                @php
                    $daLike = $baiViet->luotThichs->contains('nguoi_dung_id', $user->ma_nguoi_dung ?? 0);
                @endphp
                <form action="{{ route('baiviet.like', $baiViet->ma_bai_viet) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn {{ $daLike ? 'btn-danger' : 'btn-outline-danger' }}">
                        {{ $daLike ? '❌ Unlike' : '👍 Like' }} <span>{{ $baiViet->luotThichs->count() }}</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('danhgia.index', ['ma_bai_viet' => $baiViet->ma_bai_viet]) }}"
               class="btn btn-primary">
                💬 Đánh giá <span class="badge bg-light text-dark">{{ $baiViet->danhGias()->count() }}</span>
            </a>
        </div>

    </div>
</div>
