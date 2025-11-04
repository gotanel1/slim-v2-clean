# Clean Architecture PHP API 🚀

REST API ที่สร้างด้วย PHP ตาม Clean Architecture principles พร้อม Authentication JWT และเชื่อมต่อกับ SQL Server 2008 R2

[![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-blue)](https://www.php.net/)
[![Slim Framework](https://img.shields.io/badge/Slim-2.x-green)](https://www.slimframework.com/)
[![SQL Server](https://img.shields.io/badge/SQL%20Server-2008%20R2-red)](https://www.microsoft.com/sql-server)

---

## 📋 สารบัญ

- [คุณสมบัติ](#-คุณสมบัติ)
- [สถาปัตยกรรม](#-สถาปัตยกรรม)
- [เทคโนโลยีที่ใช้](#-เทคโนโลยีที่ใช้)
- [การติดตั้ง](#-การติดตั้ง)
- [การตั้งค่า](#-การตั้งค่า)
- [API Endpoints](#-api-endpoints)
- [โครงสร้างโปรเจค](#-โครงสร้างโปรเจค)
- [การทดสอบ](#-การทดสอบ)
- [การใช้งาน](#-การใช้งาน)

---

## ✨ คุณสมบัติ

- ✅ **Clean Architecture** - แยก layer ชัดเจน ง่ายต่อการบำรุงรักษา
- ✅ **JWT Authentication** - ระบบ Login/Register ที่ปลอดภัย
- ✅ **SQL Server Support** - เชื่อมต่อกับ SQL Server 2008 R2
- ✅ **RESTful API** - ออกแบบตามมาตรฐาน REST
- ✅ **Validation** - ตรวจสอบข้อมูลก่อนบันทึก
- ✅ **Password Hashing** - เข้ารหัสรหัสผ่านด้วย bcrypt
- ✅ **Error Handling** - จัดการ Error อย่างเป็นระบบ
- ✅ **PSR-4 Autoloading** - โหลดไฟล์อัตโนมัติ
- ✅ **Environment Configuration** - แยกการตั้งค่าตามสภาพแวดล้อม

---

## 🏗️ สถาปัตยกรรม

โปรเจคนี้ใช้ **Clean Architecture** แบ่งเป็น 4 layers:

```
┌─────────────────────────────────────────┐
│         Presentation Layer              │
│   (Controllers, Routes, HTTP)           │
├─────────────────────────────────────────┤
│         Application Layer               │
│  (Use Cases, DTOs, Services)            │
├─────────────────────────────────────────┤
│           Domain Layer                  │
│ (Entities, Value Objects, Interfaces)   │
├─────────────────────────────────────────┤
│       Infrastructure Layer              │
│ (Database, External APIs, JWT, Logging) │
└─────────────────────────────────────────┘
```

### 📁 Layer Descriptions

#### 1. **Domain Layer** (Core Business Logic)
```
app/Domain/
├── Entities/          # Business entities (User)
├── Repositories/      # Repository interfaces
├── ValueObjects/      # Value objects (Email, Password)
└── Exceptions/        # Domain exceptions
```
- ไม่ขึ้นกับ layer อื่นๆ
- มีเฉพาะ business logic
- กำหนด interfaces ของ repositories

#### 2. **Application Layer** (Use Cases)
```
app/Application/
├── UseCases/          # Use cases (Login, Register)
├── DTOs/              # Data Transfer Objects
└── Services/          # Application services
```
- ประกอบด้วย business use cases
- เรียกใช้ repositories ผ่าน interfaces
- ไม่รู้จักรายละเอียดของ infrastructure

#### 3. **Infrastructure Layer** (Technical Details)
```
app/Infrastructure/
├── Persistence/       # Database implementations
├── Auth/              # JWT, Password hashing
├── External/          # External API clients
├── Http/              # HTTP client
└── Logging/           # Logging services
```
- implement interfaces จาก domain
- เชื่อมต่อกับ database, external services
- จัดการ technical details

#### 4. **Presentation Layer** (API Interface)
```
app/Presentation/
├── Http/              # Controllers, Middleware
└── Console/           # CLI commands

routes/
├── api.php            # API routes
└── auth.php           # Authentication routes
```
- รับ HTTP requests
- เรียก use cases
- ส่ง responses กลับ

---

## 🛠️ เทคโนโลยีที่ใช้

### Backend Framework
- **[Slim Framework 2.x](https://www.slimframework.com/)** - Micro framework สำหรับสร้าง API
- **[Eloquent ORM](https://laravel.com/docs/eloquent)** - Database ORM จาก Laravel

### Authentication & Security
- **[Firebase JWT](https://github.com/firebase/php-jwt)** - JWT token generation
- **bcrypt** - Password hashing

### Validation & Utilities
- **[Respect Validation](https://respect-validation.readthedocs.io/)** - Data validation
- **[Guzzle HTTP](https://docs.guzzlephp.org/)** - HTTP client
- **[mPDF](https://mpdf.github.io/)** - PDF generation
- **[PHP dotenv](https://github.com/vlucas/phpdotenv)** - Environment variables

### Database
- **SQL Server 2008 R2** - Primary database
- **PDO + SQLSRV** - Database driver

---

## 📦 การติดตั้ง

### ความต้องการของระบบ

- PHP >= 7.4
- Composer
- SQL Server 2008 R2 หรือใหม่กว่า
- SQL Server PHP Extensions (pdo_sqlsrv, sqlsrv)
- Web Server (Apache/Nginx) หรือ PHP Built-in server

### ขั้นตอนการติดตั้ง

1. **Clone repository**
```bash
git clone <repository-url>
cd service
```

2. **ติดตั้ง dependencies**
```bash
composer install
```

3. **คัดลอกไฟล์ environment**
```bash
cp .env.example .env
```

4. **แก้ไขไฟล์ .env**
```env
APP_NAME="Clean Architecture API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/service

# SQL Server Configuration
DB_CONNECTION=sqlsrv
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=service_db
DB_USERNAME=sa
DB_PASSWORD=YourPassword123

# JWT Configuration
JWT_SECRET=your-super-secret-key-change-this-in-production
JWT_EXPIRATION=3600
JWT_REFRESH_EXPIRATION=604800
```

5. **สร้าง Database**
```sql
CREATE DATABASE service_db;
```

6. **รัน migrations** (ถ้ามี)
```bash
php artisan migrate
```

7. **ตั้งค่า Web Server**

**Apache (.htaccess)**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```

**หรือใช้ PHP Built-in Server**
```bash
cd public
php -S localhost:8000
```

---

## ⚙️ การตั้งค่า

### ตั้งค่า SQL Server Extensions

**Windows:**
```bash
# ดาวน์โหลดและติดตั้ง Microsoft Drivers for PHP for SQL Server
# https://docs.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
```

**ตรวจสอบว่าติดตั้งสำเร็จ:**
```bash
php -m | grep sqlsrv
```

### ตั้งค่า JWT Secret

สร้าง secret key ที่แข็งแรง:
```bash
# Linux/Mac
php -r "echo bin2hex(random_bytes(32));"

# หรือใช้ online generator
# https://randomkeygen.com/
```

แก้ไขใน `.env`:
```env
JWT_SECRET=<generated-secret-key>
```

---

## 🔌 API Endpoints

### Base URL
```
http://localhost/service/public/api
```

### Health Check

#### GET `/api/health`
ตรวจสอบสถานะของ API และ Database

**Response:**
```json
{
  "status": "OK",
  "message": "API is running! 🚀",
  "timestamp": "2024-01-01 12:00:00",
  "php": "7.4.33",
  "slim": "2.6.3",
  "environment": {
    "app_env": "local",
    "app_debug": "true"
  },
  "database": {
    "type": "SQL Server",
    "status": "connected",
    "version": "Microsoft SQL Server 2008 R2...",
    "host": "localhost",
    "database": "service_db"
  }
}
```

---

### Authentication Endpoints

#### POST `/api/auth/register`
สมัครสมาชิกใหม่

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123",
  "name": "John Doe"
}
```

**Response (201):**
```json
{
  "message": "Registration successful",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe",
    "created_at": "2024-01-01 12:00:00"
  }
}
```

**Validation Rules:**
- `email`: required, valid email format, unique
- `password`: required, min 6 characters
- `name`: required, min 2 characters

---

#### POST `/api/auth/login`
เข้าสู่ระบบ

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe"
  },
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "expires_in": 3600
}
```

---

#### GET `/api/auth/me`
ดูข้อมูล Profile (ต้อง Login)

**Headers:**
```
Authorization: Bearer <token>
```

**Response (200):**
```json
{
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe"
  },
  "token_info": {
    "issued_at": "2024-01-01 12:00:00",
    "expires_at": "2024-01-01 13:00:00"
  }
}
```

---

## 📂 โครงสร้างโปรเจค

```
service/
├── app/
│   ├── Application/          # Use Cases, DTOs, Services
│   │   ├── DTOs/
│   │   ├── Services/
│   │   └── UseCases/
│   │       └── Auth/
│   ├── Domain/              # Business Logic
│   │   ├── Entities/
│   │   ├── Exceptions/
│   │   ├── Repositories/
│   │   └── ValueObjects/
│   ├── Infrastructure/      # Technical Implementation
│   │   ├── Auth/
│   │   ├── External/
│   │   ├── Http/
│   │   ├── Logging/
│   │   └── Persistence/
│   └── Presentation/        # HTTP Layer
│       ├── Console/
│       └── Http/
├── bootstrap/               # Application Bootstrap
├── config/                  # Configuration Files
│   ├── app.php
│   ├── auth.php
│   └── database.php
├── database/
│   └── migrations/
├── public/                  # Web Root
│   ├── .htaccess
│   ├── index.php           # Entry Point
│   └── api-docs.html       # API Documentation
├── routes/                  # Route Definitions
│   ├── api.php
│   └── auth.php
├── storage/                 # Storage Directory
│   └── logs/
├── tests/                   # Tests
│   ├── Unit/
│   └── Integration/
├── vendor/                  # Composer Dependencies
├── .env.example            # Environment Template
├── .gitignore
├── composer.json
├── GITHUB_WORKFLOW.md      # Git Workflow Guide
├── index.php               # Redirect to public/
└── README.md
```

---

## 🧪 การทดสอบ

### ทดสอบด้วย cURL

**Health Check:**
```bash
curl http://localhost/service/public/api/health
```

**Register:**
```bash
curl -X POST http://localhost/service/public/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123",
    "name": "Test User"
  }'
```

**Login:**
```bash
curl -X POST http://localhost/service/public/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**Get Profile:**
```bash
curl http://localhost/service/public/api/auth/me \
  -H "Authorization: Bearer <your-token>"
```

### ทดสอบด้วย Postman

1. Import collection จาก `public/api-docs.html`
2. ตั้งค่า Environment Variables:
   - `base_url`: `http://localhost/service/public`
   - `token`: `<your-jwt-token>`
3. ทดสอบ endpoints ตามลำดับ

---

## 💻 การใช้งาน

### 1. เริ่มต้นใช้งาน

```bash
# เข้าไปที่โฟลเดอร์ public
cd public

# รัน PHP Built-in Server
php -S localhost:8000

# เปิดเบราว์เซอร์
http://localhost:8000/api/health
```

### 2. สร้าง User ใหม่

```php
POST /api/auth/register
{
  "email": "john@example.com",
  "password": "secure123",
  "name": "John Doe"
}
```

### 3. Login

```php
POST /api/auth/login
{
  "email": "john@example.com",
  "password": "secure123"
}
```

### 4. ใช้ Token เข้าถึง Protected Routes

```php
GET /api/auth/me
Headers: {
  "Authorization": "Bearer eyJhbGciOiJIUzI1NiIs..."
}
```

---

## 🔐 Security Best Practices

1. **อย่า commit `.env`** - มีข้อมูลสำคัญ
2. **เปลี่ยน JWT Secret** - ใช้ key ที่แข็งแรงใน production
3. **ใช้ HTTPS** - ใน production ต้องใช้ SSL/TLS
4. **Validate Input** - ตรวจสอบข้อมูลทุกครั้ง
5. **Rate Limiting** - จำกัดจำนวน request
6. **Error Messages** - อย่าเปิดเผยข้อมูลระบบ

---

## 🐛 Troubleshooting

### ปัญหาเชื่อมต่อ Database

```bash
# ตรวจสอบ SQL Server extensions
php -m | grep sqlsrv

# ตรวจสอบว่าเชื่อมต่อได้
sqlcmd -S localhost -U sa -P YourPassword123
```

### ปัญหา Composer

```bash
# อัพเดท composer
composer self-update

# ติดตั้งใหม่
rm -rf vendor composer.lock
composer install
```

### ปัญหา JWT Token

```bash
# ตรวจสอบว่า JWT_SECRET ถูกตั้งค่า
php -r "echo getenv('JWT_SECRET');"

# หรือดูใน .env
cat .env | grep JWT_SECRET
```

---

## 📚 เอกสารเพิ่มเติม

- [Slim Framework Docs](https://www.slimframework.com/docs/)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [JWT Best Practices](https://tools.ietf.org/html/rfc8725)
- [SQL Server PHP Drivers](https://docs.microsoft.com/en-us/sql/connect/php/)

---

## 🤝 Contributing

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

ดู [GITHUB_WORKFLOW.md](GITHUB_WORKFLOW.md) สำหรับคู่มือการใช้ Git

---

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

---

## 🙏 Acknowledgments

- Slim Framework Team
- Clean Architecture Community
- Laravel Eloquent ORM
- Firebase JWT Library

---

**สร้างด้วย ❤️ โดยใช้ Clean Architecture Principles**
