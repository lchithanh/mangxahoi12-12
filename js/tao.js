// JS xử lý rating 
const stars = document.querySelectorAll('.rating-star');
const ratingInput = document.getElementById('danh_gia');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const value = parseInt(star.dataset.value);
        ratingInput.value = value;

        stars.forEach((s, index) => {
            if(index < value) {
                s.classList.remove('text-secondary');
                s.classList.add('text-warning', 'bi-star-fill');
                s.classList.remove('bi-star');
            } else {
                s.classList.remove('text-warning', 'bi-star-fill');
                s.classList.add('text-secondary', 'bi-star');
            }
        });
    });
});


//JS xử lý lấy vị trí hiện tại 
document.getElementById('btnGetAddress').addEventListener('click', () => {
    const addressInput = document.getElementById('dia_chi');
    const nameInput = document.getElementById('ten_dia_diem');

    if (!navigator.geolocation) {
        alert('Trình duyệt của bạn không hỗ trợ định vị.');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                const data = await res.json();

                if (data && data.display_name) {
                    addressInput.value = data.display_name;
                    if (data.name) {
                        nameInput.value = data.name;
                    }
                } else {
                    alert('Không lấy được địa chỉ từ vị trí hiện tại.');
                }
            } catch (err) {
                console.error(err);
                alert('Lỗi khi lấy địa chỉ từ vị trí.');
            }
        },
        (error) => {
            switch(error.code) {
                case error.PERMISSION_DENIED: alert('Bạn đã từ chối truy cập vị trí.'); break;
                case error.POSITION_UNAVAILABLE: alert('Không xác định được vị trí.'); break;
                case error.TIMEOUT: alert('Lấy vị trí quá thời gian cho phép.'); break;
                default: alert('Đã xảy ra lỗi khi lấy vị trí.');
            }
        }
    );
});