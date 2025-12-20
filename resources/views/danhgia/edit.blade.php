@extends('layout.header')

@section('title', 'Sửa đánh giá')

@section('maincontent')
@php
    // Lấy session id người dùng hiện tại
    $currentUserId = session('ma_nguoi_dung');
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(!$currentUserId)
                <div class="alert alert-warning text-center">
                    Bạn cần <a href="{{ route('login') }}">đăng nhập</a> để sửa đánh giá.
                </div>
            @elseif($currentUserId != $danhGia->nguoiDung->ma_nguoi_dung)
                <div class="alert alert-danger text-center">
                    Bạn không có quyền sửa đánh giá này.
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">Sửa đánh giá</h4>

                        <form action="{{ route('danhgia.update', $danhGia->ma_danh_gia) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Điểm đánh giá --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đánh giá (1-5 sao)</label>
                                <select name="diem_danh_gia" class="form-select @error('diem_danh_gia') is-invalid @enderror" required>
                                    @for($i=1; $i<=5; $i++)
                                        <option value="{{ $i }}" {{ $danhGia->diem_danh_gia == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('diem_danh_gia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Bình luận --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Bình luận</label>
                                <textarea name="binh_luan" rows="3"
                                          class="form-control @error('binh_luan') is-invalid @enderror"
                                          required>{{ old('binh_luan', $danhGia->binh_luan) }}</textarea>
                                @error('binh_luan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ảnh hiện tại --}}
                            @if($danhGia->anhDanhGias->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ảnh hiện tại</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($danhGia->anhDanhGias as $anh)
                                            <div class="text-center">
                                                <img src="{{ asset($anh->duong_dan_anh) }}" width="120" class="rounded mb-1">
                                                <div>
                                                    <input type="checkbox" name="xoa_anh[]" value="{{ $anh->id }}">
                                                    <small>Xóa</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Thêm ảnh mới --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Thêm ảnh mới (tùy chọn, nhiều ảnh)</label>
                                <input type="file" name="anh_danh_gia[]" multiple
                                       class="form-control @error('anh_danh_gia.*') is-invalid @enderror"
                                       accept="image/*">
                                @error('anh_danh_gia.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Cập nhật đánh giá</button>
                            <a href="{{ route('danhgia.index', $danhGia->ma_bai_viet) }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
