# Routes và API Documentation

## Web Routes

### Public Routes

| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/` | HomeController | index | Trang chủ |
| GET | `/products` | ProductController | index | Danh sách sản phẩm |
| GET | `/products/{product}` | ProductController | show | Chi tiết sản phẩm |

### Authentication Routes

| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/register` | AuthController | registerForm | Form đăng ký |
| POST | `/register` | AuthController | register | Đăng ký tài khoản |
| GET | `/login` | AuthController | loginForm | Form đăng nhập |
| POST | `/login` | AuthController | login | Đăng nhập |
| POST | `/logout` | AuthController | logout | Đăng xuất |

### Cart Routes

| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/cart` | CartController | index | Xem giỏ hàng |
| POST | `/cart/add` | CartController | add | Thêm vào giỏ |
| PATCH | `/cart/update/{variantId}` | CartController | update | Cập nhật số lượng |
| DELETE | `/cart/remove/{variantId}` | CartController | remove | Xóa từ giỏ |
| DELETE | `/cart/clear` | CartController | clear | Xóa toàn bộ giỏ |

### Checkout Routes (Auth)

| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/checkout` | CheckoutController | index | Trang thanh toán |
| POST | `/checkout` | CheckoutController | store | Tạo đơn hàng |
| GET | `/checkout/success/{order}` | CheckoutController | success | Trang thành công |

### User Routes (Auth)

| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/profile` | ProfileController | show | Xem hồ sơ |
| GET | `/profile/edit` | ProfileController | edit | Form chỉnh sửa |
| PUT | `/profile` | ProfileController | update | Cập nhật hồ sơ |
| GET | `/profile/password` | ProfileController | passwordForm | Form đổi mật khẩu |
| POST | `/profile/password` | ProfileController | updatePassword | Đổi mật khẩu |
| GET | `/orders` | OrderController | index | Danh sách đơn hàng |
| GET | `/orders/{order}` | OrderController | show | Chi tiết đơn hàng |
| POST | `/orders/{order}/cancel` | OrderController | cancel | Hủy đơn hàng |
| GET | `/wishlists` | WishlistController | index | Danh sách yêu thích |
| POST | `/wishlists/add` | WishlistController | add | Thêm yêu thích |
| DELETE | `/wishlists/{product}` | WishlistController | remove | Xóa yêu thích |
| GET | `/wishlists/{product}/check` | WishlistController | check | Kiểm tra yêu thích |
| POST | `/products/{product}/reviews` | ReviewController | store | Thêm đánh giá |
| GET | `/payment/qr/{order}` | PaymentController | qr | Trang thanh toán QR |
| POST | `/payment/qr/{order}/confirm` | PaymentController | confirm | Xác nhận thanh toán QR |

### Admin Routes (Auth + Admin)

#### Dashboard
| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/admin/dashboard` | DashboardController | index | Dashboard |

#### Products
| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/admin/products` | Admin\ProductController | index | Danh sách sản phẩm |
| GET | `/admin/products/create` | Admin\ProductController | create | Form tạo |
| POST | `/admin/products` | Admin\ProductController | store | Lưu sản phẩm |
| GET | `/admin/products/{product}` | Admin\ProductController | show | Chi tiết sản phẩm |
| GET | `/admin/products/{product}/edit` | Admin\ProductController | edit | Form chỉnh sửa |
| PUT | `/admin/products/{product}` | Admin\ProductController | update | Cập nhật sản phẩm |
| DELETE | `/admin/products/{product}` | Admin\ProductController | destroy | Xóa sản phẩm |

#### Product Variants
| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| POST | `/admin/products/{product}/variants` | Admin\ProductVariantController | store | Tạo biến thể |
| PUT | `/admin/variants/{variant}` | Admin\ProductVariantController | update | Cập nhật biến thể |
| DELETE | `/admin/variants/{variant}` | Admin\ProductVariantController | destroy | Xóa biến thể |

#### Categories
| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/admin/categories` | Admin\CategoryController | index | Danh sách danh mục |
| POST | `/admin/categories` | Admin\CategoryController | store | Tạo danh mục |
| PUT | `/admin/categories/{category}` | Admin\CategoryController | update | Cập nhật danh mục |
| DELETE | `/admin/categories/{category}` | Admin\CategoryController | destroy | Xóa danh mục |

#### Orders
| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/admin/orders` | Admin\OrderController | index | Danh sách đơn hàng |
| GET | `/admin/orders/{order}` | Admin\OrderController | show | Chi tiết đơn hàng |
| PATCH | `/admin/orders/{order}/status` | Admin\OrderController | updateStatus | Cập nhật trạng thái |
| PATCH | `/admin/orders/{order}/payment` | Admin\OrderController | updatePayment | Cập nhật thanh toán |

#### Discounts
| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/admin/discounts` | Admin\DiscountController | index | Danh sách mã giảm |
| GET | `/admin/discounts/create` | Admin\DiscountController | create | Form tạo |
| POST | `/admin/discounts` | Admin\DiscountController | store | Lưu mã giảm |
| GET | `/admin/discounts/{discount}/edit` | Admin\DiscountController | edit | Form chỉnh sửa |
| PUT | `/admin/discounts/{discount}` | Admin\DiscountController | update | Cập nhật mã giảm |
| DELETE | `/admin/discounts/{discount}` | Admin\DiscountController | destroy | Xóa mã giảm |

#### Statistics
| Method | Route | Controller | Action | Mô Tả |
|--------|-------|------------|--------|-------|
| GET | `/admin/statistics` | Admin\StatisticsController | index | Thống kê tổng quan |
| GET | `/admin/statistics/report` | Admin\StatisticsController | report | Báo cáo chi tiết |
| GET | `/admin/statistics/export` | Admin\StatisticsController | export | Xuất CSV |

---

## API Routes (`/api`)

### Public API

#### Products
```
GET    /api/products              # Danh sách sản phẩm (phân trang)
GET    /api/products/{product}    # Chi tiết sản phẩm
GET    /api/products/top-selling  # Sản phẩm bán chạy (top 5)
GET    /api/products/categories   # Danh sách danh mục
```

**Query Parameters (GET /api/products)**:
- `search` - Tìm kiếm theo tên
- `category_id` - Lọc theo danh mục
- `brand` - Lọc theo thương hiệu
- `sort` - Sắp xếp (price_asc, price_desc)
- `per_page` - Số sản phẩm mỗi trang (default: 12)

### Authenticated API (require `Authorization: Bearer {token}`)

#### Orders
```
GET    /api/orders              # Danh sách đơn hàng của user
GET    /api/orders/{order}      # Chi tiết đơn hàng
POST   /api/orders/{order}/cancel  # Hủy đơn hàng
```

#### Wishlists
```
GET    /api/wishlists              # Danh sách yêu thích
POST   /api/wishlists/add          # Thêm vào yêu thích
DELETE /api/wishlists/{product}    # Xóa khỏi yêu thích
GET    /api/wishlists/{product}/check  # Kiểm tra trong yêu thích
```

#### Discounts
```
POST   /api/discounts/validate  # Kiểm tra & tính toán mã giảm giá
```

**Request body (POST /api/discounts/validate)**:
```json
{
  "code": "SUMMER2024",
  "amount": 1000000
}
```

**Response**:
```json
{
  "success": true,
  "discount": {
    "code": "SUMMER2024",
    "discount_type": "percentage",
    "discount_value": 10,
    "discount_amount": 100000,
    "final_amount": 900000
  }
}
```

---

## Thông Số Yêu Cầu & Response

### Order Status (Trạng thái đơn hàng)
- `pending` - Đang chờ xử lý
- `confirmed` - Đã xác nhận
- `shipping` - Đang vận chuyển
- `completed` - Hoàn thành
- `cancelled` - Đã hủy

### Payment Status (Trạng thái thanh toán)
- `unpaid` - Chưa thanh toán
- `paid` - Đã thanh toán
- `failed` - Thanh toán thất bại

### Payment Method (Phương thức thanh toán)
- `cod` - Thanh toán khi nhận
- `qr` - Thanh toán qua QR Code

### Discount Type (Loại mã giảm)
- `percentage` - Giảm theo %
- `fixed` - Giảm cố định

### User Role (Vai trò)
- `admin` - Quản trị viên
- `customer` - Khách hàng

### Review Rating (Đánh giá)
- 1-5 sao

---

## Error Responses

### 400 Bad Request
```json
{
  "success": false,
  "message": "Mô tả lỗi",
  "errors": {
    "field_name": ["Lỗi 1", "Lỗi 2"]
  }
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Bạn không có quyền truy cập tài nguyên này"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Tài nguyên không tìm thấy"
}
```

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Vui lòng đăng nhập để tiếp tục"
}
```

---

## Ví Dụ Sử Dụng

### JavaScript/Fetch

**Lấy danh sách sản phẩm**:
```javascript
fetch('/api/products?search=Nike&category_id=1')
  .then(r => r.json())
  .then(data => console.log(data))
```

**Thêm vào Wishlist**:
```javascript
fetch('/api/wishlists/add', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({ product_id: 1 })
})
.then(r => r.json())
.then(data => console.log(data))
```

**Kiểm tra mã giảm giá**:
```javascript
fetch('/api/discounts/validate', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({ 
    code: 'SUMMER2024',
    amount: 1000000
  })
})
.then(r => r.json())
.then(data => console.log(data))
```

---

**Lần cập nhật cuối**: 2026-08-16
