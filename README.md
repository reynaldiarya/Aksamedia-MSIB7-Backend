# Backend Developer API - PT Aksamedia Mulia Digital (MSIB Batch 7)

Proyek ini merupakan implementasi backend sederhana yang dikembangkan sebagai bagian dari tes masuk program magang **Backend Developer** di PT Aksamedia Mulia Digital untuk MSIB Batch 7. Proyek ini menggunakan framework **Laravel** dengan **Sanctum** untuk otentikasi API.

## Fitur Utama
- **Otentikasi dengan Sanctum**: Mengamankan akses API dengan token.
- **Manajemen Divisi**: Endpoint untuk mendapatkan data semua divisi.
- **Manajemen Karyawan**: Endpoint untuk CRUD data karyawan.
- **Logout API**: Endpoint untuk menghapus token otentikasi pengguna.

## API Endpoint

### 1. Mendapatkan Semua Divisi
**Endpoint:**
```
GET /api/divisions
```
**Header:**
```
Authorization: Bearer {token}
```

### 2. Mendapatkan Semua Data Karyawan
**Endpoint:**
```
GET /api/employees
```
**Header:**
```
Authorization: Bearer {token}
```

### 3. Membuat Data Karyawan Baru
**Endpoint:**
```
POST /api/employees
```
**Header:**
```
Authorization: Bearer {token}
```
**Body:**
```json
{
  "image": "image.png",
  "name": "John Smith",
  "phone": "12345"
  "division": "uuid"
  "position": "Senior Software Engineer",
}
```

### 4. Memperbarui Data Karyawan
**Endpoint:**
```
PUT /api/employees/{uuid}
```
**Header:**
```
Authorization: Bearer {token}
```
**Body:**
```json
{
  "image": "image.png",
  "name": "John Smith",
  "phone": "12345"
  "division": "uuid"
  "position": "Senior Software Engineer",
}
```

### 5. Menghapus Data Karyawan
**Endpoint:**
```
DELETE /api/employees/{uuid}
```
**Header:**
```
Authorization: Bearer {token}
```

### 6. Logout Pengguna
**Endpoint:**
```
POST /api/logout
```
**Header:**
```
Authorization: Bearer {token}
```

## Pengujian API
Disarankan untuk menggunakan tool seperti Postman untuk menguji endpoint API.

---

Dikembangkan sebagai bagian dari proses seleksi PT Aksamedia Mulia Digital.
