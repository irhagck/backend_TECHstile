<div align="center">

  # 🧵 TechStile Backend API

  <p align="center">
    <strong>RESTful API & Industrial Engine for TechStile Smart Textile Factory Management System</strong>
  </p>

</div>

---

## 🚀 Setup & Installation

### 1. Clone & Install Dependencies
```bash
cd techbackendirha
composer install
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` database parameters:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techbackendirha
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 4. Run API Server
```bash
php artisan serve
```

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
