@extends('layout.header')

@section('title', 'Chỉnh sửa Nhà Hàng')

@section('maincontent')
@php
    $maNguoiDung = session('ma_nguoi_dung'); // ID người dùng từ session
    $vaiTro      = session('user_role');     // Vai trò từ session
@endphp

<div class="container mt-5">

    @if(!$maNguoiDung)
        <div class="alert alert-warning">
            Bạn cần đăng nhập để chỉnh sửa nhà hàng.
        </div>
    @elseif($vaiTro !== 'chu_quan' || $maNguoiDung !== $nhaHang->ma_chu_so_huu)
        <div class="alert alert-danger">
            Bạn không có quyền chỉnh sửa nhà hàng này.
        </div>
    @else
        <h2>Chỉnh sửa Nhà Hàng</h2>

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

        <form action="{{ route('nhahang.update', $nhaHang->ma_nha_hang) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Tên nhà hàng --}}
            <div class="mb-3">
                <label for="ten_nha_hang" class="form-label">Tên Nhà Hàng</label>
                <input type="text" name="ten_nha_hang" id="ten_nha_hang" class="form-control" 
                       value="{{ old('ten_nha_hang', $nhaHang->ten_nha_hang) }}" required>
            </div>

            {{-- Địa chỉ --}}
            <div class="mb-3">
                <label for="dia_chi" class="form-label">Địa Chỉ</label>
                <input type="text" name="dia_chi" id="dia_chi" class="form-control" 
                       value="{{ old('dia_chi', $nhaHang->dia_chi) }}" required>
            </div>

            {{-- Khu vực --}}
            <select name="ma_khu_vuc" id="ma_khu_vuc" class="form-select" onchange="toggleKhuVucKhac()">
    @foreach($khuVucs as $kh)
        <option value="{{ $kh->ma_khu_vuc }}" {{ $nhaHang->ma_khu_vuc == $kh->ma_khu_vuc ? 'selected' : '' }}>
            {{ $kh->ten_khu_vuc }}
        </option>
    @endforeach
    <option value="khac">Khác (Thêm mới)</option>
</select>
<input type="text" name="ten_khu_vuc_moi" id="ten_khu_vuc_moi" class="form-control mt-2" style="display:none;" placeholder="Nhập tên khu vực mới">
            {{-- Phân loại --}}
            <div class="mb-3">
                <label for="phan_loai" class="form-label fw-bold">Phân Loại</label>
                <select name="phan_loai" id="phan_loai" class="form-select" onchange="togglePhanLoaiKhac()">
                    <option value="">-- Chọn Phân Loại --</option>
                    @foreach($phanLoais as $pl)
                        <option value="{{ $pl->ten_phan_loai }}"
                            {{ (old('phan_loai', $nhaHang->phanLoai->ten_phan_loai ?? '') == $pl->ten_phan_loai) ? 'selected' : '' }}>
                            {{ $pl->ten_phan_loai }}
                        </option>
                    @endforeach
                    <option value="khac" {{ old('phan_loai') == 'khac' ? 'selected' : '' }}>Khác (Thêm mới)</option>
                </select>
                
                <input type="text" name="phan_loai_moi" id="phan_loai_moi" 
                    class="form-control mt-2" placeholder="Nhập tên phân loại mới" 
                    style="display: {{ old('phan_loai') == 'khac' ? 'block' : 'none' }};" 
                    value="{{ old('phan_loai_moi') }}">
            </div>

            {{-- Số điện thoại --}}
            <div class="mb-3">
                <label for="so_dien_thoai" class="form-label">Số Điện Thoại</label>
                <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" 
                       value="{{ old('so_dien_thoai', $nhaHang->so_dien_thoai) }}">
            </div>

            {{-- Giờ mở cửa --}}
            <div class="mb-3">
                <label for="gio_mo_cua" class="form-label">Giờ Mở Cửa</label>
                <input type="text" name="gio_mo_cua" id="gio_mo_cua" class="form-control" 
                       value="{{ old('gio_mo_cua', $nhaHang->gio_mo_cua) }}">
            </div>

            {{-- Ảnh đại diện --}}
            <div class="mb-3">
                <label for="anh_dai_dien" class="form-label">Ảnh Đại Diện</label>

                {{-- Ảnh hiện tại --}}
                @if(!empty($nhaHang->anh_dai_dien))
                    <div class="mb-2">
                        <img src="{{ asset($nhaHang->anh_dai_dien) }}"
                            alt="Ảnh đại diện"
                            style="max-width:200px"
                            class="img-thumbnail">
                    </div>
                @endif

                {{-- Upload ảnh mới --}}
                <input type="file"
                    name="anh_dai_dien"
                    id="anh_dai_dien"
                    class="form-control"
                    accept="image/*"
                    onchange="previewImage(event)">

                {{-- Preview ảnh mới --}}
                <div class="mt-3">
                    <img id="preview"
                        style="max-width:200px; display:none;"
                        class="img-thumbnail">
                </div>
            </div>

            {{-- Mô tả --}}
            <div class="mb-3">
                <label for="mo_ta" class="form-label">Mô Tả</label>
                <textarea name="mo_ta" id="mo_ta" class="form-control" rows="3">{{ old('mo_ta', $nhaHang->mo_ta) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
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
@endsection