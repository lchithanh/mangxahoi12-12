<div class="card p-3 shadow-sm mb-3 w-100">
    <h5>🔍 Bộ lọc </h5>

    <form method="GET">
        <div class="row g-2">

            {{-- Lọc theo khu vực --}}
            <div class="col-md-6">
                <label class="form-label">Khu vực</label>
                <select name="district" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    @foreach(\App\Models\KhuVuc::all() as $khuVuc)
                        <option value="{{ $khuVuc->ma_khu_vuc }}"
                            {{ request('district') == $khuVuc->ma_khu_vuc ? 'selected' : '' }}>
                            {{ $khuVuc->ten_khu_vuc }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Lọc theo phân loại --}}
            <div class="col-md-6">
                <label class="form-label">Phân loại</label>
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả phân loại</option>
                    @foreach(\App\Models\PhanLoai::all() as $phanLoai)
                        <option value="{{ $phanLoai->ma_phan_loai }}"
                            {{ request('category') == $phanLoai->ma_phan_loai ? 'selected' : '' }}>
                            {{ $phanLoai->ten_phan_loai }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </form>
</div>
