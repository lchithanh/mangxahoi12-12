<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - FoodSocial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light shadow-sm">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand fw-bold text-primary" href="<?php echo e(route('home')); ?>">🍴 FoodSocial</a>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-primary">Đăng nhập</a>
        </div>
    </nav>

    <!-- Nội dung chính -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow w-100">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Đăng ký tài khoản</h4>
                </div>
                <div class="card-body p-4">

                    <!-- Thông báo -->
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($errors) && $errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors->all() as $error): ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form action="<?php echo e(route('register.submit')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="ho_ten" class="form-label">Họ tên</label>
                            <input type="text" name="ho_ten" id="ho_ten"
                                   class="form-control"
                                   value="<?php echo e(old('ho_ten')); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control"
                                   value="<?php echo e(old('email')); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="mat_khau" class="form-label">Mật khẩu</label>
                            <input type="password" name="mat_khau" id="mat_khau"
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="vai_tro" class="form-label">Vai trò</label>
                            <select name="vai_tro" id="vai_tro" class="form-select" required>
                                <option value="">-- Chọn vai trò --</option>
                                <option value="nguoi_dung" <?php echo e(old('vai_tro')=='nguoi_dung'?'selected':''); ?>>Người dùng</option>
                                <option value="chu_quan" <?php echo e(old('vai_tro')=='chu_quan'?'selected':''); ?>>Chủ quán</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ngay_sinh" class="form-label">Ngày sinh (tuỳ chọn)</label>
                            <input type="date" name="ngay_sinh" id="ngay_sinh"
                                   class="form-control"
                                   value="<?php echo e(old('ngay_sinh')); ?>">
                        </div>

                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Đăng ký
                        </button>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-link w-100">Đã có tài khoản? Đăng nhập ngay</a>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3 text-center">
        © 2025 FoodSocial - Kết nối ẩm thực
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>