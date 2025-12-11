<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa bài viết</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('baiviet.index') }}">Quản lý Bài viết</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="mb-3">Chỉnh sửa bài viết</h1>

        <!-- Thông báo lỗi -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('baiviet.update', $baiViet->ma_bai_viet) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Người đăng -->
            <div class="mb-3">
                <label for="ma_nguoi_dang" class="form-label">Người đăng</label>
                <select name="ma_nguoi_dang" id="ma_nguoi_dang" class="form-select" required>
                    <option value="">Chọn người đăng</option>
                    @foreach($nguoidungs as $nguoiDung)
                        <option value="{{ $nguoiDung->ma_nguoi_dung }}" {{ $baiViet->ma_nguoi_dang == $nguoiDung->ma_nguoi_dung ? 'selected' : '' }}>
                            {{ $nguoiDung->ten_nguoi_dung }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nhà hàng -->
            <div class="mb-3">
                <label for="ma_nha_hang" class="form-label">Nhà hàng</label>
                <input type="number" name="ma_nha_hang" id="ma_nha_hang" class="form-control" value="{{ $baiViet->ma_nha_hang }}" required>
            </div>

            <!-- Nội dung -->
            <div class="mb-3">
                <label for="noi_dung" class="form-label">Nội dung</label>
                <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5" required>{{ $baiViet->noi_dung }}</textarea>
            </div>

            <!-- Ảnh bài viết hiện có -->
            @if($baiViet->anhBaiViets->count() > 0)
                <div class="mb-3">
                    <label class="form-label">Ảnh hiện có</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($baiViet->anhBaiViets as $anh)
                            <img src="{{ asset('storage/' . $anh->duong_dan_anh) }}" alt="Ảnh bài viết" class="img-thumbnail" style="width:120px; height:120px; object-fit:cover;">
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Thêm ảnh mới -->
            <div class="mb-3">
                <label for="anh_bai_viet" class="form-label">Thêm ảnh mới (nếu muốn)</label>
                <input type="file" name="anh_bai_viet[]" id="anh_bai_viet" class="form-control" multiple>
            </div>

            <button type="submit" class="btn btn-success">Cập nhật bài viết</button>
            <a href="{{ route('baiviet.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
