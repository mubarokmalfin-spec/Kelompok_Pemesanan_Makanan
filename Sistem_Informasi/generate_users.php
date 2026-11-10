<?php
// Generator script untuk membuat 5 dosen & 100 mahasiswa dengan nama berbeda & password unik

$dosen_data = [
    [
        'id' => 1,
        'nidn' => '0012058001',
        'nama_dosen' => 'Dr. Hendra Kusuma, M.Kom.',
        'username' => 'dosen_hendra',
        'password' => 'Dosen#Hendra81',
        'email' => 'hendra.kusuma@univ.ac.id'
    ],
    [
        'id' => 2,
        'nidn' => '0015088202',
        'nama_dosen' => 'Dr. Sri Wahyuni, M.T.',
        'username' => 'dosen_sri',
        'password' => 'Dosen@Wahyuni92',
        'email' => 'sri.wahyuni@univ.ac.id'
    ],
    [
        'id' => 3,
        'nidn' => '0021038503',
        'nama_dosen' => 'Agus Wibowo, S.Kom., M.Cs.',
        'username' => 'dosen_agus',
        'password' => 'Dosen$Wibowo73',
        'email' => 'agus.wibowo@univ.ac.id'
    ],
    [
        'id' => 4,
        'nidn' => '0008118704',
        'nama_dosen' => 'Dewi Lestari, S.T., M.Kom.',
        'username' => 'dosen_dewi',
        'password' => 'Dosen%Lestari64',
        'email' => 'dewi.lestari@univ.ac.id'
    ],
    [
        'id' => 5,
        'nidn' => '0030047905',
        'nama_dosen' => 'Bambang Nugroho, M.Sc., Ph.D.',
        'username' => 'dosen_bambang',
        'password' => 'Dosen&Nugroho55',
        'email' => 'bambang.nugroho@univ.ac.id'
    ]
];

// 100 Nama Mahasiswa Unik (Kombinasi Realistis Indonesia)
$nama_depan_l = [
    'Ahmad', 'Budi', 'Dimas', 'Eko', 'Fajar', 'Gilang', 'Hafizh', 'Irfan', 'Joko', 'Kevin',
    'Lucky', 'Mahesa', 'Naufal', 'Oki', 'Pandu', 'Qori', 'Rizky', 'Satria', 'Taufik', 'Umar',
    'Vino', 'Wahyu', 'Xaverius', 'Yusuf', 'Zack', 'Bayu', 'Candra', 'Daffa', 'Erlangga', 'Farhan',
    'Galih', 'Handoko', 'Ilham', 'Julian', 'Kharisma', 'Luthfi', 'Mochamad', 'Nur', 'Panji', 'Raditya',
    'Raka', 'Rangga', 'Rayhan', 'Reza', 'Rio', 'Roni', 'Rudi', 'Ryan', 'Syahrul', 'Zulfa'
];

$nama_depan_p = [
    'Adinda', 'Annisa', 'Bella', 'Citra', 'Dina', 'Elsa', 'Fitri', 'Gita', 'Hana', 'Indah',
    'Jessica', 'Kartika', 'Laras', 'Melati', 'Nabila', 'Olivia', 'Putri', 'Qonita', 'Rani', 'Siti',
    'Tiara', 'Ulfa', 'Vina', 'Wulan', 'Yulia', 'Zahra', 'Alya', 'Berlian', 'Cahya', 'Dwi',
    'Endah', 'Fauziah', 'Gisela', 'Hani', 'Intan', 'Jihan', 'Kirana', 'Lestari', 'Maya', 'Nia',
    'Nadya', 'Nurul', 'Pratiwi', 'Rahmatia', 'Risma', 'Safira', 'Salma', 'Shinta', 'Tari', 'Vivi'
];

$nama_belakang = [
    'Pratama', 'Saputra', 'Kusuma', 'Hidayat', 'Santoso', 'Wijaya', 'Permana', 'Nugraha', 'Setiawan', 'Ramadhan',
    'Wibowo', 'Firmansyah', 'Syahputra', 'Maulana', 'Gunawan', 'Utomo', 'Subagyo', 'Kurniawan', 'Hermawan', 'Pradana',
    'Anggraini', 'Puspita', 'Wulandari', 'Rahmawati', 'Lestari', 'Sari', 'Kusumawati', 'Utami', 'Wardani', 'Handayani',
    'Astuti', 'Pertiwi', 'Novitasari', 'Fitriani', 'Cahyani', 'Indriyani', 'Susanti', 'Damayanti', 'Safitri', 'Maharani',
    'Siregar', 'Nasution', 'Lubis', 'Harahap', 'Ginting', 'Sembiring', 'Pasaribu', 'Hutapea', 'Simanjuntak', 'Sitompul'
];

$kata_sandi_prefix = [
    'Bintang', 'Cahaya', 'Garuda', 'Melati', 'Samudra', 'Mentari', 'Pelangi', 'Angkasa', 'Pustaka', 'Merdeka',
    'Zamrud', 'Permata', 'Ksatria', 'Pesona', 'Lentera', 'Laksana', 'Pintar', 'Cerdas', 'Mandiri', 'Sukses',
    'Semangat', 'Harapan', 'Impian', 'Gemilang', 'Prestasi', 'Bahagia', 'Harmoni', 'Pancasila', 'Wawasan', 'Nusantara'
];

$simbol = ['!', '@', '#', '$', '%', '&', '*'];

$mahasiswa_data = [];
$used_names = [];
$used_passwords = [];

for ($i = 1; $i <= 100; $i++) {
    $nim = sprintf("230101%03d", $i);
    $dosen_id = (int)ceil($i / 20); // 1-20 -> 1, 21-40 -> 2, 41-60 -> 3, 61-80 -> 4, 81-100 -> 5
    
    // Bergantian laki-laki dan perempuan
    $is_laki = ($i % 2 === 1);
    $jk = $is_laki ? 'Laki-laki' : 'Perempuan';
    
    // Buat nama unik
    $max_attempts = 100;
    do {
        if ($is_laki) {
            $depan = $nama_depan_l[($i * 7 + rand(0, 49)) % count($nama_depan_l)];
        } else {
            $depan = $nama_depan_p[($i * 5 + rand(0, 49)) % count($nama_depan_p)];
        }
        $belakang = $nama_belakang[($i * 11 + rand(0, 49)) % count($nama_belakang)];
        $full_name = "{$depan} {$belakang}";
        $max_attempts--;
    } while (isset($used_names[$full_name]) && $max_attempts > 0);
    $used_names[$full_name] = true;

    // Buat password unik
    do {
        $p_word = $kata_sandi_prefix[($i + rand(0, count($kata_sandi_prefix)-1)) % count($kata_sandi_prefix)];
        $p_sym = $simbol[$i % count($simbol)];
        $p_num = str_pad(($i * 13 + 7) % 999, 3, '0', STR_PAD_LEFT);
        $pass = "{$p_word}{$p_sym}{$p_num}";
    } while (isset($used_passwords[$pass]));
    $used_passwords[$pass] = true;

    $usia = 19 + (($i % 4));

    $mahasiswa_data[] = [
        'id' => $i,
        'nim' => $nim,
        'nama_mahasiswa' => $full_name,
        'password' => $pass,
        'jenis_kelamin' => $jk,
        'usia' => $usia,
        'prodi' => 'Sistem Informasi',
        'angkatan' => '2023',
        'dosen_id' => $dosen_id
    ];
}

// 1. Generate SQL File
$sql = "-- ========================================================\n";
$sql .= "-- Database: db_spk_mental\n";
$sql .= "-- Skema Sistem Pendukung Keputusan Skrining Kesehatan Mental\n";
$sql .= "-- 5 Dosen PA dan 100 Mahasiswa (1 Dosen = 20 Mahasiswa)\n";
$sql .= "-- ========================================================\n\n";
$sql .= "CREATE DATABASE IF NOT EXISTS `db_spk_mental` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
$sql .= "USE `db_spk_mental`;\n\n";

$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
$sql .= "DROP TABLE IF EXISTS `skrining`;\n";
$sql .= "DROP TABLE IF EXISTS `mahasiswa`;\n";
$sql .= "DROP TABLE IF EXISTS `dosen`;\n";
$sql .= "DROP TABLE IF EXISTS `users`;\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n\n";

// Table DOSEN
$sql .= "-- --------------------------------------------------------\n";
$sql .= "-- 1. Tabel dosen\n";
$sql .= "-- --------------------------------------------------------\n";
$sql .= "CREATE TABLE `dosen` (\n";
$sql .= "  `id` INT AUTO_INCREMENT PRIMARY KEY,\n";
$sql .= "  `nidn` VARCHAR(30) NOT NULL UNIQUE,\n";
$sql .= "  `nama_dosen` VARCHAR(100) NOT NULL,\n";
$sql .= "  `username` VARCHAR(50) NOT NULL UNIQUE,\n";
$sql .= "  `password` VARCHAR(255) NOT NULL,\n";
$sql .= "  `password_plain` VARCHAR(100) NOT NULL,\n";
$sql .= "  `email` VARCHAR(100) DEFAULT NULL,\n";
$sql .= "  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n";

$sql .= "INSERT INTO `dosen` (`id`, `nidn`, `nama_dosen`, `username`, `password`, `password_plain`, `email`) VALUES\n";
$dosen_values = [];
foreach ($dosen_data as $d) {
    $hash = password_hash($d['password'], PASSWORD_DEFAULT);
    $dosen_values[] = "({$d['id']}, '{$d['nidn']}', '{$d['nama_dosen']}', '{$d['username']}', '{$hash}', '{$d['password']}', '{$d['email']}')";
}
$sql .= implode(",\n", $dosen_values) . ";\n\n";

// Table MAHASISWA
$sql .= "-- --------------------------------------------------------\n";
$sql .= "-- 2. Tabel mahasiswa\n";
$sql .= "-- --------------------------------------------------------\n";
$sql .= "CREATE TABLE `mahasiswa` (\n";
$sql .= "  `id` INT AUTO_INCREMENT PRIMARY KEY,\n";
$sql .= "  `nim` VARCHAR(20) NOT NULL UNIQUE,\n";
$sql .= "  `nama_mahasiswa` VARCHAR(100) NOT NULL,\n";
$sql .= "  `password` VARCHAR(255) NOT NULL,\n";
$sql .= "  `password_plain` VARCHAR(100) NOT NULL,\n";
$sql .= "  `prodi` VARCHAR(100) NOT NULL DEFAULT 'Sistem Informasi',\n";
$sql .= "  `angkatan` VARCHAR(10) NOT NULL DEFAULT '2023',\n";
$sql .= "  `jenis_kelamin` ENUM('Laki-laki', 'Perempuan') NOT NULL,\n";
$sql .= "  `usia` INT NOT NULL DEFAULT 20,\n";
$sql .= "  `dosen_id` INT NOT NULL,\n";
$sql .= "  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
$sql .= "  FOREIGN KEY (`dosen_id`) REFERENCES `dosen`(`id`) ON DELETE CASCADE\n";
$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n";

$sql .= "INSERT INTO `mahasiswa` (`id`, `nim`, `nama_mahasiswa`, `password`, `password_plain`, `prodi`, `angkatan`, `jenis_kelamin`, `usia`, `dosen_id`) VALUES\n";
$mhs_values = [];
foreach ($mahasiswa_data as $m) {
    $hash = password_hash($m['password'], PASSWORD_DEFAULT);
    $escaped_name = addslashes($m['nama_mahasiswa']);
    $mhs_values[] = "({$m['id']}, '{$m['nim']}', '{$escaped_name}', '{$hash}', '{$m['password']}', '{$m['prodi']}', '{$m['angkatan']}', '{$m['jenis_kelamin']}', {$m['usia']}, {$m['dosen_id']})";
}
$sql .= implode(",\n", $mhs_values) . ";\n\n";

// Table SKRINING
$sql .= "-- --------------------------------------------------------\n";
$sql .= "-- 3. Tabel skrining (Riwayat Tes & SPK ML)\n";
$sql .= "-- --------------------------------------------------------\n";
$sql .= "CREATE TABLE `skrining` (\n";
$sql .= "  `id` INT AUTO_INCREMENT PRIMARY KEY,\n";
$sql .= "  `mahasiswa_id` INT NOT NULL,\n";
$sql .= "  `dosen_id` INT NOT NULL,\n";
$sql .= "  `nim` VARCHAR(20) NOT NULL,\n";
$sql .= "  `nama_mahasiswa` VARCHAR(100) NOT NULL,\n";
$sql .= "  `prodi` VARCHAR(100) NOT NULL,\n";
$sql .= "  `angkatan` VARCHAR(10) NOT NULL,\n";
$sql .= "  `jenis_kelamin` ENUM('Laki-laki', 'Perempuan') NOT NULL,\n";
$sql .= "  `usia` INT NOT NULL,\n";
$sql .= "  `tekanan_akademik` INT NOT NULL,\n";
$sql .= "  `tekanan_pekerjaan` INT NOT NULL,\n";
$sql .= "  `ipk` DECIMAL(4,2) NOT NULL,\n";
$sql .= "  `kepuasan_belajar` INT NOT NULL,\n";
$sql .= "  `durasi_tidur` VARCHAR(50) NOT NULL,\n";
$sql .= "  `pola_makan` VARCHAR(50) NOT NULL,\n";
$sql .= "  `pikiran_menyakiti_diri` VARCHAR(10) NOT NULL,\n";
$sql .= "  `jam_belajar_harian` INT NOT NULL,\n";
$sql .= "  `tekanan_keuangan` INT NOT NULL,\n";
$sql .= "  `riwayat_keluarga` VARCHAR(10) NOT NULL,\n";
$sql .= "  `hasil_prediksi` TINYINT(1) NOT NULL,\n";
$sql .= "  `kategori` VARCHAR(50) NOT NULL,\n";
$sql .= "  `probabilitas_rendah` DECIMAL(5,2) NOT NULL,\n";
$sql .= "  `probabilitas_tinggi` DECIMAL(5,2) NOT NULL,\n";
$sql .= "  `catatan_refleksi` TEXT NOT NULL,\n";
$sql .= "  `faktor_utama` TEXT NOT NULL,\n";
$sql .= "  `status_konseling` ENUM('Belum Ditindaklanjuti', 'Dijadwalkan Konseling', 'Selesai Konseling') DEFAULT 'Belum Ditindaklanjuti',\n";
$sql .= "  `catatan_dosen` TEXT DEFAULT NULL,\n";
$sql .= "  `tanggal_konseling` VARCHAR(100) DEFAULT NULL,\n";
$sql .= "  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
$sql .= "  FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa`(`id`) ON DELETE CASCADE,\n";
$sql .= "  FOREIGN KEY (`dosen_id`) REFERENCES `dosen`(`id`) ON DELETE CASCADE\n";
$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n";

// Sample initial screenings for each lecturer's students (e.g. 2 students per lecturer)
$sample_screenings = [
    [
        'm_id' => 1, 'd_id' => 1, 'nim' => $mahasiswa_data[0]['nim'], 'nama' => $mahasiswa_data[0]['nama_mahasiswa'],
        'jk' => $mahasiswa_data[0]['jenis_kelamin'], 'usia' => $mahasiswa_data[0]['usia'],
        'ta' => 2, 'tp' => 0, 'ipk' => 3.75, 'kb' => 4, 'tidur' => '7-8 jam', 'makan' => 'Sehat', 'pmd' => 'Tidak', 'jam' => 4, 'tk' => 2, 'rk' => 'Tidak',
        'pred' => 0, 'kat' => 'Tingkat Stress/Risiko Rendah', 'pr' => 93.50, 'pt' => 6.50,
        'pesan' => 'Kondisi psikologis dan pola hidupmu relatif stabil dan sehat. Pertahankan manajemen waktu, pola istirahat, dan lingkungan belajar yang positif!',
        'faktor' => 'Tekanan Akademik (24.71%), Tekanan Keuangan (12.01%), Jam Belajar Harian (8.45%)',
        'status' => 'Belum Ditindaklanjuti', 'catatan' => null
    ],
    [
        'm_id' => 2, 'd_id' => 1, 'nim' => $mahasiswa_data[1]['nim'], 'nama' => $mahasiswa_data[1]['nama_mahasiswa'],
        'jk' => $mahasiswa_data[1]['jenis_kelamin'], 'usia' => $mahasiswa_data[1]['usia'],
        'ta' => 5, 'tp' => 2, 'ipk' => 3.05, 'kb' => 2, 'tidur' => 'Kurang dari 5 jam', 'makan' => 'Tidak Sehat', 'pmd' => 'Ya', 'jam' => 7, 'tk' => 4, 'rk' => 'Ya',
        'pred' => 1, 'kat' => 'Tingkat Stress/Risiko Tinggi', 'pr' => 17.80, 'pt' => 82.20,
        'pesan' => 'Hasil indikasi menunjukkan kamu sedang mengalami beban pikiran atau tekanan psikologis yang cukup berat. Disarankan untuk beristirahat sejenak dan berkonsultasi dengan Dosen PA atau konselor kampus.',
        'faktor' => 'Pikiran Menyakiti Diri (35.61%), Tekanan Akademik (24.71%), Tekanan Keuangan (12.01%)',
        'status' => 'Dijadwalkan Konseling', 'catatan' => 'Diharapkan hadir konseling tatap muka pada hari Selasa pukul 10.00 di Ruang Dosen Gedung B.'
    ],
    [
        'm_id' => 21, 'd_id' => 2, 'nim' => $mahasiswa_data[20]['nim'], 'nama' => $mahasiswa_data[20]['nama_mahasiswa'],
        'jk' => $mahasiswa_data[20]['jenis_kelamin'], 'usia' => $mahasiswa_data[20]['usia'],
        'ta' => 4, 'tp' => 1, 'ipk' => 3.20, 'kb' => 2, 'tidur' => '5-6 jam', 'makan' => 'Cukup/Sedang', 'pmd' => 'Tidak', 'jam' => 5, 'tk' => 3, 'rk' => 'Tidak',
        'pred' => 1, 'kat' => 'Tingkat Stress/Risiko Tinggi', 'pr' => 32.10, 'pt' => 67.90,
        'pesan' => 'Hasil indikasi menunjukkan kamu sedang mengalami beban pikiran atau tekanan psikologis yang cukup berat. Disarankan untuk beristirahat sejenak dan berkonsultasi dengan Dosen PA atau konselor kampus.',
        'faktor' => 'Tekanan Akademik (24.71%), Tekanan Keuangan (12.01%), Durasi Tidur (10.15%)',
        'status' => 'Belum Ditindaklanjuti', 'catatan' => null
    ],
    [
        'm_id' => 41, 'd_id' => 3, 'nim' => $mahasiswa_data[40]['nim'], 'nama' => $mahasiswa_data[40]['nama_mahasiswa'],
        'jk' => $mahasiswa_data[40]['jenis_kelamin'], 'usia' => $mahasiswa_data[40]['usia'],
        'ta' => 2, 'tp' => 0, 'ipk' => 3.85, 'kb' => 5, 'tidur' => '7-8 jam', 'makan' => 'Sehat', 'pmd' => 'Tidak', 'jam' => 3, 'tk' => 1, 'rk' => 'Tidak',
        'pred' => 0, 'kat' => 'Tingkat Stress/Risiko Rendah', 'pr' => 95.80, 'pt' => 4.20,
        'pesan' => 'Kondisi psikologis dan pola hidupmu relatif stabil dan sehat. Pertahankan manajemen waktu, pola istirahat, dan lingkungan belajar yang positif!',
        'faktor' => 'Tekanan Akademik (24.71%), Tekanan Keuangan (12.01%), Jam Belajar Harian (8.45%)',
        'status' => 'Belum Ditindaklanjuti', 'catatan' => null
    ]
];

$sql .= "INSERT INTO `skrining` (\n";
$sql .= "  `mahasiswa_id`, `dosen_id`, `nim`, `nama_mahasiswa`, `prodi`, `angkatan`, `jenis_kelamin`, `usia`,\n";
$sql .= "  `tekanan_akademik`, `tekanan_pekerjaan`, `ipk`, `kepuasan_belajar`, `durasi_tidur`, `pola_makan`,\n";
$sql .= "  `pikiran_menyakiti_diri`, `jam_belajar_harian`, `tekanan_keuangan`, `riwayat_keluarga`,\n";
$sql .= "  `hasil_prediksi`, `kategori`, `probabilitas_rendah`, `probabilitas_tinggi`, `catatan_refleksi`, `faktor_utama`,\n";
$sql .= "  `status_konseling`, `catatan_dosen`\n";
$sql .= ") VALUES\n";

$sc_rows = [];
foreach ($sample_screenings as $s) {
    $sc_name = addslashes($s['nama']);
    $sc_pesan = addslashes($s['pesan']);
    $sc_faktor = addslashes($s['faktor']);
    $sc_cat = $s['catatan'] ? "'" . addslashes($s['catatan']) . "'" : "NULL";
    $sc_rows[] = "({$s['m_id']}, {$s['d_id']}, '{$s['nim']}', '{$sc_name}', 'Sistem Informasi', '2023', '{$s['jk']}', {$s['usia']}, {$s['ta']}, {$s['tp']}, {$s['ipk']}, {$s['kb']}, '{$s['tidur']}', '{$s['makan']}', '{$s['pmd']}', {$s['jam']}, {$s['tk']}, '{$s['rk']}', {$s['pred']}, '{$s['kat']}', {$s['pr']}, {$s['pt']}, '{$sc_pesan}', '{$sc_faktor}', '{$s['status']}', {$sc_cat})";
}
$sql .= implode(",\n", $sc_rows) . ";\n";

file_put_contents(__DIR__ . '/database.sql', $sql);

// 2. Generate DAFTAR_AKUN.md
$md = "# DAFTAR AKUN PENGGUNA (DOSEN & MAHASISWA)\n\n";
$md .= "Sistem Informasi Skrining Kesehatan Mental Mahasiswa (SPK AI)\n";
$md .= "**Total Pengguna:** 5 Akun Dosen Pembimbing Akademik & 100 Akun Mahasiswa\n";
$md .= "**Ketentuan Relasi:** 1 Dosen Pembimbing Akademik mengampu tepat **20 Mahasiswa Perwalian**.\n\n";
$md .= "> **Catatan Keamanan:** Seluruh nama dosen dan mahasiswa berbeda, serta setiap akun memiliki password unik yang berbeda satu sama lain.\n\n";
$md .= "---\n\n";

$md .= "## 👨‍🏫 A. DAFTAR AKUN DOSEN PEMBIMBING AKADEMIK (5 DOSEN)\n\n";
$md .= "| No | NIDN | Nama Dosen | Username Login | Password | Mengampu Mahasiswa |\n";
$md .= "|:---:|:---:|:---|:---|:---|:---|\n";
foreach ($dosen_data as $i => $d) {
    $start_m = ($i * 20) + 1;
    $end_m = ($i + 1) * 20;
    $start_nim = sprintf("230101%03d", $start_m);
    $end_nim = sprintf("230101%03d", $end_m);
    $md .= "| " . ($i + 1) . " | `{$d['nidn']}` | **{$d['nama_dosen']}** | `{$d['username']}` | `{$d['password']}` | No. {$start_m}–{$end_m} (`{$start_nim}` s/d `{$end_nim}`) |\n";
}
$md .= "\n---\n\n";

$md .= "## 🎓 B. DAFTAR AKUN MAHASISWA (100 MAHASISWA)\n\n";
$md .= "Mahasiswa login menggunakan **NIM** dan **Password** masing-masing.\n\n";

for ($d_idx = 0; $d_idx < 5; $d_idx++) {
    $d = $dosen_data[$d_idx];
    $start_m = ($d_idx * 20) + 1;
    $end_m = ($d_idx + 1) * 20;
    $md .= "### Kelompok Bimbingan Dosen " . ($d_idx + 1) . ": {$d['nama_dosen']} (NIDN: `{$d['nidn']}`)\n";
    $md .= "| No | NIM (Username) | Nama Mahasiswa | Jenis Kelamin | Password | Dosen Pembimbing |\n";
    $md .= "|:---:|:---:|:---|:---:|:---|:---|\n";

    for ($m_idx = $start_m - 1; $m_idx < $end_m; $m_idx++) {
        $m = $mahasiswa_data[$m_idx];
        $md .= "| {$m['id']} | `{$m['nim']}` | {$m['nama_mahasiswa']} | {$m['jenis_kelamin']} | `{$m['password']}` | {$d['nama_dosen']} |\n";
    }
    $md .= "\n";
}

file_put_contents(__DIR__ . '/DAFTAR_AKUN.md', $md);

echo "Berhasil menghasilkan database.sql dan DAFTAR_AKUN.md!\n";
?>
