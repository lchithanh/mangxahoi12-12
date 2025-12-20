@extends('layout.header')

@section('title', 'Chỉnh sửa hồ sơ')

@section('maincontent')
<div class="container py-4">
    <h3>Chỉnh sửa hồ sơ</h3>

    {{-- Thông báo thành công / lỗi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Hiển thị lỗi validate --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trangcanhan.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Họ tên --}}
        <div class="mb-3">
            <label for="ho_ten" class="form-label">Họ tên</label>
            <input type="text" name="ho_ten" id="ho_ten" class="form-control"
                   value="{{ old('ho_ten', $user->ho_ten) }}" required>
            @error('ho_ten') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email', $user->email) }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Mô tả --}}
        <div class="mb-3">
            <label for="mo_ta" class="form-label">Mô tả</label>
            <textarea name="mo_ta" id="mo_ta" class="form-control" rows="3">{{ old('mo_ta', $user->mo_ta) }}</textarea>
            @error('mo_ta') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Ảnh đại diện --}}
        <div class="mb-3">
            <label for="anh_dai_dien" class="form-label">Ảnh đại diện</label>
            <input type="file" name="anh_dai_dien" id="anh_dai_dien" class="form-control" accept="image/*">

            {{-- Ảnh hiện tại --}}
            @if($user->anh_dai_dien)
                <div class="mt-2">
                    <img src="{{ asset($user->anh_dai_dien) }}" alt="Ảnh đại diện"
                         style="width:100px; height:100px; object-fit:cover;" class="img-thumbnail">
                </div>
            @endif
            @error('anh_dai_dien') <small class="text-danger">{{ $message }}</small> @enderror

            {{-- Preview ảnh mới --}}
            <div class="mt-3">
                <img id="preview" style="width:100px; height:100px; object-fit:cover; display:none;" class="img-thumbnail">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="{{ route('trangcanhan.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<script>
    // Preview ảnh mới
    const inputFile = document.getElementById('anh_dai_dien');
    const preview = document.getElementById('preview');
    inputFile.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection