document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('.save-toggle-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const postId = btn.dataset.postId;
            const isSaved = btn.dataset.saved == 1;
            const isSavedPage = btn.dataset.savedPage == 1;
            const method = isSaved ? 'DELETE' : 'POST';

            try {
                const res = await fetch(`/posts/${postId}/save`, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if ((data.saved === true || data.saved === false) || data.success) {
                    if (isSavedPage) {
                        // Nếu ở trang Saved, xóa thẻ bài viết khỏi DOM
                        const card = document.getElementById('post-' + postId);
                        if (card) card.remove();
                    } else {
                        // Toggle trạng thái nút
                        btn.dataset.saved = data.saved ? 1 : 0;
                        if (data.saved) {
                            btn.classList.remove('btn-outline-secondary');
                            btn.classList.add('btn-success');
                            btn.textContent = 'Hủy lưu';
                        } else {
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-secondary');
                            btn.textContent = 'Lưu bài viết';
                        }
                    }
                }

            } catch (err) {
                console.error(err);
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    });
});
