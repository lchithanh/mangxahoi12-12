<div class="card p-3 shadow-sm mb-3 w-100">
    <h5>🔍 Bộ lọc theo khu vực</h5>

    <form action="{{ route('home') }}" method="GET">
        <div class="mb-3">
            <label for="district" class="form-label">Chọn khu vực:</label>
            <select name="district" id="district" class="form-select" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                @foreach(\App\Models\KhuVuc::all() as $khuVuc)
                    <option value="{{ $khuVuc->ma_khu_vuc }}"
                        {{ request('district') == $khuVuc->ma_khu_vuc ? 'selected' : '' }}>
                        {{ $khuVuc->ten_khu_vuc }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

