{{-- resources/views/danhgia/create.blade.php --}}
@php
    // Nếu include từ index, truyền $baiViet, $user, $nhaHang
    // Nếu mở trực tiếp qua create(), controller phải truyền đủ biến
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="mb-3">Viết đánh giá cho bài viết</h5>

        {{-- Hiển thị lỗi validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('danhgia.store', ['ma_bai_viet' => $baiViet->ma_bai_viet]) }}" 
              method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Hidden fields --}}
            <input type="hidden" name="ma_bai_viet" value="{{ $baiViet->ma_bai_viet }}">
            <input type="hidden" name="ma_nguoi_dung" value="{{ $user->ma_nguoi_dung ?? 0 }}">
            <input type="hidden" name="ma_nha_hang" value="{{ $baiViet->ma_nha_hang }}">

            {{-- Điểm đánh giá --}}
            <div class="mb-3">
                <label for="diem_danh_gia" class="form-label">Điểm đánh giá (1-5)</label>
                <select name="diem_danh_gia" id="diem_danh_gia" class="form-select" required>
                    <option value="">Chọn điểm</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            {{-- Nội dung đánh giá --}}
            <div class="mb-3">
                <label for="noi_dung" class="form-label">Nội dung đánh giá</label>
                <textarea name="binh_luan" id="noi_dung" class="form-control" rows="4" placeholder="Viết đánh giá..." required></textarea>
            </div>

            {{-- Ảnh đánh giá --}}
            <div class="mb-3">
                <label for="duong_dan_anh" class="form-label">Ảnh (nếu có)</label>
                <input type="file" name="duong_dan_anh[]" id="duong_dan_anh" class="form-control" multiple accept="image/*">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Gửi đánh giá</button>
                <a href="{{ route('baiviet.show', $baiViet->ma_bai_viet) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại bài viết
                </a>
            </div>
        </form>
    </div>
</div>
