# BÁO CÁO BÀI TẬP LỚN
## ĐỀ TÀI: XÂY DỰNG HỆ THỐNG QUẢN LÝ NHÀ THUỐC VÀ DƯỢC PHẨM (PHARMACARE)

---

## CHƯƠNG 1: TỔNG QUAN ĐỀ TÀI

### 1.1. Lý do chọn đề tài
Trong hoạt động kinh doanh dược phẩm và vận hành nhà thuốc, việc quản lý danh mục thuốc, theo dõi số lượng tồn, kiểm soát hạn sử dụng (Date thuốc), quản lý nguồn cung ứng và lập hóa đơn bán lẻ tại quầy đóng vai trò sống còn. Nếu quản lý thủ công bằng sổ sách hoặc bảng tính rời rạc, nhà thuốc rất dễ gặp phải các rủi ro:
- Không kiểm soát kịp thời các loại thuốc **sắp hết hạn sử dụng** (trong vòng 30 ngày) hoặc **đã quá hạn**, gây nguy hại cho người bệnh và thiệt hại kinh tế.
- Sai lệch số lượng **tồn thực tế** khi bán hàng hoặc khi hủy/hoàn trả hóa đơn.
- Khó khăn trong việc thống kê doanh thu theo ngày, tra cứu lịch sử bán hàng và phân quyền giữa Quản trị viên (Chủ nhà thuốc) và Dược sĩ bán hàng.

Xuất phát từ thực tiễn đó, đề tài **"Xây dựng Hệ thống Quản lý Nhà thuốc và Dược phẩm — PharmaCare"** được thực hiện nhằm số hóa toàn diện quy trình quản lý dược phẩm, danh mục, nhà cung cấp, nhân sự và hóa đơn bán lẻ trên nền tảng Web hiện đại.

### 1.2. Mục tiêu của hệ thống
- **Quản lý tập trung dữ liệu dược phẩm:** Phân loại thuốc theo nhóm điều trị, nhà cung cấp, đơn vị tính, giá niêm yết, số lượng tồn và hạn sử dụng.
- **Cảnh báo thông minh:** Tự động phát hiện và cảnh báo thuốc sắp hết hàng (<= 10 đơn vị), hết hàng (0 đơn vị), sắp hết hạn (<= 30 ngày) hoặc đã quá hạn.
- **Tự động hóa quy trình bán hàng:** Lập hóa đơn bán lẻ nhiều mặt hàng cùng lúc, tự động kiểm tra và khấu trừ số lượng tồn trong giao dịch an toàn (`Database Transaction`), đồng thời hoàn trả số lượng tự động khi hủy hóa đơn.
- **Phân quyền chặt chẽ:** Bảo mật hệ thống theo 2 vai trò người dùng (`Admin` và `Pharmacist`) và trạng thái hoạt động (`Active` / `Inactive`), trong đó chỉ **Admin** mới có quyền nhìn thấy và truy cập chức năng **Quản lý Nhà cung cấp** và **Quản lý Nhân viên**.

### 1.3. Phạm vi và đối tượng sử dụng
| Vai trò | Mã vai trò (`role`) | Quyền hạn trong hệ thống |
| :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin` | Toàn quyền hệ thống: Thống kê Dashboard, Quản lý Thuốc, Danh mục, Hóa đơn bán hàng và độc quyền **Quản lý Nhà cung cấp** & **Quản lý Nhân viên**. |
| **Dược sĩ bán hàng** | `pharmacist` | Xem Dashboard, quản lý/tra cứu Thuốc và Danh mục thuốc, lập Hóa đơn bán lẻ và quản lý hóa đơn. |

---

## CHƯƠNG 2: CÔNG NGHỆ SỬ DỤNG

Hệ thống được xây dựng dựa trên kiến trúc **MVC (Model – View – Controller)** chuẩn của framework **Laravel 12**:

- **Ngôn ngữ & Backend Framework:** PHP `^8.2`, **Laravel Framework `^12.0`**.
- **Cơ sở dữ liệu (DBMS):** Tương thích đa hệ quản trị CSDL thông qua **Eloquent ORM** và **Query Builder** (hỗ trợ SQLite và MySQL/MariaDB).
- **Giao diện người dùng (Frontend):**
  - **Laravel Blade Template Engine**.
  - **Bootstrap 5.3.3** & **Bootstrap Icons 1.11.3** kết hợp bộ font hiện đại `Plus Jakarta Sans`.
- **Bảo mật & Xác thực:**
  - Mã hóa mật khẩu một chiều bằng `Bcrypt` (`Hash::make`).
  - Bảo vệ form chống tấn công giả mạo truy vấn bằng `@csrf` token.
  - Kiểm soát truy cập qua Custom Middleware `AdminMiddleware`.

---

## CHƯƠNG 3: PHÂN TÍCH VÀ THIẾT KẾ HỆ THỐNG

### 3.1. Kiến trúc phân quyền chức năng
- **Public Routes (`guest`):** Trang đăng nhập (`GET /login`, `POST /login`).
- **Protected Routes (`admin` middleware):** Yêu cầu người dùng đã đăng nhập và tài khoản đang hoạt động (`status = active`), bao gồm:
  - Bảng điều khiển (`/dashboard`)
  - Quản lý Thuốc (`/medicines`)
  - Quản lý Danh mục (`/categories`)
  - Quản lý Hóa đơn bán lẻ (`/invoices`, `/invoices/{invoice}/cancel`)
- **Admin-Only Routes (`admin:only` middleware):** Chỉ cho phép tài khoản có `role = admin` nhìn thấy trên menu và truy cập 2 phân hệ:
  - Quản lý Nhà cung cấp (`/suppliers`)
  - Quản lý Nhân viên (`/staff`)

### 3.2. Thiết kế Cơ sở dữ liệu (6 bảng chính)

#### 1. Bảng `users` (Quản lý nhân viên và tài khoản)
| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | `bigint` | PK, Auto Increment | Mã định danh nhân viên |
| `name` | `varchar(255)` | Not Null | Họ và tên nhân viên |
| `email` | `varchar(255)` | Unique, Not Null | Email đăng nhập |
| `phone` | `varchar(20)` | Nullable | Số điện thoại liên hệ |
| `role` | `varchar(50)` | Default: `pharmacist` | Vai trò: `admin`, `pharmacist` |
| `status` | `varchar(50)` | Default: `active` | Trạng thái: `active`, `inactive` |
| `salary` | `decimal(12,2)` | Default: `0` | Mức lương cơ bản (VNĐ) |
| `password` | `varchar(255)` | Not Null | Mật khẩu đã mã hóa Bcrypt |
| `created_at`, `updated_at` | `timestamp` | Nullable | Thời gian tạo và cập nhật |

#### 2. Bảng `categories` (Danh mục thuốc điều trị)
| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | `bigint` | PK, Auto Increment | Mã danh mục |
| `name` | `varchar(255)` | Unique, Not Null | Tên nhóm thuốc điều trị |
| `description` | `text` | Nullable | Mô tả công dụng nhóm thuốc |
| `created_at`, `updated_at` | `timestamp` | Nullable | Thời gian tạo và cập nhật |

#### 3. Bảng `suppliers` (Nhà cung cấp dược phẩm)
| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | `bigint` | PK, Auto Increment | Mã định danh nhà cung cấp |
| `code` | `varchar(50)` | Unique, Not Null | Mã nhà cung cấp (VD: `NCC001`) |
| `name` | `varchar(255)` | Not Null | Tên công ty cung ứng dược phẩm |
| `phone` | `varchar(20)` | Nullable | Số điện thoại liên hệ |
| `email` | `varchar(255)` | Nullable | Địa chỉ email |
| `address` | `varchar(255)` | Nullable | Địa chỉ trụ sở |
| `description` | `text` | Nullable | Ghi chú / tiêu chuẩn chất lượng |

#### 4. Bảng `medicines` (Thông tin thuốc)
| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | `bigint` | PK, Auto Increment | Mã định danh thuốc |
| `code` | `varchar(50)` | Unique, Not Null | Mã thuốc (VD: `PARA500`) |
| `name` | `varchar(255)` | Not Null | Tên thương mại / hoạt chất thuốc |
| `category_id` | `bigint` | FK (`categories.id`) | Thuộc danh mục thuốc |
| `supplier_id` | `bigint` | FK (`suppliers.id`), Nullable | Thuộc nhà cung cấp |
| `unit` | `varchar(50)` | Not Null | Đơn vị tính (`Hộp`, `Vỉ`, `Viên`, `Chai`, `Tuýp`...) |
| `price` | `decimal(12,2)` | Not Null | Giá bán niêm yết (VNĐ) |
| `stock` | `integer` | Not Null, Default: `0` | Số lượng tồn hiện tại |
| `expiry_date` | `date` | Not Null | Hạn sử dụng của thuốc |
| `description` | `text` | Nullable | Công dụng, chỉ định, liều dùng |

#### 5. Bảng `invoices` (Hóa đơn bán lẻ)
| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | `bigint` | PK, Auto Increment | Mã định danh hóa đơn |
| `code` | `varchar(50)` | Unique, Not Null | Mã hóa đơn (`HD-YYYYMMDD-XXXX`) |
| `customer_name` | `varchar(255)` | Default: `Khách lẻ` | Tên khách hàng mua thuốc |
| `customer_phone` | `varchar(20)` | Nullable | Số điện thoại khách hàng |
| `user_id` | `bigint` | FK (`users.id`) | Nhân viên/Dược sĩ lập hóa đơn |
| `total_amount` | `decimal(14,2)` | Default: `0` | Tổng tiền thanh toán (VNĐ) |
| `payment_method` | `varchar(50)` | Default: `cash` | `cash` (Tiền mặt), `transfer` (Chuyển khoản), `card` (Thẻ) |
| `status` | `varchar(50)` | Default: `paid` | `paid` (Đã thanh toán), `cancelled` (Đã hủy) |
| `note` | `text` | Nullable | Ghi chú dặn dò liều uống |

#### 6. Bảng `invoice_details` (Chi tiết hóa đơn bán thuốc)
| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | `bigint` | PK, Auto Increment | Mã dòng chi tiết |
| `invoice_id` | `bigint` | FK (`invoices.id`) | Thuộc hóa đơn bán lẻ |
| `medicine_id` | `bigint` | FK (`medicines.id`) | Loại thuốc được bán |
| `quantity` | `integer` | Not Null | Số lượng bán |
| `unit_price` | `decimal(12,2)` | Not Null | Đơn giá bán tại thời điểm lập đơn |
| `total_price` | `decimal(14,2)` | Not Null | Thành tiền (`quantity * unit_price`) |

---

## CHƯƠNG 4: CHI TIẾT CÁC PHÂN HỆ CHỨC NĂNG

### 4.1. Phân hệ Đăng nhập & Bảo mật (`AuthController` & `AdminMiddleware`)
- Xác thực người dùng bằng Email và Mật khẩu, hỗ trợ ghi nhớ đăng nhập (`remember`).
- Tự động kiểm tra trạng thái tài khoản: Nếu nhân viên bị khóa (`status = inactive`), hệ thống từ chối cho phép truy cập và hủy phiên làm việc.
- Phân quyền rõ ràng giữa Dược sĩ bán hàng và Quản trị viên (`AdminMiddleware`): chỉ Admin mới nhìn thấy và truy cập được chức năng **Nhà cung cấp** và **Nhân viên**.

### 4.2. Phân hệ Bảng điều khiển (`DashboardController`)
- Tổng hợp các chỉ số quan trọng nhất của nhà thuốc theo thời gian thực:
  - Tổng doanh thu tích lũy và Doanh thu trong ngày hôm nay.
  - Tổng số hóa đơn thành công, tổng số mặt hàng thuốc, tổng số lượng thuốc hiện có.
  - Cảnh báo số lượng thuốc sắp hết hàng (`stock <= 10`) và thuốc sắp hết hạn sử dụng trong vòng 30 ngày.
  - Thống kê Nhà cung cấp và Đội ngũ nhân viên (chỉ hiển thị đối với Admin).

### 4.3. Phân hệ Quản lý Thuốc & Danh mục (`MedicineController` & `CategoryController`)
- Hiển thị danh sách thuốc phân trang kèm thông tin Danh mục và Nhà cung cấp.
- Hỗ trợ tìm kiếm theo tên/mã thuốc và lọc kết hợp theo:
  - Danh mục điều trị.
  - Tình trạng số lượng (`low`: sắp hết hàng <= 10, `out`: hết hàng = 0).
  - Tình trạng hạn sử dụng (`expiring`: sắp hết hạn trong 30 ngày, `expired`: đã quá hạn).
- Quản lý nhóm danh mục điều trị kèm kiểm tra ràng buộc không cho phép xóa danh mục đang có thuốc.

### 4.4. Phân hệ Bán hàng & Quản lý Hóa đơn (`InvoiceController`)
- **Lập hóa đơn bán lẻ:**
  - Cho phép chọn nhiều loại thuốc trong cùng một hóa đơn, tự động lấy đơn giá niêm yết nếu người dùng không nhập giá tùy chỉnh.
  - Sử dụng `DB::beginTransaction()` đảm bảo tính toàn vẹn: Kiểm tra số lượng từng loại thuốc -> Tạo chi tiết hóa đơn -> Trừ số lượng (`decrement('stock')`) -> Tính tổng tiền hóa đơn. Nếu bất kỳ mặt hàng nào vượt quá số lượng hiện có, toàn bộ giao dịch được `rollBack()` và thông báo lỗi chi tiết.
- **Xem và In hóa đơn:** Giao diện chi tiết hóa đơn rõ ràng, hỗ trợ xem trước bản in cho khách hàng.
- **Hủy hóa đơn & Hoàn trả số lượng:** Khi hủy hóa đơn đã lập, hệ thống chuyển trạng thái sang `cancelled` và tự động cộng hoàn trả số lượng thuốc (`increment('stock')`).

### 4.5. Phân hệ Quản lý Nhà cung cấp & Nhân viên (`SupplierController` & `StaffController` — Chỉ Admin)
- **Quản lý Nhà cung cấp (`admin:only`):** Thêm, sửa, xóa, tìm kiếm đối tác cung ứng dược phẩm; chặn xóa nhà cung cấp khi vẫn còn thuốc của đơn vị đó trong hệ thống.
- **Quản lý Nhân viên (`admin:only`):** Quản lý hồ sơ, phân quyền chức vụ (`Admin` / `Dược sĩ`), mức lương, trạng thái làm việc và mật khẩu của nhân viên; tích hợp cơ chế bảo vệ không cho phép Admin tự xóa tài khoản đang đăng nhập của chính mình.

---

## CHƯƠNG 5: KẾT LUẬN VÀ HƯỚNG PHÁT TRIỂN

### 5.1. Kết quả đạt được
- Hoàn thiện hệ thống quản lý nhà thuốc **PharmaCare** đáp ứng đầy đủ các nghiệp vụ từ quản lý danh mục, thuốc, hạn sử dụng, bán hàng đến quản lý nhà cung cấp và nhân sự.
- Giao diện người dùng đồng nhất, trực quan, dễ thao tác, phản hồi thông báo lỗi và thành công hoàn toàn bằng tiếng Việt.
- Đảm bảo an toàn dữ liệu và phân quyền chặt chẽ giữa Quản trị viên và Dược sĩ bán hàng.

### 5.2. Hướng phát triển
- Tích hợp máy quét mã vạch (Barcode) để thêm nhanh thuốc vào hóa đơn bán lẻ.
- Xây dựng thêm báo cáo biểu đồ doanh thu theo tháng/quý và xuất báo cáo Excel/PDF.
