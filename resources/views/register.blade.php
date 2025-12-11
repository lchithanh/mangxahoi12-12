<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h3 class="mb-4 text-center">Đăng ký</h3>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="ho_ten" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="ho_ten" name="ho_ten" value="{{ old('ho_ten') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="mat_khau" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="mat_khau" name="mat_khau" required>
                    </div>

                    <div class="mb-3">
                        <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh">
                    </div>

                    <div class="mb-3">
                        <label for="vai_tro" class="form-label">Vai trò</label>
                        <select class="form-select" name="vai_tro" id="vai_tro" required>
                            <option value="">Chọn vai trò</option>
                            <option value="nguoi_dung" {{ old('vai_tro')=='nguoi_dung' ? 'selected' : '' }}>Người dùng</option>
                            <option value="chu_quan" {{ old('vai_tro')=='chu_quan' ? 'selected' : '' }}>Chủ quán</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
