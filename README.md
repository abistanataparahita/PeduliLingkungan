<<<<<<< HEAD
# Peduli Lingkungan — Fitur & Dokumentasi Singkat

## Stack Teknologi

- **Framework**: Laravel 11 (PHP)
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Autentikasi**: Laravel auth kustom (login/register user publik) + middleware `is-admin` untuk admin

---

## Fitur Publik (Pengunjung & User Terdaftar)

### 1. Beranda (`/`)
- Hero & section pengenalan komunitas.
- Highlight **event terdekat** (juga muncul di announcement bar navbar).
- Section:
  - Tentang Kami
  - Kenapa Join
  - Event
  - Artikel
  - Galeri
  - Produk (katalog produk).

### 2. Event Publik
- **List Event**: `GET /events`
  - Menampilkan daftar event dengan tanggal, lokasi, dll.
- **Detail Event**: `GET /events/{slug}`
  - Menampilkan detail lengkap event.

### 3. Artikel Publik (Blog)
- **List Artikel**: `GET /artikel`
  - Daftar artikel dengan judul, kategori, tanggal.
- **Detail Artikel**: `GET /artikel/{slug}`
  - Halaman konten artikel.

### 4. Galeri Foto
- **Galeri**: `GET /galeri`
  - Kumpulan foto kegiatan yang di-manage dari admin.

### 5. Katalog Produk
- **List Produk**: `GET /products`
  - Filter & sort:
    - Pencarian (`search`)
    - Hanya stok tersedia (`in_stock`)
    - Hanya diskon (`on_sale`)
  - Pagination.
- **Detail Produk**: `GET /products/{product}`
  - Informasi:
    - Nama, SKU, deskripsi.
    - Harga normal, harga diskon, stok.
    - Status pre-order (jika produk tipe pre-order).
  - **Pre-order** (jika `is_preorder = true`):
    - Menampilkan estimasi ketersediaan, batas waktu pre-order, sisa kuota.
    - Jika kuota habis/tutup → label “Pre Order Ditutup”.
    - Jika user sudah pernah pre-order (status `pending/confirmed`) → info “Kamu sudah pre order produk ini”.
    - Modal form pre-order (untuk user login) dengan field:
      - Nomor WhatsApp
      - Jumlah kuantitas
      - Catatan
    - Submit pre-order:
      - Simpan ke tabel `orders` dengan `status = pending`.
      - Update kuota pre-order (`preorder_filled`).
      - Menampilkan popup/toast sukses + tombol opsional **“Chat Admin”** ke WhatsApp (pesan otomatis).
  - **Produk non pre-order**:
    - Tombol:
      - **“Pesan Sekarang”** → form pesanan `/produk/{product}/pesan` (butuh login).
      - **“Tanya via WhatsApp”** → langsung membuka WhatsApp admin dengan pesan singkat (tanpa simpan ke DB).

### 6. Form Pesanan Produk (Non Pre-Order)

#### 6.1. Halaman Form Pesanan (`GET /produk/{product}/pesan`)
- Hanya bisa diakses user login (dibungkus di group `auth`).
- Dua kolom (desktop):
  - **Kiri** — Ringkasan produk:
    - Foto produk.
    - Nama produk.
    - Harga satuan (menggunakan `Product::final_price` + format rupiah).
    - Preview total **real-time** dengan Alpine.js (`qty * price`).
  - **Kanan** — Form:
    - Nama lengkap (terisi otomatis dari user, bisa diedit).
    - Nomor WhatsApp.
    - Jumlah (`qty`) dengan minimum 1.
    - Catatan (opsional).
    - Preview total ulang di bawah form.
    - Tombol **“Konfirmasi & Chat WhatsApp 💬”**.
    - Teks keterangan bahwa setelah klik akan diarahkan ke WhatsApp.

#### 6.2. Submit Form Pesanan (`POST /produk/{product}/pesan`)
- Validasi:
  - `buyer_name`, `whatsapp`, `qty`, `catatan (opsional)`.
- Simpan ke tabel `orders`:
  - `user_id` = user login.
  - `product_id`, `buyer_name`, `whatsapp`, `qty`, `catatan`.
  - `status = 'pending'`, `is_read = false`.
- Hitung total dari `product->final_price * qty`.
- Bangun pesan WhatsApp otomatis (format rapi berisi:
  - Produk, jumlah, total, nama, WA, catatan, dan `Order ID`).
- Redirect ke `https://wa.me/{waNumber}?text={waMessage}` (user tinggal klik kirim di WA).

### 7. “Pesanan Saya” (User)

#### 7.1. List Pesanan (`GET /pesanan`)
- Hanya untuk user login.
- Query: `Order::where('user_id', auth_id)->with('product')->latest()->paginate(10)`.
- Halaman:
  - Header “Pesanan Saya” + total pesanan (`$orders->total()`).
  - Tombol **“Lihat Produk”** (kembali ke katalog).
  - Daftar card pesanan:
    - Foto produk kecil.
    - Nama produk.
    - Qty.
    - Total (qty × harga final).
    - Tanggal & jam pesanan.
    - **Badge status**:
      - `pending` → kuning “Menunggu”.
      - `confirmed` → biru “Dikonfirmasi”.
      - `selesai` → hijau “Selesai”.
      - `dibatalkan` → merah “Dibatalkan”.
    - Tombol **“Lihat Detail →”** ke `/pesanan/{order}`.
  - Empty state jika tidak ada pesanan:
    - Icon tas belanja.
    - Teks “Belum ada pesanan”.
    - Tombol **“Lihat Produk”**.
  - Pagination Laravel default.

#### 7.2. Detail Pesanan (`GET /pesanan/{order}`)
- Cek kepemilikan: hanya pemilik `order->user_id` yang bisa akses (403 jika bukan).
- Memuat relasi `product`.
- Layout:
  - Tombol kembali ke “Pesanan Saya”.
  - Badge status besar di kanan (warna sesuai status).
  - Card detail:
    - Foto & nama produk.
    - Qty.
    - Total.
    - Nama pembeli.
    - Nomor WhatsApp.
    - Catatan (jika ada).
    - Tanggal & jam pemesanan.
  - **Timeline status**:
    - Langkah: Menunggu → Dikonfirmasi → Selesai.
    - Step yang sudah tercapai diberi indikator hijau.
    - Jika status `dibatalkan` → muncul box peringatan “Pesanan dibatalkan”.
  - Tombol **“Chat Admin”**:
    - Membuka WhatsApp admin dengan pesan otomatis berisi:
      - `Order ID`, produk, qty, total, nama, WA, catatan.

### 8. Forum (`/forum`)
- Prefix `forum.` dengan beberapa route:
  - Index, buat post, reply, like, delete, dll.
- Hanya user login (dan tidak dibanned) yang bisa membuat/berinteraksi.

### 9. Autentikasi User Publik
- **Guest**:
  - `GET /login`, `POST /login`
  - `GET /register`, `POST /register`
- **Auth**:
  - Logout `POST /logout`.
  - Halaman profil & edit profil.
  - Ubah password.
  - Update avatar.

### 10. Navbar & UX Publik
- Navbar fixed dengan:
  - Logo dan link section (Home, Event, Produk, Forum, Lainnya).
  - Announcement bar event terdekat di paling atas.
- **Desktop**:
  - Tombol **Masuk/Daftar** jika guest.
  - Dropdown user jika login:
    - Profil.
    - Post Saya.
    - **Pesanan Saya**.
    - Logout.
  - CTA **Join Sekarang** ke grup WhatsApp.
- **Mobile drawer**:
  - Menu navigasi utama.
  - Jika **guest**:
    - Tombol **Masuk** dan **Daftar** di footer drawer.
  - Jika **login**:
    - Kartu ringkasan user (avatar, nama, email).
    - Tombol **Profil** dan **Pesanan**.
    - Tombol **Keluar**.
  - Tombol **Gabung via WhatsApp** (CTA komunitas).

---

## Fitur Admin

Seluruh route admin memakai prefix `/admin`, middleware `auth` + `is-admin`, dan layout custom `admin.layouts.dashboard`.

### 1. Dashboard (`/admin/dashboard`)
- Ringkasan angka:
  - Total Event.
  - Event mendatang.
  - Total Foto.
  - Total Artikel.
- Tabel:
  - Event terdekat.
  - Artikel terbaru.

### 2. Manajemen Banner
- `admin/banners` (resource full).
- Tambah/edit/hapus banner.
- Reorder, toggle aktif, dsb.

### 3. Manajemen Event
- `admin/events` (resource full).
- Fitur tambahan:
  - Toggle featured.
  - Toggle active.

### 4. Manajemen Galeri
- `admin/galleries` (resource).
- Bulk upload foto.
- Reorder urutan.
- Toggle featured.

### 5. Manajemen Artikel
- `admin/articles` (resource).
- Publish/unpublish artikel.

### 6. Testimonial
- `admin/testimonials` resource + toggle tampil/sembunyi.

### 7. Halaman “Tentang Kami”
- `admin/about`:
  - Edit konten tentang komunitas.

### 8. Pengaturan Situs
- `admin/settings`:
  - Simpan berbagai konfigurasi, termasuk:
    - `wa_phone` → nomor WhatsApp admin untuk pesanan/konsultasi.
    - `wa_group_link` → link grup WhatsApp komunitas.
    - `meta_title`, `meta_description`, `og_image`, dll.

### 9. Manajemen Produk
- `admin/products` (resource).
- Field penting:
  - Harga beli, harga jual, harga diskon.
  - Stok saat ini, min/max stok.
  - Flag `is_preorder` + atribut pre-order (estimasi, kuota, open_until, filled).
- Fitur:
  - Soft delete.
  - Konfirmasi delete lewat modal.

### 10. Manajemen User
- `admin/users`:
  - List user.
  - Lihat detail user.
  - Ubah role.
  - Ban/unban user.
  - Hapus user.

### 11. Manajemen Pesanan (Order) Admin

#### 11.1. Tabel Order (`/admin/orders`)
- Tabel semua pesanan (`orders`):
  - No.
  - Nama buyer.
  - Produk.
  - Qty.
  - WhatsApp.
  - Catatan.
  - Status.
  - Tanggal.
  - Aksi.
- Filter by status:
  - Semua / pending / confirmed / selesai / dibatalkan.
- Aksi:
  - Ubah status (dropdown + tombol submit).
  - Hapus (pakai modal konfirmasi global).

#### 11.2. Status & Notifikasi Admin
- Model `Order`:
  - `status`: `pending | confirmed | selesai | dibatalkan`.
  - `is_read`: menandai apakah admin sudah melihat pesanan pending.
- Badge notifikasi:
  - **Sidebar**:
    - Menu “Pesanan” di sidebar admin menampilkan badge count pending unread.
  - **Header**:
    - Icon bell dengan badge count yang sama.
- Mekanisme:
  - `View::composer('admin.layouts.dashboard', ...)` menghitung:
    - Jumlah order dengan `status = 'pending'` dan `is_read = false`.
  - Saat admin membuka `/admin/orders`:
    - Semua order `pending` dengan `is_read = false` akan diupdate menjadi `is_read = true`.

---

## Model & Struktur Data Utama

### 1. `products` (Product)
- Informasi produk, harga, stok, dan atribut pre-order:
  - `is_preorder` (bool).
  - `preorder_estimate`, `preorder_open_until`, `preorder_quota`, `preorder_filled`.
- Relasi:
  - `hasMany(Order::class)` sebagai `orders()`.

### 2. `orders` (Order)
- Kolom:
  - `id`
  - `user_id` (nullable, relasi ke user).
  - `product_id` (relasi ke product).
  - `buyer_name`.
  - `whatsapp`.
  - `qty`.
  - `catatan` (nullable).
  - `status` (`pending | confirmed | selesai | dibatalkan`).
  - `is_read` (bool).
  - `created_at`, `updated_at`.
- Relasi:
  - `user()` → belongsTo User.
  - `product()` → belongsTo Product.
- Scope helper:
  - `pending()`.
  - `unread()`.

---

## Alur Pesanan Singkat

1. **User buka halaman produk**  
   `/products` → klik produk → `/products/{product}`.

2. **Pilih cara pesan**  
   - Tombol **“Pesan Sekarang”** → form pesanan (butuh login).  
   - Tombol **“Tanya via WhatsApp”** → langsung ke WhatsApp (tanpa menyimpan order).

3. **Isi form pesanan** (`GET /produk/{product}/pesan`)  
   Isi nama, nomor WhatsApp, jumlah, dan catatan. Preview total auto-update.

4. **Klik “Konfirmasi & Chat WhatsApp”**  
   - Sistem menyimpan order ke DB (`orders`, status `pending`).
   - Sistem redirect ke WhatsApp admin dengan pesan lengkap.

5. **User ngobrol via WhatsApp**  
   Pesan sudah terisi otomatis, user cukup kirim.

6. **Admin proses pesanan**  
   - Melihat daftar pesanan di `/admin/orders`.
   - Mengubah status sesuai progres (`pending → confirmed → selesai` atau `dibatalkan`).

7. **User pantau status**  
   - Buka **“Pesanan Saya”** dari navbar.
   - Lihat semua riwayat pesanan + detail & timeline per pesanan.

---

## Catatan Pengembangan

- Fitur-fitur lama (routes pre-order, forum, admin panel) **tidak diubah secara destruktif**; fitur baru mengikuti model/order yang sudah ada.
- Nomor WhatsApp admin dan link grup bisa dikonfigurasi dari halaman **Pengaturan** admin (`wa_phone`, `wa_group_link`).
- Untuk menambah status baru (misal “Diproses”), perlu:
  - Update enum/status di migration/model `Order`.
  - Sesuaikan admin `/admin/orders`, badge di user (`/pesanan` & `/pesanan/{order}`), dan timeline.  

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# PeduliLingkungan
>>>>>>> 27db755b6595441efc89fe7ef2ab54e5415b85fb
