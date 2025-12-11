document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if(!confirm('Bạn có chắc muốn xóa bài viết này?')) return;

            const postId = this.dataset.postId;

            fetch('/posts/' + postId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Xóa DOM card bài viết
                    const card = document.getElementById('post-' + postId);
                    if(card) card.remove();
                } else {
                    alert('Xóa thất bại!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Có lỗi xảy ra!');
            });
        });
    });
});
