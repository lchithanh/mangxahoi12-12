sequenceDiagram
    participant U as 👤 Người dùng
    participant V as 📱 View/Màn hình
    participant S as 🖥️ Hệ thống
    participant C as 👑 Chủ bài viết
    
    Note over U,S: === 1. TRUY CẬP ===
    U->>V: Truy cập chi tiết bài viết
    V->>S: Yêu cầu nội dung bài viết
    S-->>V: Trả về bài viết
    V-->>U: Hiển thị bài viết
    
    Note over U,S: === 2. MỞ FORM ĐÁNH GIÁ ===
    U->>V: Click nút đánh giá
    V-->>U: Hiển thị form đánh giá
    
    Note over U,S: === 3. ĐIỀN & GỬI ===
    U->>V: Điền thông tin đánh giá
    U->>V: Gửi đánh giá
    V->>S: Gửi dữ liệu đánh giá
    
    Note over U,S: === 4. KIỂM TRA HỢP LỆ ===
    S->>S: Đầm tra hợp lệ
    
    alt KHÔNG HỢP LỆ
        S-->>V: Thông báo "Lỗi đánh giá"
        V-->>U: Hiển thị lỗi
        U->>V: Sửa lỗi
        V->>S: Gửi lại
    else HỢP LỆ
        S-->>V: "Đánh giá thành công"
        V-->>U: Hiển thị thông báo thành công
        
        S->>C: Tạo thông báo cho chủ bài viết
        Note right of S: Thông báo có đánh giá mới
        
        S->>V: Cập nhật hiển thị đánh giá
        V-->>U: Hiển thị đánh giá vừa gửi
    end
    
    Note over U,S: === 5. KẾT THÚC ===
    U->>V: Quay lại bài viết
    V-->>U: Hiển thị bài viết đã có đánh giá