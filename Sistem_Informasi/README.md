# MindCare SPK — Sistem Skrining Kesehatan Mental Mahasiswa Berbasis Machine Learning

Aplikasi web berbasis **PHP Native** dan **Machine Learning (Python/Scikit-Learn)** untuk mata kuliah Sistem Informasi. Sistem ini berfungsi sebagai **Sistem Pendukung Keputusan (SPK)** dalam mendeteksi tingkat stres dan beban akademik mahasiswa, serta memberikan sarana pemantauan & penjadwalan bimbingan bagi Dosen Pembimbing Akademik (PA).

---

## 👥 Pengguna & Ketentuan Relasi
Sistem ini menggunakan relasi pembimbingan akademik:
* **5 Dosen Pembimbing Akademik (Dosen PA)**
* **100 Mahasiswa**
* **Aturan Relasi:** Setiap **1 Dosen PA mengampu tepat 20 Mahasiswa Perwalian**.
  * Dosen 1: Mengampu Mahasiswa No. 1 – 20 (`230101001` s/d `230101020`)
  * Dosen 2: Mengampu Mahasiswa No. 21 – 40 (`230101021` s/d `230101040`)
  * Dosen 3: Mengampu Mahasiswa No. 41 – 60 (`230101041` s/d `230101060`)
  * Dosen 4: Mengampu Mahasiswa No. 61 – 80 (`230101061` s/d `230101080`)
  * Dosen 5: Mengampu Mahasiswa No. 81 – 100 (`230101081` s/d `230101100`)

> Seluruh nama dosen dan mahasiswa berbeda, serta setiap akun memiliki password unik masing-masing yang dicatat lengkap di file [**`DAFTAR_AKUN.md`**](DAFTAR_AKUN.md).

---

## 🌟 Fitur Utama

### 1. Peran Mahasiswa (`dashboard_mahasiswa.php` & `index.php`)
- **Login Mahasiswa:** Masuk menggunakan **NIM** dan **Password unik**.
- **Dashboard Mahasiswa:**
  - Menampilkan profil mahasiswa dan informasi **Dosen Pembimbing Akademik (Dosen PA)**.
  - **Notifikasi Bimbingan Konseling 🔔:** Jika Dosen PA menjadwalkan konseling, akan muncul notifikasi mencolok lengkap dengan catatan/pesan dari Dosen.
  - Menampilkan ringkasan status skrining terkini dan grafik probabilitas stres.
  - Riwayat lengkap pemeriksaan kuesioner yang pernah dilakukan.
- **Form Kuesioner (12 Indikator ML):**
  - Data diri otomatis terisi dari akun mahasiswa yang sedang login.
  - Memproses klasifikasi SPK AI ke model Random Forest.
  - Halaman hasil instan dengan grafik Chart.js, 3 faktor risiko teratas, dan tombol cetak lembar hasil.

### 2. Peran Dosen Pembimbing Akademik (`dosen/`)
- **Login Dosen:** Masuk menggunakan **Username** dan **Password unik** masing-masing dosen.
- **Dashboard Monitoring Dosen:**
  - Statistik pemantauan **20 mahasiswa perwaliannya** (Total perwalian, sudah skrining, risiko tinggi, perlu konseling).
  - Grafik proporsi risiko stres mahasiswa bimbingannya.
  - Tabel pengisian skrining terkini.
- **Kelola 20 Mahasiswa Perwalian (`dosen/data_mahasiswa.php`):**
  - Tabel 20 mahasiswa bimbingannya dengan indikator sudah/belum mengisi skrining.
  - Fitur pencarian dan filter status (Risiko Tinggi, Rendah, Dijadwalkan Konseling, Belum Skrining).
- **Detail Evaluasi & Tindak Lanjut Konseling (`dosen/detail_mahasiswa.php`):**
  - Rincian jawaban lengkap 12 indikator mahasiswa.
  - Analisis AI (probabilitas stres dan 3 pemicu utama).
  - Form tindak lanjut: Dosen dapat mengubah status menjadi **Dijadwalkan Konseling** atau **Selesai Konseling** serta menuliskan pesan/catatan bimbingan.
  - Catatan ini otomatis terkirim dan menjadi notifikasi pada akun mahasiswa terkait.
- **Cetak Lembar Perwalian (`dosen/cetak_laporan.php`):**
  - Laporan rekapitulasi pemantauan 20 mahasiswa perwalian berstandar akademik, lengkap dengan kop surat dan tanda tangan dosen PA.

---

## 📂 Struktur Database (`db_spk_mental`)

1. **`dosen`**: Menyimpan 5 Dosen PA (id, nidn, nama_dosen, username, password, email).
2. **`mahasiswa`**: Menyimpan 100 Mahasiswa (id, nim, nama_mahasiswa, password, prodi, angkatan, jenis_kelamin, usia, `dosen_id`).
3. **`skrining`**: Menyimpan hasil tes (id, `mahasiswa_id`, `dosen_id`, 12 indikator, hasil_prediksi, probabilitas, catatan_refleksi, faktor_utama, status_konseling, catatan_dosen, created_at).

---

## 🚀 Cara Menjalankan Aplikasi

1. **Pastikan MySQL Aktif:**
   - Database `db_spk_mental` sudah otomatis ter-import dengan 5 Dosen & 100 Mahasiswa.
   - File sql dump tersimpan di [`database.sql`](database.sql).
2. **Jalankan Server PHP:**
   - Double-click file **[`jalankan.bat`](jalankan.bat)**, atau
   - Buka CMD dan ketik: `php -S localhost:8000`
3. **Buka Browser:**
   - Akses: **`http://localhost:8000/login.php`**
   - Pilih tab **Mahasiswa** atau **Dosen PA**.
   - Kredensial akun dapat dilihat pada file [**`DAFTAR_AKUN.md`**](DAFTAR_AKUN.md).
