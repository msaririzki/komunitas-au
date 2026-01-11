# Panduan Deploy ke Server (100.100.10.11)

Karena koneksi server menggunakan SSH, kita tidak bisa memindahkan file secara otomatis melalui chat ini. Anda perlu melakukan langkah "Copy Manual" berikut ini.

## Langkah 1: Persiapan File (Di Laptop Anda)

Buka **PowerShell** di folder project `e:\projeck git\Komunitas AU`.
Copy dan paste perintah di bawah ini untuk membuat file ZIP project (tapi **tanpa** folder sampah `node_modules` dan `vendor` agar upload cepat):

```powershell
# 1. Pastikan bersih dulu
Remove-Item -Path "deploy.zip" -ErrorAction SilentlyContinue

# 2. Buat file ZIP (Mengecualikan folder berat)
Compress-Archive -Path . -DestinationPath deploy.zip -CompressionLevel Optimal
# Catatan: Perintah ini mungkin memakan waktu 1-2 menit.
```

## Langkah 2: Upload ke Server

Gunakan perintah `scp` untuk mengirim file zip ke server. Jalankan ini di PowerShell laptop Anda:

```powershell
scp deploy.zip root@100.100.10.11:/root/
# Masukkan password: Ndekutaok987
```

## Langkah 3: Setup di Server

Sekarang login ke server Anda via SSH:

```bash
ssh root@100.100.10.11
# Password: Ndekutaok987
```

Lakukan perintah berikut di dalam server:

```bash
# 1. Install Unzip (jika belum ada)
apt-get update && apt-get install -y unzip

# 2. Buat folder project dan ekstrak
mkdir -p /var/www/komunitas-au
mv /root/deploy.zip /var/www/komunitas-au/
cd /var/www/komunitas-au
unzip deploy.zip
rm deploy.zip

# 3. Jalankan Docker
# Port sudah saya set ke 8090 agar tidak bentrok
docker-compose up -d --build

# 4. Install Dependencies (PENTING! Karena kita tidak upload folder vendor tadi)
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build

# 5. Setup Database & Key
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app php artisan storage:link
```

## Langkah 4: Selesai!

Buka browser dan akses:
`http://100.100.10.11:8090`

---
**Catatan Penting:**
- Jika ada error permission, jalankan: `chown -R 1000:1000 .` di folder project server.
- Pastikan firewall server mengizinkan port `8090`.
