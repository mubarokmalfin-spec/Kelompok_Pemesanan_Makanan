<?php
require_once 'koneksi.php';

// Wajib Login Mahasiswa
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: login.php?tab=mahasiswa');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

try {
    $mahasiswa_id = $_SESSION['user_id'];
    $dosen_id = $_SESSION['dosen_id'];
    $nim = $_SESSION['nim'];
    $nama_mahasiswa = $_SESSION['nama_lengkap'];
    $prodi = $_SESSION['prodi'];
    $angkatan = $_SESSION['angkatan'];
    $jenis_kelamin = $_SESSION['jenis_kelamin'];
    $usia = intval($_POST['usia'] ?? $_SESSION['usia']);

    // 2. Ambil Data Indikator Skrining
    $ipk_asli = floatval($_POST['ipk'] ?? 3.5);
    // Skala model ML dilatih pada CGPA 0-10. Jika mahasiswa input skala 4.00, sesuaikan
    $ipk_ml = ($ipk_asli <= 4.00) ? round($ipk_asli * 2.5, 2) : $ipk_asli;

    $jam_belajar_harian = intval($_POST['jam_belajar_harian'] ?? 4);
    $tekanan_akademik = intval($_POST['tekanan_akademik'] ?? 3);
    $tekanan_pekerjaan = intval($_POST['tekanan_pekerjaan'] ?? 0);
    $kepuasan_belajar = intval($_POST['kepuasan_belajar'] ?? 3);
    $tekanan_keuangan = intval($_POST['tekanan_keuangan'] ?? 3);
    $durasi_tidur = trim($_POST['durasi_tidur'] ?? '7-8 jam');
    $pola_makan = trim($_POST['pola_makan'] ?? 'Sehat');
    $riwayat_keluarga = trim($_POST['riwayat_keluarga'] ?? 'Tidak');
    $pikiran_menyakiti_diri = trim($_POST['pikiran_menyakiti_diri'] ?? 'Tidak');

    // 3. Susun Payload untuk Model Machine Learning
    $ml_input = [
        'jenis_kelamin' => $jenis_kelamin,
        'usia' => $usia,
        'tekanan_akademik' => $tekanan_akademik,
        'tekanan_pekerjaan' => $tekanan_pekerjaan,
        'ipk' => $ipk_ml,
        'kepuasan_belajar' => $kepuasan_belajar,
        'durasi_tidur' => $durasi_tidur,
        'pola_makan' => $pola_makan,
        'pikiran_menyakiti_diri' => $pikiran_menyakiti_diri,
        'jam_belajar_harian' => $jam_belajar_harian,
        'tekanan_keuangan' => $tekanan_keuangan,
        'riwayat_keluarga' => $riwayat_keluarga
    ];

    // 4. Panggil Engine ML (Dual-Engine: Flask atau CLI Fallback)
    $ml_response = panggil_model_ml($ml_input);

    if (!isset($ml_response['status']) || $ml_response['status'] !== 'success') {
        throw new Exception($ml_response['message'] ?? 'Gagal memperoleh prediksi dari model ML.');
    }

    $hasil_prediksi = intval($ml_response['hasil_prediksi']);
    $kategori = $ml_response['kategori'];
    $catatan_refleksi = $ml_response['catatan_refleksi'];
    $prob_rendah = floatval($ml_response['chart_data']['percentages'][0] ?? 0);
    $prob_tinggi = floatval($ml_response['chart_data']['percentages'][1] ?? 0);
    $faktor_utama = implode(', ', $ml_response['faktor_utama'] ?? []);

    // 5. Simpan ke Database MySQL (Dengan Foreign Key mahasiswa_id & dosen_id)
    $insert_id = null;
    if ($pdo) {
        $sql = "INSERT INTO `skrining` (
            `mahasiswa_id`, `dosen_id`, `nim`, `nama_mahasiswa`, `prodi`, `angkatan`,
            `jenis_kelamin`, `usia`, `tekanan_akademik`, `tekanan_pekerjaan`, `ipk`, `kepuasan_belajar`,
            `durasi_tidur`, `pola_makan`, `pikiran_menyakiti_diri`, `jam_belajar_harian`, `tekanan_keuangan`, `riwayat_keluarga`,
            `hasil_prediksi`, `kategori`, `probabilitas_rendah`, `probabilitas_tinggi`, `catatan_refleksi`, `faktor_utama`
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $mahasiswa_id, $dosen_id, $nim, $nama_mahasiswa, $prodi, $angkatan,
            $jenis_kelamin, $usia, $tekanan_akademik, $tekanan_pekerjaan, $ipk_asli, $kepuasan_belajar,
            $durasi_tidur, $pola_makan, $pikiran_menyakiti_diri, $jam_belajar_harian, $tekanan_keuangan, $riwayat_keluarga,
            $hasil_prediksi, $kategori, $prob_rendah, $prob_tinggi, $catatan_refleksi, $faktor_utama
        ]);

        $insert_id = $pdo->lastInsertId();
    }

    // Redirect ke hasil
    if ($insert_id) {
        header("Location: hasil.php?id={$insert_id}");
    } else {
        header("Location: dashboard_mahasiswa.php");
    }
    exit;

} catch (Exception $e) {
    header('Location: index.php?pesan=error&error_msg=' . urlencode($e->getMessage()));
    exit;
}
?>
