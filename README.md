
---

# Laravel PaymentApp

Proyek ini adalah implementasi sederhana dari sistem pembayaran dari take home test yang diberikan mencakup fitur **Deposit**, **Withdraw**, **Transfer** (fitur improvment tambahan), dan **Admin Dashboard**.

---

## Fitur

- **Login** dan **Logout** untuk autentikasi pengguna.
- CRUD untuk transaksi **Deposit**, **Withdraw**, dan **Transfer**.
- **Middleware Authorization** untuk melindungi API.
- **Admin Dashboard** untuk melihat semua transaksi dan biaya admin.
- Migrasi dan Seeder untuk data dummy.

---

## Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```

2. **Instalasi Dependencies**
   ```bash
   composer install
   ```

3. **Copy File `.env`**
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Atur Konfigurasi Database**

   Edit file `.env` dan masukkan konfigurasi database Anda:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

---

## Migrasi dan Seeder

1. **Jalankan Migrasi**
   ```bash
   php artisan migrate
   ```

2. **Jalankan Seeder**
   ```bash
   php artisan db:seed
   ```

   **Seeder yang tersedia**:
   - **Admin**: `email: admin@example.com`, `password: password`
   - **User 1**: `email: johndoe@example.com`, `password: password`
   - **User 2**: `email: janedoe@example.com`, `password: password`
   
    **Catatan**:
    ```bash
   Jika terjadi error pada saat running seeder, hiraukan saja karena itu pasti
   dikarenakan duplikat entry, silahkan untuk melanjutkan ke step selanjutnya.
   ```

---

## Menjalankan Aplikasi

1. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   php artisan queue:work
   ```

2. **Akses URL**
   - **Web**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
   - **API**: [http://127.0.0.1:8000/api](http://127.0.0.1:8000/api)
   ```bash
   Setelah login : [http://127.0.0.1:8000/login] anda akan dilempar ke page sesuai
   role, jika role as admin, akan dilempar ke page dashboard admin yang mana berisi daf-
   tar transaksi semua user dan form inputan untuk insert/update admin fee. fitur ini
   saya buat untuk menampilkan proses asyn melalu job untuk update data wallet.
   Lalu jika role berupa user biasa maka akan dilempat ke page transaction.
   ```

---

## Daftar Endpoint API

### 1. **Login**
- **Endpoint**: `POST /api/login`
- **Deskripsi**: Autentikasi pengguna dan mendapatkan Bearer Token.

**Parameter**:
| Name     | Type   | Required | Description       |
|----------|--------|----------|-------------------|
| `email`  | string | Yes      | Email pengguna    |
| `password` | string | Yes    | Password pengguna |

**Contoh Respons Berhasil**:
```json
{
    "message": "Login successful",
    "access_token": "Bearer MXwxNzMyNTk1MDk0",
    "token_type": "Bearer"
}
```

---

### 2. **Logout**
- **Endpoint**: `POST /api/logout`
- **Header**:  
  `Authorization: Bearer <token>`
- **Deskripsi**: Logout pengguna.

**Contoh Respons**:
```json
{
    "message": "Logout successful"
}
```

---

### 3. **Deposit**
- **Endpoint**: `POST /api/deposit`
- **Header**:  
  `Authorization: Bearer <token>`

**Parameter**:
| Name      | Type   | Required | Description          |
|-----------|--------|----------|----------------------|
| `order_id` | string | Yes      | ID unik untuk deposit |
| `amount`   | float  | Yes      | Jumlah deposit        |

**Contoh Respons Berhasil**:
```json
{
    "status": 1,
    "message": "Deposit successful",
    "transaction": {
        "id": 1,
        "order_id": "ORD12345",
        "amount": 5000.00,
        "type": "deposit",
        "status": 1
    },
    "wallet_balance": 15000.00
}
```

---

### 4. **Withdraw**
- **Endpoint**: `POST /api/withdraw`
- **Header**:  
  `Authorization: Bearer <token>`

**Parameter**:
| Name    | Type   | Required | Description      |
|---------|--------|----------|------------------|
| `amount` | float  | Yes      | Jumlah withdrawal |

**Contoh Respons Berhasil**:
```json
{
    "status": 1,
    "message": "Withdrawal successful",
    "transaction": {
        "id": 2,
        "order_id": "ORD1690376000",
        "amount": 3000.00,
        "type": "withdrawal",
        "status": 1
    },
    "wallet_balance": 12000.00
}
```

---

### 5. **Transfer**
- **Endpoint**: `POST /api/transfer`
- **Header**:  
  `Authorization: Bearer <token>`

**Parameter**:
| Name           | Type   | Required | Description            |
|----------------|--------|----------|------------------------|
| `receiver_email` | string | Yes    | Email penerima transfer |
| `amount`         | float  | Yes    | Jumlah uang transfer   |

**Contoh Respons Berhasil**:
```json
{
    "status": 1,
    "message": "Transfer request received",
    "transaction": {
        "id": 3,
        "order_id": "ORD1690376010-W",
        "amount": 2500.00,
        "type": "transfer",
        "status": 1
    },
    "receiver_transaction": {
        "id": 4,
        "order_id": "ORD1690376010-D",
        "amount": 2000.00,
        "type": "deposit",
        "status": 1
    },
    "wallet_balance": 9500.00
}
```

---

## Teknologi yang Digunakan

- **Laravel 8**
- **PHP 8.x**
- **MySQL**
- **Postman** untuk pengujian API

---
