# Sport Shop - Ứng dụng Bán Hàng Thể Thao

## Tổng Quan Dự Án

Sport Shop là một ứng dụng e-commerce đầy đủ chức năng dành cho bán hàng thể thao, xây dựng bằng Laravel.

## Chức Năng Chính (Yêu Cầu Bắt Buộc)

### 1. Quản Lý Sản Phẩm/Dịch Vụ
- ✅ Hiển thị sản phẩm chi tiết (tên, mô tả, hình ảnh, giá, trạng thái)
- ✅ Hiển thị sản phẩm bán chạy nhất
- ✅ Phân loại theo danh mục
- ✅ Bộ lọc sản phẩm (danh mục, thương hiệu, giá)
- ✅ Tìm kiếm theo từ khóa
- ✅ Quản lý tồn kho (cập nhật số lượng)

### 2. Quản Lý Người Dùng
- ✅ Đăng ký tài khoản mới
- ✅ Đăng nhập/Đăng xuất
- ✅ Quản lý hồ sơ cá nhân
- ✅ Đổi mật khẩu
- ✅ Phân quyền (Admin/Customer)

### 3. Giỏ Hàng & Đặt Hàng
- ✅ Thêm/Xóa sản phẩm vào giỏ
- ✅ Cập nhật số lượng
- ✅ Xóa toàn bộ giỏ
- ✅ Đặt hàng với thông tin giao hàng

### 4. Thanh Toán
- ✅ Phương thức COD (Thanh toán khi nhận)
- ✅ Phương thức QR (Mã QR)
- ✅ Áp dụng mã giảm giá/voucher

### 5. Quản Lý Đơn Hàng
- ✅ Theo dõi trạng thái đơn hàng (pending, confirmed, shipping, completed, cancelled)
- ✅ Lịch sử đơn hàng cho khách hàng
- ✅ Xem chi tiết đơn hàng
- ✅ Hủy đơn hàng (chỉ khi ở trạng thái pending)
- ✅ Quản lý đơn hàng cho Admin

### 6. Hệ Thống Phản Hồi & Đánh Giá
- ✅ Đánh giá sản phẩm (1-5 sao)
- ✅ Bình luận về sản phẩm
- ✅ Hiển thị trung bình đánh giá

### 7. Thống Kê Doanh Số
- ✅ Báo cáo doanh thu
- ✅ Sản phẩm bán chạy nhất
- ✅ Thống kê theo phương thức thanh toán
- ✅ Xuất báo cáo CSV

## Chức Năng Phụ (Nâng Cao)

### 1. Cá Nhân Hóa & Gợi Ý
- ✅ Lịch sử duyệt sản phẩm
- ✅ Gợi ý sản phẩm dựa trên lịch sử
- ✅ Danh sách yêu thích (Wishlist)

### 2. Marketing & Khuyến Mãi
- ✅ Quản lý mã giảm giá/Voucher
- ✅ Mã có hạn sử dụng
- ✅ Giảm giá theo % hoặc cố định
- ✅ Ngày hiệu lực của mã

### 3. Hỗ Trợ Khách Hàng
- ⏳ FAQ (Sắp triển khai)
- ⏳ Live Chat (Sắp triển khai)
- ⏳ Hệ thống Ticket (Sắp triển khai)

### 4. Quản Lý Nội Dung
- ⏳ Trang tĩnh (Về chúng tôi, Liên hệ, Chính sách)
- ⏳ Blog/Tin tức

### 5. Tích Hợp Bên Thứ Ba
- ⏳ Hệ thống vận chuyển
- ⏳ Mạng xã hội

### 6. Phân Tích & Báo Cáo
- ✅ Phân tích hành vi người dùng (lịch sử duyệt)
- ✅ Báo cáo chi tiết doanh số

## Kiến Trúc Dự Án

### Cấu Trúc Thư Mục

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # Controllers cho phần Admin
│   │   ├── Api/                # API Controllers
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── ProductController.php
│   │   ├── OrderController.php
│   │   └── ...
│   └── Middleware/
│       ├── AdminMiddleware.php
│       └── RecordBrowsingHistory.php
├── Models/
│   ├── User.php
│   ├── Product.php
│   ├── Order.php
│   ├── Review.php
│   ├── Wishlist.php
│   ├── Discount.php
│   └── ...
├── Services/
│   ├── ProductRecommendationService.php
│   └── EmailService.php
├── Helpers/
│   └── PriceHelper.php
└── Policies/
    └── OrderPolicy.php
database/
├── migrations/     # Định nghĩa schema database
└── seeders/       # Dữ liệu test
routes/
├── web.php        # Routes cho web
└── api.php        # Routes cho API
resources/
├── views/         # Blade templates
└── js/
    └── app.js     # JavaScript
```

## Cơ Sở Dữ Liệu

### Các Bảng Chính

1. **users** - Người dùng (Admin/Customer)
2. **categories** - Danh mục sản phẩm
3. **products** - Sản phẩm
4. **product_variants** - Biến thể sản phẩm (size, màu sắc)
5. **orders** - Đơn hàng
6. **order_details** - Chi tiết đơn hàng
7. **reviews** - Đánh giá sản phẩm
8. **wishlists** - Danh sách yêu thích
9. **discounts** - Mã giảm giá/Voucher
10. **browsing_histories** - Lịch sử duyệt

## API Endpoints

### Products
```
GET /api/products                  # Danh sách sản phẩm
GET /api/products/{id}             # Chi tiết sản phẩm
GET /api/products/top-selling      # Sản phẩm bán chạy
GET /api/products/categories       # Danh mục
```

### Orders
```
GET /api/orders                    # Danh sách đơn hàng
GET /api/orders/{id}               # Chi tiết đơn hàng
POST /api/orders/{id}/cancel       # Hủy đơn hàng
```

### Wishlists
```
GET /api/wishlists                 # Danh sách yêu thích
POST /api/wishlists/add            # Thêm yêu thích
DELETE /api/wishlists/{product_id} # Xóa yêu thích
GET /api/wishlists/{product_id}/check # Kiểm tra
```

### Discounts
```
POST /api/discounts/validate       # Kiểm tra mã giảm giá
```

## Cài Đặt & Chạy Dự Án

### Yêu Cầu
- PHP >= 8.1
- Composer
- MySQL/SQLite
- Node.js (cho front-end)

### Cài Đặt

1. Clone dự án
```bash
git clone <repo-url>
cd sport_shop
```

2. Cài đặt dependencies
```bash
composer install
npm install
```

3. Cấu hình file .env
```bash
cp .env.example .env
php artisan key:generate
```

4. Chạy migrations
```bash
php artisan migrate
```

5. Seed dữ liệu test (tuỳ chọn)
```bash
php artisan db:seed
```

6. Chạy server
```bash
php artisan serve
npm run dev
```

7. Truy cập
- Frontend: http://localhost:8000
- Admin: http://localhost:8000/admin/dashboard

### Tài Khoản Test

- **Admin**
  - Email: admin@sportshop.com
  - Password: 123456

- **Customer** - Tạo tài khoản mới qua trang đăng ký

## Các Chức Năng Chính

### Cho Khách Hàng

1. **Duyệt Sản Phẩm**
   - Xem danh sách sản phẩm
   - Tìm kiếm theo từ khóa
   - Lọc theo danh mục, thương hiệu, giá
   - Xem chi tiết sản phẩm
   - Đánh giá sản phẩm

2. **Giỏ Hàng & Đặt Hàng**
   - Thêm sản phẩm vào giỏ
   - Cập nhật số lượng
   - Xóa sản phẩm
   - Áp dụng mã giảm giá
   - Đặt hàng với thông tin giao hàng

3. **Thanh Toán**
   - Thanh toán khi nhận (COD)
   - Thanh toán qua QR Code

4. **Quản Lý Đơn Hàng**
   - Xem lịch sử đơn hàng
   - Xem chi tiết đơn hàng
   - Hủy đơn hàng (nếu chưa được xác nhận)

5. **Wishlist**
   - Thêm sản phẩm vào danh sách yêu thích
   - Xem danh sách yêu thích
   - Xóa khỏi danh sách

6. **Hồ Sơ Cá Nhân**
   - Xem thông tin cá nhân
   - Chỉnh sửa thông tin
   - Đổi mật khẩu

### Cho Admin

1. **Dashboard**
   - Thống kê tổng quan
   - Doanh thu
   - Số đơn hàng
   - Sản phẩm bán chạy

2. **Quản Lý Sản Phẩm**
   - Thêm, sửa, xóa sản phẩm
   - Quản lý danh mục
   - Quản lý biến thể sản phẩm
   - Cập nhật tồn kho

3. **Quản Lý Đơn Hàng**
   - Xem danh sách đơn hàng
   - Cập nhật trạng thái đơn
   - Cập nhật trạng thái thanh toán

4. **Quản Lý Khuyến Mãi**
   - Tạo/sửa/xóa mã giảm giá
   - Quản lý số lần sử dụng
   - Thiết lập ngày hiệu lực

5. **Thống Kê & Báo Cáo**
   - Xem thống kê doanh số
   - Báo cáo chi tiết
   - Xuất báo cáo CSV

## Các Công Nghệ Sử Dụng

- **Backend**: Laravel 10
- **Database**: MySQL/SQLite
- **Frontend**: Blade (HTML/CSS/JavaScript)
- **Authentication**: Laravel Sanctum
- **API**: RESTful API
- **Validation**: Laravel Validation

## Các Lớp & Services

### Services

- **ProductRecommendationService**: Gợi ý sản phẩm
- **EmailService**: Gửi email (thông báo, xác nhận)

### Helpers

- **PriceHelper**: Định dạng, tính toán giá

### Policies

- **OrderPolicy**: Kiểm tra quyền truy cập đơn hàng

## Middleware

- **AdminMiddleware**: Kiểm tra quyền Admin
- **RecordBrowsingHistory**: Ghi lại lịch sử duyệt

## Future Enhancements (Có thể triển khai sau)

- [ ] Hệ thống FAQ
- [ ] Live Chat
- [ ] Blog/Tin tức
- [ ] Tích hợp cổng thanh toán thực (Stripe, PayPal)
- [ ] Tích hợp vận chuyển
- [ ] Đăng nhập bằng mạng xã hội
- [ ] Mobile App
- [ ] Notification system (email, SMS)
- [ ] Chương trình khách hàng thân thiết
- [ ] Advanced analytics

## Hỗ Trợ

Để báo cáo lỗi hoặc yêu cầu tính năng mới, vui lòng liên hệ qua email hoặc tạo issue.

## License

MIT License

---

**Phiên bản**: 1.0.0
**Ngày cập nhật**: 2026-08-16
