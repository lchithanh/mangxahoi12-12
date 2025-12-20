@extends('layout.header')

@section('title', 'Đăng ký Nhà Hàng')

@section('maincontent')
@php
    $maNguoiDung = session('ma_nguoi_dung'); // Lấy ID người dùng từ session
    $vaiTro      = session('user_role');     // Lấy vai trò (chu_quan / user)
@endphp

<div class="container mt-5">

    @if(!$maNguoiDung)
        <div class="alert alert-warning">
            Bạn cần đăng nhập để tạo nhà hàng.
        </div>
    @elseif($vaiTro !== 'chu_quan')
        <div class="alert alert-danger">
            Bạn không có quyền tạo nhà hàng. Chỉ chủ quán mới được phép.
        </div>
    @else
        <h2>Đăng ký Nhà Hàng</h2>

        {{-- Thông báo lỗi validate --}}
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
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('nhahang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Tên nhà hàng --}}
            <div class="mb-3">
                <label for="ten_nha_hang" class="form-label">Tên Nhà Hàng</label>
                <input type="text" name="ten_nha_hang" id="ten_nha_hang"
                       class="form-control" value="{{ old('ten_nha_hang') }}" required>
            </div>

            {{-- Địa chỉ --}}
            <div class="mb-3">
                <label for="dia_chi" class="form-label">Địa Chỉ</label>
                <input type="text" name="dia_chi" id="dia_chi"
                       class="form-control" value="{{ old('dia_chi') }}" required>
            </div>

            {{-- Khu vực --}}
            <div class="mb-3">
                <label for="ma_khu_vuc" class="form-label">Khu Vực</label>
                <select name="ma_khu_vuc" id="ma_khu_vuc" class="form-select" onchange="toggleKhuVucKhac()">
                    <option value="">-- Chọn Khu Vực --</option>
                    @foreach($khuVucs as $khuVuc)
                        <option value="{{ $khuVuc->ma_khu_vuc }}"
                            {{ old('ma_khu_vuc') == $khuVuc->ma_khu_vuc ? 'selected' : '' }}>
                            {{ $khuVuc->ten_khu_vuc }}
                        </option>
                    @endforeach
                    <option value="khac" {{ old('ma_khu_vuc') == 'khac' ? 'selected' : '' }}>Khác</option>
                </select>
                <input type="text" name="ten_khu_vuc_moi" id="ten_khu_vuc_moi"
                       class="form-control mt-2" placeholder="Nhập khu vực mới"
                       style="display:none;" value="{{ old('ten_khu_vuc_moi') }}">
            </div>

            {{-- Phân loại --}}
            <div class="mb-3">
                <label for="phan_loai" class="form-label">Phân Loại</label>
                <select name="phan_loai" id="phan_loai" class="form-select" onchange="togglePhanLoaiKhac()">
                    <option value="">-- Chọn Phân Loại --</option>
                    @foreach($phanLoais as $phanLoai)
                        <option value="{{ $phanLoai->ten_phan_loai }}"
                            {{ old('phan_loai') == $phanLoai->ten_phan_loai ? 'selected' : '' }}>
                            {{ $phanLoai->ten_phan_loai }}
                        </option>
                    @endforeach
                    <option value="khac" {{ old('phan_loai') == 'khac' ? 'selected' : '' }}>Khác</option>
                </select>
                <input type="text" name="phan_loai_moi" id="phan_loai_moi"
                       class="form-control mt-2" placeholder="Nhập phân loại mới"
                       style="display:none;" value="{{ old('phan_loai_moi') }}">
            </div>

            {{-- Số điện thoại --}}
            <div class="mb-3">
                <label for="so_dien_thoai" class="form-label">Số Điện Thoại</label>
                <input type="text" name="so_dien_thoai" id="so_dien_thoai"
                       class="form-control" value="{{ old('so_dien_thoai') }}">
            </div>

            {{-- Giờ mở cửa --}}
            <div class="mb-3">
                <label for="gio_mo_cua" class="form-label">Giờ mở cửa</label>
                <input type="text" name="gio_mo_cua" id="gio_mo_cua"
                       class="form-control" placeholder="08:00 - 22:00"
                       value="{{ old('gio_mo_cua') }}">
            </div>

            {{-- Ảnh đại diện --}}
            <div class="mb-3">
                <label for="anh_dai_dien" class="form-label">Ảnh Đại Diện</label>
                <input type="file" name="anh_dai_dien" id="anh_dai_dien"
                       class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Đăng Ký</button>
            <a href="{{ route('nhahang.index') }}" class="btn btn-secondary">Hủy</a>
        </form>

    @endif
</div>

<script>
    function toggleKhuVucKhac() {
        const select = document.getElementById('ma_khu_vuc');
        const input = document.getElementById('ten_khu_vuc_moi');
        input.style.display = (select.value === 'khac') ? 'block' : 'none';
    }

    function togglePhanLoaiKhac() {
        const select = document.getElementById('phan_loai');
        const input = document.getElementById('phan_loai_moi');
        input.style.display = (select.value === 'khac') ? 'block' : 'none';
    }

    window.onload = function() {
        toggleKhuVucKhac();
        togglePhanLoaiKhac();
    }
</script>