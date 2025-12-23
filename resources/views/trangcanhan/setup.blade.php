@extends('layout.header')

@section('title', 'Chỉnh sửa hồ sơ')

@section('maincontent')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4 text-center">Chỉnh sửa hồ sơ</h4>

                    {{-- Thông báo --}}
                    @if(session('success'))
                        <div class="alert alert-success small py-2">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('trangcanhan.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Xem trước & Chọn ảnh --}}
                        <div class="text-center mb-4">
                            <img id="preview" 
                                 src="{{ $user->anh_dai_dien ? asset($user->anh_dai_dien) : 'https://ui-avatars.com/api/?name=' . urlencode($user->ho_ten) }}" 
                                 class="rounded-circle border mb-2" 
                                 style="width:100px; height:100px; object-fit:cover;">
                            
                            <div class="mt-2">
                                <label for="anh_dai_dien" class="btn btn-sm btn-light border small">Thay đổi ảnh</label>
                                <input type="file" name="anh_dai_dien" id="anh_dai_dien" class="d-none" accept="image/*">
                            </div>
                        </div>

                        {{-- Họ tên --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Họ tên</label>
                            <input type="text" name="ho_ten" class="form-control"
                                   value="{{ old('ho_ten', $user->ho_ten) }}" required>
                            @error('ho_ten') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Mô tả</label>
                            <textarea name="mo_ta" class="form-control" rows="3">{{ old('mo_ta', $user->mo_ta) }}</textarea>
                            @error('mo_ta') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Nút bấm --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="{{ route('trangcanhan.index', ['id' => $user->ma_nguoi_dung]) }}" 
                               class="btn btn-link btn-sm text-decoration-none text-muted">Hủy bỏ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JS đơn giản để xem trước ảnh khi chọn file
    document.getElementById('anh_dai_dien').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection