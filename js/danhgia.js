document.addEventListener('DOMContentLoaded', () => {

    // Xử lý click sao đánh giá
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', async () => {
            const value = parseInt(star.dataset.value);
            const container = star.closest('.user-rating');
            const postId = container.dataset.postId;

            // Gửi sao mới hoặc cập nhật sao cũ
            const res = await fetch(`/posts/${postId}/rate`, {
                method: 'PUT',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': window.csrf
                },
                body: JSON.stringify({ so_sao: value })
            });

            const data = await res.json();

            // Cập nhật sao người dùng
            container.querySelectorAll('i').forEach((s, idx) => {
                s.className = idx < value ? 'bi bi-star-fill text-warning' : 'bi bi-star text-secondary';
            });

            // Cập nhật sao trung bình với half star
            if(data.diem_trung_binh !== undefined){
                const avgDiv = document.querySelector(`.avg-rating[data-post-id="${postId}"]`);
                const starsContainer = avgDiv.querySelectorAll('i');
                const avgScore = avgDiv.querySelector('small');

                const avg = parseFloat(data.diem_trung_binh);
                const fullStars = Math.floor(avg);
                const halfStar = (avg - fullStars) >= 0.5 ? 1 : 0;
                const emptyStars = 5 - fullStars - halfStar;

                starsContainer.forEach((s, idx) => {
                    if(idx < fullStars){
                        s.className = 'bi bi-star-fill text-warning fs-5';
                    } else if(idx === fullStars && halfStar){
                        s.className = 'bi bi-star-half text-warning fs-5';
                    } else {
                        s.className = 'bi bi-star text-secondary fs-5';
                    }
                });

                // Cập nhật số lượt đánh giá và điểm trung bình
                avgScore.textContent = `(${avg.toFixed(1)} / 5 từ ${data.luot_danh_gia} đánh giá)`;
            }
        });
    });

    // Xử lý click vào số lượt đánh giá để mở modal danh sách người đánh giá
    document.querySelectorAll('.avg-rating small').forEach(small => {
        small.style.cursor = 'pointer';
        small.addEventListener('click', async () => {
            const avgDiv = small.closest('.avg-rating');
            const postId = avgDiv.dataset.postId;

            // Lấy danh sách người đánh giá từ server
            const res = await fetch(`/posts/${postId}/raters`);
            const data = await res.json();

            const list = document.getElementById('ratersList');
            list.innerHTML = '';

            if(data.length === 0){
                list.innerHTML = '<li class="list-group-item">Chưa có đánh giá nào</li>';
            } else {
                data.forEach(user => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex align-items-center';
                    li.innerHTML = `
                        <img src="${user.avatar ? '/storage/' + user.avatar : 'https://via.placeholder.com/30'}"
                             class="rounded-circle me-2" width="30" height="30">
                        ${user.name} - ⭐ ${user.rating}
                    `;
                    list.appendChild(li);
                });
            }

            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('ratersModal'));
            modal.show();
        });
    });

});
