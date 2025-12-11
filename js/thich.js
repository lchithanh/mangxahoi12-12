document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.btn-like');

    buttons.forEach(btn => {
        btn.addEventListener('click', async () => {
            const postId = btn.dataset.id;

            try {
                const res = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrf, // CSRF token
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await res.json();

                if (data.error) {
                    alert(data.error); // Chưa đăng nhập
                    return;
                }

                const icon = btn.querySelector('i');

                if (data.liked) {
                    // Chuyển sang trái tim đỏ
                    icon.classList.replace('bi-heart', 'bi-heart-fill');
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-danger');
                } else {
                    // Quay về trái tim rỗng
                    icon.classList.replace('bi-heart-fill', 'bi-heart');
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                }

                // Cập nhật số lượt like
                btn.querySelector('.like-count').textContent = data.count;

            } catch (err) {
                console.error('Lỗi like bài viết:', err);
            }
        });
    });
});

// Hiển thị danh sách người like
async function showLikers(postId) {
    try {
        const res = await fetch(`/posts/${postId}/likers`);
        const data = await res.json();

        const list = document.getElementById('likersList');
        list.innerHTML = '';

        if (data.length === 0) {
            list.innerHTML = '<li class="list-group-item">Chưa ai thích</li>';
        } else {
            data.forEach(user => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex align-items-center';
                li.innerHTML = `
                    <img src="${user.avatar ? '/storage/' + user.avatar : 'https://via.placeholder.com/30'}"
                         class="rounded-circle me-2" width="30" height="30">
                    ${user.name}
                `;
                list.appendChild(li);
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('likersModal'));
        modal.show();

    } catch (err) {
        console.error('Lỗi lấy danh sách người thích:', err);
    }
}
