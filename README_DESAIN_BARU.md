# AYUNIYA Storybook Redesign

Pembaruan tampilan yang mengikuti storyboard ungu-pink pastel, dengan sidebar plum, panel lembut, ilustrasi langit malam, dan tipografi Playfair Display + Poppins.

## Instalasi yang direkomendasikan (PATCH)

1. Backup folder proyek `C:\xampp\htdocs\Project AYUNIYA` terlebih dahulu, terutama `assets/uploads/` dan `config/database.php`.
2. Ekstrak ZIP PATCH yang disertakan oleh ChatGPT **ke dalam folder proyek yang sedang digunakan**, dan setujui penggantian file jika diminta. Folder proyek yang sudah berjalan jangan dihapus.
3. Tidak perlu mengimpor ulang `database/schema.sql` maupun menghapus database `ayuniya_db`.
4. Port MySQL tetap diatur oleh `config/database.php` pada instalasi lokal. Patch desain tidak menyentuh file ini.
5. Jalankan XAMPP Apache + MySQL, lalu buka `http://localhost/Project%20AYUNIYA/` (beranda), `.../login.php`, atau `.../dashboard.php` setelah login.
6. Tekan Ctrl+F5 untuk memuat CSS terbaru bila browser masih menampilkan desain lama.

## Apa saja yang diperbarui?

- Halaman publik / landing (`index.php`): latar lavender, moonlit scene, tipografi, tombol, dan kartu fitur.
- Login / registrasi: panel form glassmorphism dan ilustrasi suasana malam pastel.
- Seluruh halaman aplikasi: sidebar plum, navigasi, kartu, formulir, warna, dan tampilan mobile.
- Dashboard: hero berilustrasi dan ringkasan data yang lebih menarik.
- Personal Vibe / VibeTunes: empat pilihan vibe opsional (Chill, Focus, Healing, Energetic) serta visual playlist. Filter memakai isi `vibe_category` pada tabel `songs`.
- Personal Recap: empat kartu ringkasan dengan jumlah aktual dari database.

## Informasi penting

- Tidak ada perubahan ke tabel atau data pengguna.
- Fitur musik tidak otomatis terisi: tabel `songs` tetap membutuhkan rekaman lagu dengan URL Spotify embed yang valid. Tampilan kosong adalah keadaan yang benar apabila tabel tersebut kosong.
- Gambar dekoratif adalah SVG lokal buatan baru di `assets/images/`.
- Ikon Feather dan font Google Fonts memerlukan koneksi internet. Jika offline, teks dan elemen utama tetap tampil, tetapi ikon/font dapat kembali ke alternatif.
- Ini adalah proyek PHP dan MySQL: memerlukan server lokal atau hosting dengan PHP + MySQL untuk mengakses semua fungsi. Belum ada tautan hosting publik.
