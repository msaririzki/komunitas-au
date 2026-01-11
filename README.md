# Komunitas AU 🚀

**Komunitas AU** adalah platform media sosial komunitas modern yang dibangun dengan **Laravel 11**, **TailwindCSS**, dan **Alpine.js**. Aplikasi ini dirancang dengan antarmuka **Glassmorphism** yang elegan, dark mode, dan performa tinggi.

![Komunitas AU Banner](https://via.placeholder.com/1200x600/0f0f1a/38bdf8?text=Komunitas+AU+Preview)

## ✨ Fitur Unggulan

-   **📱 Mobile First Design**: Tampilan responsif sempurna di Desktop dan HP.
-   **🎨 Modern UI/UX**: Desain Dark Glassmorphism dengan aksen Neon.
-   **💬 Interaktif**: Posting status, komentar, dan like secara real-time.
-   **🖼️ Media Support**: Upload banyak gambar dalam satu postingan.
-   **👤 Profil User**: Halaman profil yang elegan dengan timeline khusus.
-   **⚡ Performa Tinggi**: Menggunakan teknik Lazy Loading dan optimasi aset.
-   **🐳 Docker Ready**: Siap deploy dengan satu perintah `docker-compose up`.

## 🛠️ Teknologi yang Digunakan

-   **Backend**: Laravel 11.x
-   **Frontend**: Blade, TailwindCSS v3.x, Alpine.js v3.x
-   **Database**: MySQL / MariaDB
-   **Containerization**: Docker & Docker Compose

## 🚀 Cara Install

### Menggunakan Docker (Rekomendasi)

Aplikasi ini sudah dilengkapi dengan **Zero-Config Deployment**. Anda hanya perlu Docker.

1.  **Clone Repository**
    ```bash
    git clone https://github.com/msaririzki/komunitas-au.git
    cd komunitas-au
    ```

2.  **Jalankan Aplikasi**
    ```bash
    docker-compose up -d --build
    ```
    *Script otomatis akan menginstall dependency, migrate database, dan build asset.*

3.  **Akses Web**
    Buka browser dan kunjungi: `http://localhost:8090`

### Cara Manual (Tanpa Docker)

1.  Copy `.env.example` ke `.env`
2.  `composer install`
3.  `npm install && npm run build`
4.  `php artisan key:generate`
5.  `php artisan migrate --seed`
6.  `php artisan serve`

## 👥 Kontribusi

Silakan buat **Pull Request** jika ingin menambahkan fitur baru.

---
© 2024 - 2026 Komunitas AU. Dibuat dengan 💜 oleh Antigravity.
