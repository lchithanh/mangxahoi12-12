<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-lg-4">
        {{-- Header của bộ lọc: Làm thanh mảnh và thoáng hơn --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="bi bi-funnel text-primary small"></i>
                </div>
                <span class="fw-bold text-dark small text-uppercase l-spacing-1">Bộ lọc</span>
            </div>

            {{-- Nút Reset: Đưa lên góc trên để tiết kiệm diện tích hàng dưới --}}
            @if(request('district') || request('category'))
                <a href="{{ url()->current() }}" class="text-decoration-none text-muted small fw-medium hover-danger transition">
                    <i class="bi bi-x-circle me-1"></i>Xóa lọc
                </a>
            @endif
        </div>

        <form method="GET">
            <div class="row g-2"> {{-- Giảm g-5 xuống g-2 để các ô gắn kết hơn --}}
                
                {{-- Lọc theo khu vực --}}
                <div class="col-md-6">
                    <div class="input-group">
                        <label class="input-group-text border-0 bg-light text-muted px-3 rounded-start-3">
                        </label>
                        <select name="district" class="form-select border-0 bg-light py-2 fw-medium shadow-none rounded-end-3" 
                                onchange="this.form.submit()">
                            <option value="">Tất cả khu vực</option>
                            @foreach(\App\Models\KhuVuc::all() as $khuVuc)
                                <option value="{{ $khuVuc->ma_khu_vuc }}"
                                    {{ request('district') == $khuVuc->ma_khu_vuc ? 'selected' : '' }}>
                                    {{ $khuVuc->ten_khu_vuc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Lọc theo phân loại --}}
                <div class="col-md-6">
                    <div class="input-group">
                        <label class="input-group-text border-0 bg-light text-muted px-3 rounded-start-3">
                        </label>
                        <select name="category" class="form-select border-0 bg-light py-2 fw-medium shadow-none rounded-end-3" 
                                onchange="this.form.submit()">
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

            </div>
        </form>
    </div>
</div>
