@php
    $currentUserId = session('ma_nguoi_dung');
@endphp

@if(!$currentUserId)
    <div class="alert alert-warning text-center">
        Bạn cần <a href="{{ route('login') }}">đăng nhập</a> để viết đánh giá.
    </div>
@else
    <form action="{{ route('danhgia.store', $baiViet->ma_bai_viet) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Điểm đánh giá --}}
        <div class="mb-3">
            <label for="diem_danh_gia" class="form-label fw-bold">Điểm đánh giá (1-5)</label>
            <select name="diem_danh_gia" id="diem_danh_gia"
                    class="form-select @error('diem_danh_gia') is-invalid @enderror" required>
                <option value="">-- Chọn số sao --</option>
                @for($i=1; $i<=5; $i++)
                    <option value="{{ $i }}" {{ old('diem_danh_gia') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            @error('diem_danh_gia')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Bình luận --}}
        <div class="mb-3">
            <label for="binh_luan" class="form-label fw-bold">Bình luận</label>
            <textarea name="binh_luan" id="binh_luan" rows="3"
                      class="form-control @error('binh_luan') is-invalid @enderror"
                      required>{{ old('binh_luan') }}</textarea>
            @error('binh_luan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ảnh đánh giá --}}
        <div class="mb-3">
            <label for="anh_danh_gia" class="form-label fw-bold">Ảnh đánh giá (tùy chọn, nhiều ảnh)</label>
            <input type="file" name="anh_danh_gia[]" id="anh_danh_gia" multiple
                   class="form-control @error('anh_danh_gia.*') is-invalid @enderror"
                   accept="image/*">
            @error('anh_danh_gia.*')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
    </form>
@endif