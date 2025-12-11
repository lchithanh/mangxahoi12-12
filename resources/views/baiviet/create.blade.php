<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo bài viết mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; }
        .form-label { font-weight: 500; }
        .btn-create { display: flex; justify-content: center; align-items: center; gap: 5px; }
        .preview-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-right: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <h3 class="mb-4">Tạo bài viết mới</h3>

                {{-- Thông báo lỗi --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Thông báo session --}}
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

                <form action="{{ route('baiviet.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Chọn nhà hàng --}}
                    <div class="mb-3">
                        <label for="ma_nha_hang" class="form-label">Nhà hàng</label>
                        <select name="ma_nha_hang" id="ma_nha_hang" class="form-select" required>
                            <option value="">Chọn nhà hàng</option>
                            @foreach($nhahangs as $nhaHang)
                                <option value="{{ $nhaHang->ma_nha_hang }}">{{ $nhaHang->ten_nha_hang }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nội dung bài viết --}}
                    <div class="mb-3">
                        <label for="noi_dung" class="form-label">Nội dung</label>
                        <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5" placeholder="Nhập nội dung bài viết..." required></textarea>
                    </div>

                    {{-- Thêm ảnh --}}
                    <div class="mb-3">
                        <label for="anh_bai_viet" class="form-label">Ảnh bài viết (nếu có)</label>
                        <input type="file" name="anh_bai_viet[]" id="anh_bai_viet" class="form-control" multiple accept="image/*">
                        <div id="preview" class="d-flex flex-wrap mt-2"></div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-create">
                        <i class="bi bi-plus-lg"></i> Tạo bài viết
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary mt-2">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Xem trước ảnh
    const inputFile = document.getElementById('anh_bai_viet');
    const preview = document.getElementById('preview');

    inputFile.addEventListener('change', function() {
        preview.innerHTML = '';
        for (let file of inputFile.files) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-img');
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>
