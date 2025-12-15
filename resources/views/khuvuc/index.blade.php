@extends('layout.header')

@section('title', 'Danh sách khu vực')

@section('maincontent')
<div class="container py-4">

    <h3>📍 Danh sách khu vực</h3>

    <a href="{{ route('khuvuc.create') }}" class="btn btn-primary mb-3">Thêm khu vực mới</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên khu vực</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($khuVucs as $khu)
                <tr>
                    <td>{{ $khu->ma_khu_vuc }}</td>
                    <td>{{ $khu->ten_khu_vuc }}</td>
                    <td>
                        <a href="{{ route('khuvuc.edit', $khu->ma_khu_vuc) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('khuvuc.destroy', $khu->ma_khu_vuc) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Chưa có khu vực nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
