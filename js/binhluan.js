document.addEventListener("submit", async function(e) {
    const form = e.target;

    // ======================
    // COMMENT & REPLY
    // ======================
    if (form.matches(".comment-form") || form.matches(".reply-form")) {
        e.preventDefault();

        const postId = form.dataset.postId;
        const noiDung = form.querySelector("input[name=noi_dung]").value.trim();
        const parentId = form.querySelector("input[name=binh_luan_cha_id]")?.value || null;

        if (!noiDung) return;

        try {
            const res = await fetch(`/posts/${postId}/comments`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": window.csrf
                },
                body: JSON.stringify({
                    noi_dung: noiDung,
                    binh_luan_cha_id: parentId
                })
            });

            const data = await res.json();

            if (!data || !data.binh_luan_id) {
                alert("Lỗi: server không trả về ID bình luận.");
                return;
            }

            // Thêm comment/reply vào DOM
            if (form.matches(".comment-form")) {
                document.querySelector(`#comment-${postId} .comment-list`)
                    .insertAdjacentHTML("afterbegin", renderComment(data, postId, false));
            } else {
                form.insertAdjacentHTML("afterend", renderComment(data, postId, true));
            }

            form.querySelector("input[name=noi_dung]").value = "";

        } catch(err) {
            console.error(err);
            alert("Không kết nối được server!");
        }
    }

    // ======================
    // EDIT COMMENT
    // ======================
    else if (form.matches(".edit-form")) {
        e.preventDefault();
        const commentId = form.dataset.id;
        const noiDung = form.querySelector("input[name=noi_dung]").value.trim();
        if (!noiDung) return alert("Nội dung không được để trống!");

        try {
            // Lấy URL từ attribute action form, Blade đã render đúng route
            const url = form.getAttribute("action");

            const res = await fetch(url, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": window.csrf
                },
                body: JSON.stringify({ noi_dung: noiDung }) // Không cần _method
            });

            if (!res.ok) {
                const text = await res.text();
                console.error("Server lỗi:", text);
                return alert("Lỗi server! Kiểm tra console.");
            }

            const data = await res.json();

            if (data.success) {
                const commentContent = document.querySelector(`#edit-${commentId}`)
                    .closest(".comment")
                    .querySelector(".comment-content");

                if (commentContent) commentContent.textContent = data.noi_dung;

                document.getElementById(`edit-${commentId}`).style.display = "none";
                commentContent.style.background = "#ffffcc";
                setTimeout(() => commentContent.style.background = "", 1000);
            } else {
                alert(data.error || "Không thể sửa bình luận.");
            }

        } catch(err) {
            console.error(err);
            alert("Không kết nối được server!");
        }
    }
});

// ======================
// RENDER COMMENT / REPLY
// ======================
function renderComment(data, postId, isReply) {
    const id = data.binh_luan_id;

    return `
    <div class="comment p-2 rounded-3 mb-2 ms-${isReply ? 4 : 0}" style="background:#f8f9fa;">
        <strong>${data.ho_va_ten}</strong>: 
        <span class="comment-content">${data.noi_dung}</span><br>
        <small class="text-muted">${data.ngay_tao}</small>

        <button class="btn btn-sm btn-link p-0 mt-1" onclick="toggleReply(${id})">↩️ Trả lời</button>

        ${data.nguoi_dung_id == window.currentUserId ? `
        <button class="btn btn-sm btn-link p-0 mt-1" onclick="toggleEdit(${id})">✏️ Sửa</button>
        <div id="edit-${id}" style="display:none; margin-top:5px;">
            <form class="edit-form" data-id="${id}" 
                  action="/posts/comments/update/${id}">
                <input type="text" name="noi_dung" class="form-control mb-1" value="${data.noi_dung}">
                <button class="btn btn-sm btn-primary">Lưu</button>
            </form>
        </div>` : ''}

        <div id="reply-${id}" class="mt-2 ms-3" style="display:none;">
            <form class="reply-form" data-post-id="${postId}">
                <input type="hidden" name="binh_luan_cha_id" value="${id}">
                <div class="input-group">
                    <input type="text" name="noi_dung" class="form-control" required placeholder="Viết trả lời...">
                    <button class="btn btn-secondary">Gửi</button>
                </div>
            </form>
        </div>
    </div>`;
}

// ======================
// TOGGLE FUNCTIONS
// ======================
function toggleReply(id) {
    const box = document.getElementById("reply-" + id);
    if (!box) return;
    box.style.display = box.style.display === "none" ? "block" : "none";
}

function toggleEdit(id) {
    const box = document.getElementById("edit-" + id);
    if (!box) return;
    box.style.display = box.style.display === "none" ? "block" : "none";
}
function toggleComment(postId) {
    let box = document.getElementById("comment-box-" + postId);
    if (!box) return;

    // Toggle
    box.style.display = (box.style.display === "none" || box.style.display === "") 
        ? "block" 
        : "none";
}

document.addEventListener('DOMContentLoaded', () => {

    // Xóa comment hoặc reply
    window.deleteComment = function(commentId) {
        if (!confirm("Bạn có chắc muốn xóa bình luận này?")) return;

        fetch(`/posts/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.csrf,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Tìm comment/reply wrapper
                const commentEl = document.querySelector(`#comment-${commentId}`);
                if(commentEl) commentEl.remove();
            } else {
                alert(data.error || "Không thể xóa bình luận.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Lỗi server!");
        });
    }

    

});
