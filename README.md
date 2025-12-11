# Sistem Manajemen Keamanan (Aplikasi Pesan Aman)

**Universitas Atma Jaya Yogyakarta**

### 👥 Anggota Tim
*   **Yudhika Wira Utama** (220711701) - Database
*   **Vaness Avril Madayanto** (220711685) - Frontend
*   **Eustakius Satu Rajawali Ku** (220711648) - Backend + Frontend

---

Aplikasi pengiriman pesan aman berbasis web yang dibangun dengan **CodeIgniter 4**, menerapkan **Enkripsi End-to-End (E2EE)** menggunakan skema Enkripsi Hibrida (RSA + AES) dan Tanda Tangan Digital untuk integritas pesan.

### 🔒 Fitur Utama

*   **Pendaftaran Pengguna yang Aman**:
    *   Pembuatan pasangan kunci RSA (2048-bit) Publik/Privat di sisi klien.
    *   Kunci privat dienkripsi dengan kata sandi pengguna sebelum dikirim ke server.
    *   Server *tidak pernah* menyimpan atau melihat kunci privat dalam bentuk teks biasa (plain-text).
*   **Login Aman**:
    *   Otentikasi standar dengan manajemen sesi.
    *   Pengambilan kunci terenkripsi saat login untuk penggunaan dekripsi di sisi klien.
*   **Pesan Terenkripsi End-to-End**:
    *   **Enkripsi Hibrida**: Berkinerja tinggi dan aman.
        1.  **AES-256 (GCM)** digunakan untuk mengenkripsi isi pesan sebenarnya (payload).
        2.  **RSA** digunakan untuk mengenkripsi kunci AES.
    *   **Forward Secrecy**: Setiap pesan dienkripsi dengan kunci AES unik yang dibuat secara acak.
*   **Integritas & Otentikasi Pesan**:
    *   Semua pesan **ditandatangani secara digital** menggunakan Kunci Privat RSA pengirim.
    *   Penerima memverifikasi tanda tangan menggunakan Kunci Publik pengirim untuk memastikan pesan tidak dirusak dan benar-benar berasal dari pengirim.
*   **Kotak Masuk & Kotak Keluar**: Lihat riwayat pesan aman Anda dengan dekripsi otomatis di sisi klien.

### 🛠️ Teknologi yang Digunakan

*   **Framework Backend**: CodeIgniter 4.x (PHP 8.1+)
*   **Database**: MySQL / MariaDB
*   **Frontend**: HTML5, Bootstrap, JavaScript (Web Crypto API / Logika Kripto Kustom)
*   **Kriptografi**:
    *   RSA-OAEP untuk Pertukaran Kunci.
    *   AES-GCM untuk Enkripsi Konten.
    *   RSA-PSS / ECDSA untuk Tanda Tangan.

### 🚀 Instalasi & Pengaturan

#### 1. Prasyarat
*   PHP 8.1 atau lebih tinggi
*   Composer terinstal secara global
*   Server MySQL/MariaDB yang valid

#### 2. Instalasi
1.  **Clone repositori:**
    ```bash
    git clone https://github.com/Eustakius/security-management.git
    cd security-management
    ```

2.  **Install Dependencies:**
    ```bash
    composer install
    ```

3.  **Pengaturan Environment:**
    Salin file environment contoh dan konfigurasikan.
    ```bash
    cp env .env
    ```
    Buka `.env` dan konfigurasikan pengaturan database Anda:
    ```ini
    CI_ENVIRONMENT = development

    database.default.hostname = localhost
    database.default.database = security_app
    database.default.username = root
    database.default.password = 
    database.default.DBDriver = MySQLi
    ```

#### 3. Pengaturan Database
Impor file SQL yang disediakan untuk membuat tabel yang diperlukan (`users`, `messages`).
*   Impor `security_app.sql` ke dalam tool database Anda (phpMyAdmin, DBeaver, dll.).
*   *Alternatifnya*, jika migrasi sudah diatur (cek `php spark migrate`), gunakan itu. *Catatan: Gunakan file SQL jika migrasi tidak tersedia.*

#### 4. Menjalankan Aplikasi
Mulai server pengembangan lokal:
```bash
php spark serve
```
Akses aplikasi di: `http://localhost:8080`

### 🛡️ Alur Arsitektur Keamanan

1.  **Mengirim Pesan:**
    *   Alice ingin mengirim pesan ke Bob.
    *   Aplikasi membuat **Kunci AES** acak.
    *   Pesan dienkripsi dengan **Kunci AES**.
    *   **Kunci AES** dienkripsi dengan **Kunci Publik Bob** (sehingga hanya Bob yang bisa membacanya).
    *   **Kunci AES** *juga* dienkripsi dengan **Kunci Publik Alice** (sehingga Alice bisa membaca pesan terkirimnya).
    *   **Tanda Tangan Digital** dibuat menggunakan **Kunci Privat Alice**.
    *   Konten Terenkripsi, Kunci Terenkripsi, IV, dan Tanda Tangan dikirim ke server.

2.  **Menerima Pesan:**
    *   Bob login dan mengambil Kunci Terenkripsi spesifik miliknya untuk pesan tersebut.
    *   Browser Bob mendekripsi **Kunci AES** menggunakan **Kunci Privat Bob** (yang dibuka dengan kata sandinya).
    *   **Kunci AES** yang telah didekripsi digunakan untuk mendekripsi **Isi Pesan**.
    *   Browser Bob memverifikasi **Tanda Tangan** menggunakan **Kunci Publik Alice**.

---
*Catatan: Proyek ini bertujuan untuk tujuan pendidikan guna mendemonstrasikan prinsip-prinsip komunikasi aman.*
