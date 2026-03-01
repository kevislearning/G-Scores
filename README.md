# G-Scores - Hệ thống tra cứu điểm thi THPT 2024

## Công nghệ sử dụng

- **Backend**: Laravel (PHP)
- **Database**: MySQL với Eloquent ORM
- **Frontend**: HTML5, CSS3, JavaScript, jQuery
- **Charts**: Chart.js

## Tính năng

1. **Tra cứu điểm theo số báo danh**: Nhập số báo danh (8 chữ số) để xem điểm tất cả các môn
2. **Thống kê điểm theo môn học**: Biểu đồ thống kê số lượng thí sinh theo 4 mức điểm:
    - Giỏi: >= 8 điểm
    - Khá: 6-8 điểm
    - Trung bình: 4-6 điểm
    - Yếu: < 4 điểm
3. **Top 10 khối A**: Danh sách 10 thí sinh có điểm cao nhất khối A (Toán + Vật Lí + Hóa Học)

## Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- MySQL

---

## Cách 1: Sử dụng Laravel built-in server + MySQL cài đặt riêng

### Bước 1: Cài đặt dependencies

```bash
cd g-scores
composer install
```

### Bước 2: Tạo database MySQL

Mở MySQL client và tạo database:

```sql
CREATE DATABASE g_scores;
```

### Bước 3: Cấu hình file `.env`

Sao chép file `.env.example` thành `.env`:

```bash
cp .env.example .env
```

Chỉnh sửa file `.env` với thông tin MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=g_scores
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

### Bước 4: Tạo application key

```bash
php artisan key:generate
```

### Bước 5: Chạy migration và import dữ liệu

```bash
php artisan migrate
php artisan db:seed
```

### Bước 6: Khởi chạy server

```bash
php artisan serve
```

Truy cập trang web tại: **http://localhost:8000**

---

## Cách 2: Sử dụng XAMPP

### Bước 1: Cài đặt và khởi động XAMPP

Mở XAMPP Control Panel và start **Apache** và **MySQL**.

### Bước 2: Tạo database

1. Truy cập phpMyAdmin: http://localhost/phpmyadmin
2. Click **"New"** ở sidebar trái
3. Nhập tên database: `g_scores`
4. Click **"Create"**

### Bước 3: Cài đặt dependencies

```bash
cd g-scores
composer install
```

### Bước 4: Cấu hình file `.env`

Sao chép file `.env.example` thành `.env`:

```bash
cp .env.example .env
```

Chỉnh sửa file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=g_scores
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 5: Tạo application key

```bash
php artisan key:generate
```

### Bước 6: Chạy migration và import dữ liệu

```bash
php artisan migrate
php artisan db:seed
```

### Bước 7: Khởi chạy server

**Cách A - Sử dụng Laravel built-in server (khuyến nghị):**

```bash
php artisan serve
```

Truy cập: **http://localhost:8000**

**Cách B - Sử dụng Apache của XAMPP:**

1. Copy hoặc di chuyển thư mục `g-scores` vào `C:\xampp\htdocs\`
2. Truy cập: **http://localhost/g-scores/public**

---

