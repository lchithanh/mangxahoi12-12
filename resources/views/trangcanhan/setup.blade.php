@extends('layout.header')

@section('title', 'Chỉnh sửa hồ sơ')

@section('maincontent')
<div class="container py-4">
    <h3 class="mb-4">Chỉnh sửa hồ sơ cá nhân</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trangcanhan.saveSetup') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3 text-center">
            <label for="avatar" class="form-label">Ảnh đại diện</label>
            <div class="mb-2">
                <img src="{{ $user->anh_dai_dien ? asset($user->anh_dai_dien) : 'https://via.placeholder.com/150?text=Avatar' }}"
                     alt="Avatar"
                     id="preview-avatar"
                     class="rounded-circle"
                     style="width:150px; height:150px; object-fit:cover;">
            </div>
            <input type="file" class="form-control" id="avatar" name="anh_dai_dien" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="ho_ten" class="form-label">Họ và tên</label>
            <input type="text" class="form-control" id="ho_ten" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="gioi_thieu" class="form-label">Giới thiệu</label>
            <textarea class="form-control" id="gioi_thieu" name="gioi_thieu" rows="4">{{ old('gioi_thieu', $user->gioi_thieu) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="{{ route('trangcanhan.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </form>
</div>

<script>
    // Xem trước avatar khi chọn file mới
    const avatarInput = document.getElementById('avatar');
    const preview = document.getElementById('preview-avatar');

    avatarInput.addEventListener('change', function() {
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
