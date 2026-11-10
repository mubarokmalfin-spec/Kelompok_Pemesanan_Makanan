<?php
require_once 'auth_check.php';

$dosen_id = $_SESSION['user_id'];
$dosen_nama = $_SESSION['nama_lengkap'];
$id = intval($_GET['id'] ?? 0);
$mhs = null;

if ($id > 0 && $pdo) {
    $stmt = $pdo->prepare("SELECT s.*
                           FROM `skrining` s
                           JOIN `mahasiswa` m ON s.mahasiswa_id = m.id
                           WHERE s.`id` = ? AND s.`dosen_id` = ?");
    $stmt->execute([$id, $dosen_id]);
    $mhs = $stmt->fetch();
}

if (!$mhs) {
    header('Location: data_mahasiswa.php');
    exit;
}

// Handle Form Update Status & Catatan Konseling oleh Dosen
$pesan_update = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_konseling']) && $pdo) {
    $status_konseling = trim($_POST['status_konseling'] ?? 'Belum Ditindaklanjuti');
    $catatan_dosen = trim($_POST['catatan_dosen'] ?? '');
    $tanggal_konseling = trim($_POST['tanggal_konseling'] ?? '');

    $stmtUpdate = $pdo->prepare("UPDATE `skrining` 
                                 SET `status_konseling` = ?, `catatan_dosen` = ?, `tanggal_konseling` = ? 
                                 WHERE `id` = ? AND `dosen_id` = ?");
    $stmtUpdate->execute([$status_konseling, $catatan_dosen, $tanggal_konseling, $id, $dosen_id]);

    $mhs['status_konseling'] = $status_konseling;
    $mhs['catatan_dosen'] = $catatan_dosen;
    $mhs['tanggal_konseling'] = $tanggal_konseling;
    $pesan_update = 'Status konseling dan catatan bimbingan berhasil disimpan! Catatan ini akan otomatis muncul sebagai notifikasi pada dashboard mahasiswa.';
}

$is_high_risk = ($mhs['hasil_prediksi'] == 1);
$factors = array_map('trim', explode(',', $mhs['faktor_utama']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Evaluasi Mahasiswa - MindCare SPK</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include 'navbar_dosen.php'; ?>

<main class="container my-4">
  
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="data_mahasiswa.php" class="btn btn-outline-secondary btn-sm rounded-pill">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke 20 Mahasiswa Perwalian
    </a>
    <span class="text-muted small">Waktu Skrining: <?= date('d M Y, H:i', strtotime($mhs['created_at'])) ?> WIB</span>
  </div>

  <?php if ($pesan_update): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($pesan_update) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    
    <!-- Kolom Kiri: Profil & Form Tindak Lanjut Dosen PA -->
    <div class="col-lg-4">
      
      <!-- Card Status Prediksi -->
      <div class="card shadow-sm border-0 mb-4 overflow-hidden text-center">
        <div class="card-body p-4 <?= $is_high_risk ? 'bg-danger bg-opacity-10' : 'bg-success bg-opacity-10' ?>">
          <span class="badge <?= $is_high_risk ? 'bg-danger' : 'bg-success' ?> px-3 py-2 rounded-pill fs-6 mb-2">
            <?= htmlspecialchars($mhs['kategori']) ?>
          </span>
          <h4 class="fw-bold mb-1">
            <?= $is_high_risk ? 'Tingkat Stres Tinggi' : 'Kondisi Relatif Stabil' ?>
          </h4>
          <p class="small text-muted mb-0">Probabilitas Stres: <strong><?= $mhs['probabilitas_tinggi'] ?>%</strong></p>
        </div>
      </div>

      <!-- Card Profil Mahasiswa -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2 text-primary"></i>Profil Mahasiswa</h6>
        </div>
        <div class="card-body p-3">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between px-0">
              <span class="text-muted">Nama</span>
              <strong><?= htmlspecialchars($mhs['nama_mahasiswa']) ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
              <span class="text-muted">NIM</span>
              <span class="font-monospace fw-semibold"><?= htmlspecialchars($mhs['nim']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
              <span class="text-muted">Program Studi</span>
              <span><?= htmlspecialchars($mhs['prodi']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
              <span class="text-muted">Angkatan</span>
              <span><?= htmlspecialchars($mhs['angkatan']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
              <span class="text-muted">Jenis Kelamin / Usia</span>
              <span><?= htmlspecialchars($mhs['jenis_kelamin']) ?> / <?= $mhs['usia'] ?> th</span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
              <span class="text-muted">IPK Saat Tes</span>
              <span class="badge bg-primary fs-6"><?= number_format($mhs['ipk'], 2) ?></span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Card Tindak Lanjut Konseling oleh Dosen PA -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Tindak Lanjut & Bimbingan Dosen PA</h6>
        </div>
        <div class="card-body p-4">
          <form action="detail_mahasiswa.php?id=<?= $mhs['id'] ?>" method="POST">
            <div class="mb-3">
              <label for="status_konseling" class="form-label">Status Tindak Lanjut</label>
              <select name="status_konseling" id="status_konseling" class="form-select" required>
                <option value="Belum Ditindaklanjuti" <?= $mhs['status_konseling'] === 'Belum Ditindaklanjuti' ? 'selected' : '' ?>>Belum Ditindaklanjuti</option>
                <option value="Dijadwalkan Konseling" <?= $mhs['status_konseling'] === 'Dijadwalkan Konseling' ? 'selected' : '' ?>>🔔 Dijadwalkan Konseling (Kirim Notif)</option>
                <option value="Selesai Konseling" <?= $mhs['status_konseling'] === 'Selesai Konseling' ? 'selected' : '' ?>>✅ Selesai Konseling</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="catatan_dosen" class="form-label">Catatan Bimbingan / Pesan Konseling</label>
              <textarea name="catatan_dosen" id="catatan_dosen" rows="4" class="form-control" placeholder="Contoh: Silakan temui bapak di ruang dosen hari Selasa jam 10.00 untuk diskusi pengurangan beban SKS dan solusi kendala belajar."><?= htmlspecialchars($mhs['catatan_dosen'] ?? '') ?></textarea>
              <div class="form-text small">Catatan ini akan langsung tampil sebagai notifikasi ketika mahasiswa login.</div>
            </div>
            <button type="submit" name="update_konseling" class="btn btn-primary w-100 rounded-pill shadow-sm">
              <i class="bi bi-floppy me-1"></i> Simpan Catatan & Kirim Notif
            </button>
          </form>
        </div>
      </div>

    </div>

    <!-- Kolom Kanan: Rincian 12 Jawaban Kuesioner & Analisis AI -->
    <div class="col-lg-8">
      
      <!-- Card Analisis AI & Pemicu -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-cpu-fill me-2 text-primary"></i>Analisis Model SPK (Random Forest)</h6>
        </div>
        <div class="card-body p-4">
          <div class="row align-items-center mb-3">
            <div class="col-md-4 text-center">
              <div style="max-width: 170px; margin: 0 auto;">
                <canvas id="miniRiskChart"></canvas>
              </div>
            </div>
            <div class="col-md-8">
              <h6 class="fw-bold mb-2">3 Faktor Pemicu Risiko Teratas:</h6>
              <div class="d-flex flex-column gap-2">
                <?php foreach ($factors as $idx => $f): ?>
                  <div class="p-2 px-3 rounded-2 <?= $is_high_risk ? 'bg-danger bg-opacity-10 text-danger border border-danger-subtle' : 'bg-light text-dark border' ?> small d-flex justify-content-between align-items-center">
                    <span><strong>#<?= $idx + 1 ?></strong> <?= htmlspecialchars($f) ?></span>
                    <i class="bi bi-arrow-up-right"></i>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div class="p-3 bg-light rounded-3 small">
            <strong>Catatan Refleksi Sistem:</strong>
            <p class="mb-0 mt-1 text-muted"><?= htmlspecialchars($mhs['catatan_refleksi']) ?></p>
          </div>
        </div>
      </div>

      <!-- Card Rincian Jawaban 12 Indikator -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-card-checklist me-2 text-primary"></i>Rincian Jawaban 12 Indikator Kuesioner Mahasiswa</h6>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 50%;">Indikator Pertanyaan</th>
                  <th>Jawaban Mahasiswa</th>
                  <th>Tingkat Indikasi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1. Tekanan Akademik</td>
                  <td><strong>Skala <?= $mhs['tekanan_akademik'] ?> / 5</strong></td>
                  <td>
                    <?php if ($mhs['tekanan_akademik'] >= 4): ?>
                      <span class="badge bg-danger">Tinggi / Berat</span>
                    <?php elseif ($mhs['tekanan_akademik'] == 3): ?>
                      <span class="badge bg-warning text-dark">Sedang</span>
                    <?php else: ?>
                      <span class="badge bg-success">Ringan</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <td>2. Kepuasan Belajar</td>
                  <td><strong>Skala <?= $mhs['kepuasan_belajar'] ?> / 5</strong></td>
                  <td>
                    <?php if ($mhs['kepuasan_belajar'] <= 2): ?>
                      <span class="badge bg-danger">Kurang Puas</span>
                    <?php else: ?>
                      <span class="badge bg-success">Cukup / Puas</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <td>3. Tekanan Keuangan</td>
                  <td><strong>Skala <?= $mhs['tekanan_keuangan'] ?> / 5</strong></td>
                  <td>
                    <?php if ($mhs['tekanan_keuangan'] >= 4): ?>
                      <span class="badge bg-danger">Beban Berat</span>
                    <?php else: ?>
                      <span class="badge bg-success">Terkendali</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <td>4. Tekanan Pekerjaan Sampingan</td>
                  <td><strong>Skala <?= $mhs['tekanan_pekerjaan'] ?> / 5</strong></td>
                  <td>
                    <?= $mhs['tekanan_pekerjaan'] == 0 ? '<span class="badge bg-secondary">Tidak Bekerja</span>' : '<span class="badge bg-warning text-dark">Level ' . $mhs['tekanan_pekerjaan'] . '</span>' ?>
                  </td>
                </tr>
                <tr>
                  <td>5. Jam Belajar Harian (Mandiri)</td>
                  <td><strong><?= $mhs['jam_belajar_harian'] ?> Jam / Hari</strong></td>
                  <td>
                    <?= $mhs['jam_belajar_harian'] >= 7 ? '<span class="badge bg-warning text-dark">Intensitas Tinggi</span>' : '<span class="badge bg-success">Wajar</span>' ?>
                  </td>
                </tr>
                <tr>
                  <td>6. Durasi Tidur Harian</td>
                  <td><strong><?= htmlspecialchars($mhs['durasi_tidur']) ?></strong></td>
                  <td>
                    <?= $mhs['durasi_tidur'] === 'Kurang dari 5 jam' ? '<span class="badge bg-danger">Kurang / Begadang</span>' : '<span class="badge bg-success">Cukup</span>' ?>
                  </td>
                </tr>
                <tr>
                  <td>7. Pola Makan</td>
                  <td><strong><?= htmlspecialchars($mhs['pola_makan']) ?></strong></td>
                  <td>
                    <?= $mhs['pola_makan'] === 'Tidak Sehat' ? '<span class="badge bg-danger">Buruk / Tidak Teratur</span>' : '<span class="badge bg-success">Baik</span>' ?>
                  </td>
                </tr>
                <tr>
                  <td>8. Riwayat Gangguan Mental Keluarga</td>
                  <td><strong><?= htmlspecialchars($mhs['riwayat_keluarga']) ?></strong></td>
                  <td>
                    <?= $mhs['riwayat_keluarga'] === 'Ya' ? '<span class="badge bg-warning text-dark">Ada Riwayat</span>' : '<span class="badge bg-success">Tidak Ada</span>' ?>
                  </td>
                </tr>
                <tr>
                  <td>9. Pikiran Menyakiti Diri / Putus Asa</td>
                  <td><strong><?= htmlspecialchars($mhs['pikiran_menyakiti_diri']) ?></strong></td>
                  <td>
                    <?= $mhs['pikiran_menyakiti_diri'] === 'Ya' ? '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill"></i> Sangat Butuh Perhatian</span>' : '<span class="badge bg-success">Aman</span>' ?>
                  </td>
                </tr>
                <tr>
                  <td>10. Indeks Prestasi Kumulatif (IPK)</td>
                  <td><strong><?= number_format($mhs['ipk'], 2) ?></strong></td>
                  <td><span class="badge bg-info text-dark">Akademik</span></td>
                </tr>
                <tr>
                  <td>11. Usia Mahasiswa</td>
                  <td><strong><?= $mhs['usia'] ?> Tahun</strong></td>
                  <td><span class="badge bg-secondary">Demografi</span></td>
                </tr>
                <tr>
                  <td>12. Jenis Kelamin</td>
                  <td><strong><?= htmlspecialchars($mhs['jenis_kelamin']) ?></strong></td>
                  <td><span class="badge bg-secondary">Demografi</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>

  </div>
</main>

<footer class="footer mt-auto py-3 bg-light border-top">
  <div class="container text-center text-muted small">
    MindCare SPK Dosen &copy; <?= date('Y') ?> &bull; Dosen PA: <?= htmlspecialchars($dosen_nama) ?>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const miniCtx = document.getElementById('miniRiskChart').getContext('2d');
  new Chart(miniCtx, {
    type: 'doughnut',
    data: {
      labels: ['Rendah', 'Tinggi'],
      datasets: [{
        data: [<?= $mhs['probabilitas_rendah'] ?>, <?= $mhs['probabilitas_tinggi'] ?>],
        backgroundColor: ['#198754', '#dc3545'],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      cutout: '70%'
    }
  });
</script>
</body>
</html>
