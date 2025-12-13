<div class="card p-3 shadow-sm mb-3 w-100">
    <h5>🔍 Bộ lọc tìm kiếm</h5>

    {{-- Form tìm kiếm theo khu vực --}}
    <form action="{{ route('home') }}" method="GET">
        {{-- Khu vực --}}
        <div class="mb-3">
            <label for="district" class="form-label">Khu vực:</label>
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

        {{-- Xếp hạng --}}
        <div class="mb-3">
            <label for="sort" class="form-label">Xếp hạng:</label>
            <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                <option value="">Mặc định</option>
                <option value="followers" {{ request('sort')=='followers' ? 'selected' : '' }}>
                    Nhà hàng nhiều theo dõi
                </option>
                <option value="likes" {{ request('sort')=='likes' ? 'selected' : '' }}>
                    Bài viết nhiều lượt thích
                </option>
            </select>
        </div>
    </form>
</div>
