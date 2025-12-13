<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }
        .card { border-radius: 15px; }
        .form-label { font-weight: 500; }
        .btn-update { display: flex; align-items: center; gap: 5px; }
        .img-container { position: relative; display: inline-block; margin: 5px; }
        .current-img { width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
        .btn-delete-img { position: absolute; top: 2px; right: 2px; background: rgba(255,0,0,0.7); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; font-size: 16px; cursor: pointer; }
        #preview .preview-img { width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; margin: 5px; }
        textarea { resize: none; }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm p-4">
                <h3 class="mb-4 text-center">Chỉnh sửa bài viết</h3>

                {{-- Hiển thị thông báo --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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

                <form action="{{ route('baiviet.update', $baiViet->ma_bai_viet) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nội dung bài viết --}}
                    <div class="mb-3">
                        <label for="noi_dung" class="form-label">Nội dung</label>
                        <textarea id="noi_dung" name="noi_dung" class="form-control" rows="5" required>{{ $baiViet->noi_dung }}</textarea>
                    </div>

                    {{-- Ảnh hiện có --}}
                    @if($baiViet->anhBaiViets && $baiViet->anhBaiViets->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Ảnh hiện có</label>
                            <div class="d-flex flex-wrap" id="current-images">
                                @foreach($baiViet->anhBaiViets as $media)
                                    <div class="img-container">
                                        <img src="{{ asset($media->duong_dan_anh) }}" class="current-img">
                                        <div class="form-check" style="position:absolute; bottom:2px; left:2px;">
                                            <input class="form-check-input" type="checkbox" name="xoa_anh[]" value="{{ $media->ma_anh }}" id="xoa{{ $media->ma_anh }}">
                                            <label class="form-check-label text-white" for="xoa{{ $media->ma_anh }}">Xóa</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Thêm ảnh mới --}}
                    <div class="mb-3">
                        <label for="anh_bai_viet" class="form-label">Thêm ảnh mới</label>
                        <input type="file" name="anh_bai_viet[]" id="anh_bai_viet" class="form-control" multiple accept="image/*">
                        <div id="preview" class="d-flex flex-wrap mt-2"></div>
                    </div>

                    {{-- Nút submit --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-success btn-update">
                            <i class="bi bi-pencil-square"></i> Cập nhật bài viết
                        </button>
                        <a href="{{ route('baiviet.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Preview ảnh mới
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
