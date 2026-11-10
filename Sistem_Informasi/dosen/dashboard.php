<?php
require_once 'auth_check.php';

$dosen_id = $_SESSION['user_id'];
$dosen_nama = $_SESSION['nama_lengkap'];
$dosen_nidn = $_SESSION['nidn'];

$total_bimbingan = 20; // 1 dosen = 20 mahasiswa
$total_skrining = 0;
$total_tinggi = 0;
$total_rendah = 0;
$total_perlu_konseling = 0;
$latest_data = [];

if ($pdo) {
    // 1. Total mahasiswa yang sudah mengisi skrining dari 20 bimbingan dosen ini
    $stmtCount = $pdo->prepare("SELECT COUNT(DISTINCT mahasiswa_id) FROM `skrining` WHERE `dosen_id` = ?");
    $stmtCount->execute([$dosen_id]);
    $total_skrining = $stmtCount->fetchColumn();

    // 2. Total Risiko Tinggi & Rendah pada skrining terkini mahasiswa bimbingan dosen ini
    $stmtTinggi = $pdo->prepare("SELECT COUNT(*) FROM `skrining` WHERE `dosen_id` = ? AND `hasil_prediksi` = 1");
    $stmtTinggi->execute([$dosen_id]);
    $total_tinggi = $stmtTinggi->fetchColumn();

    $stmtRendah = $pdo->prepare("SELECT COUNT(*) FROM `skrining` WHERE `dosen_id` = ? AND `hasil_prediksi` = 0");
    $stmtRendah->execute([$dosen_id]);
    $total_rendah = $stmtRendah->fetchColumn();

    // 3. Perlu Konseling (Risiko tinggi & Belum ditindaklanjuti)
    $stmtKonsel = $pdo->prepare("SELECT COUNT(*) FROM `skrining` WHERE `dosen_id` = ? AND `hasil_prediksi` = 1 AND `status_konseling` = 'Belum Ditindaklanjuti'");
    $stmtKonsel->execute([$dosen_id]);
    $total_perlu_konseling = $stmtKonsel->fetchColumn();

    // 4. Ambil 5 data skrining terbaru dari mahasiswa bimbingan dosen ini
    $stmtRecent = $pdo->prepare("SELECT s.*, m.jenis_kelamin 
                                 FROM `skrining` s 
                                 JOIN `mahasiswa` m ON s.mahasiswa_id = m.id 
                                 WHERE s.`dosen_id` = ? 
                                 ORDER BY s.`created_at` DESC LIMIT 5");
    $stmtRecent->execute([$dosen_id]);
    $latest_data = $stmtRecent->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Monitoring Dosen PA - MindCare SPK</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include 'navbar_dosen.php'; ?>

<main class="container my-4">
  
  <!-- Welcome Banner Dosen -->
  <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
      <div>
        <span class="badge bg-white text-primary px-3 py-1 rounded-pill mb-2 fw-semibold">Panel Dosen Pembimbing Akademik</span>
        <h3 class="fw-bold mb-1">Selamat Datang, <?= htmlspecialchars($dosen_nama) ?>!</h3>
        <p class="mb-0 opacity-75">NIDN: <strong><?= htmlspecialchars($dosen_nidn) ?></strong> &bull; Anda membimbing dan mengampu <strong>20 Mahasiswa Perwalian</strong>.</p>
      </div>
      <div>
        <a href="data_mahasiswa.php" class="btn btn-light text-primary fw-semibold px-4 py-2 rounded-pill shadow-sm">
          <i class="bi bi-people-fill me-1"></i> Data 20 Mahasiswa Bimbingan
        </a>
      </div>
    </div>
  </div>

  <!-- 4 Stat Cards -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm border-0 stat-card h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4 fs-3">
            <i class="bi bi-people"></i>
          </div>
          <div>
            <div class="text-muted small">Total Perwalian</div>
            <h3 class="fw-bold mb-0">20 <small class="fs-6 fw-normal text-muted">Mahasiswa</small></h3>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm border-0 stat-card h-100 border-start border-info border-4">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-3 bg-info bg-opacity-10 text-info rounded-4 fs-3">
            <i class="bi bi-clipboard2-check"></i>
          </div>
          <div>
            <div class="text-muted small">Sudah Skrining</div>
            <h3 class="fw-bold text-info mb-0"><?= $total_skrining ?> <small class="fs-6 fw-normal text-muted">/ 20</small></h3>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm border-0 stat-card h-100 border-start border-danger border-4">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-4 fs-3">
            <i class="bi bi-exclamation-triangle-fill"></i>
          </div>
          <div>
            <div class="text-muted small">Risiko Tinggi (Stres)</div>
            <h3 class="fw-bold text-danger mb-0"><?= $total_tinggi ?></h3>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm border-0 stat-card h-100 border-start border-warning border-4">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-4 fs-3">
            <i class="bi bi-chat-heart-fill"></i>
          </div>
          <div>
            <div class="text-muted small">Perlu Dijadwalkan</div>
            <h3 class="fw-bold text-warning mb-0"><?= $total_perlu_konseling ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts & Info Row -->
  <div class="row g-4 mb-4">
    <div class="col-lg-5">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2 text-primary"></i>Proporsi Risiko Mahasiswa Bimbingan</h6>
        </div>
        <div class="card-body p-4 text-center">
          <?php if (($total_tinggi + $total_rendah) > 0): ?>
            <div style="max-width: 260px; margin: 0 auto;">
              <canvas id="chartProporsi"></canvas>
            </div>
            <div class="row mt-3 text-center">
              <div class="col-6 border-end">
                <span class="text-muted small">Risiko Rendah</span>
                <h5 class="fw-bold text-success mb-0"><?= $total_rendah ?> Mhs</h5>
              </div>
              <div class="col-6">
                <span class="text-muted small">Risiko Tinggi</span>
                <h5 class="fw-bold text-danger mb-0"><?= $total_tinggi ?> Mhs</h5>
              </div>
            </div>
          <?php else: ?>
            <p class="text-muted my-5">Belum ada mahasiswa bimbingan yang mengisi skrining.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i>Tugas & Panduan Dosen Pembimbing Akademik</h6>
        </div>
        <div class="card-body p-4">
          <div class="alert alert-info border-info-subtle mb-3">
            <h6 class="fw-bold mb-1"><i class="bi bi-lightbulb-fill me-1"></i> Peran SPK AI dalam Bimbingan:</h6>
            <p class="mb-0 small">Sistem ini membantu Bapak/Ibu mendeteksi mahasiswa yang mengalami beban mental/stres akademik sejak dini sehingga tindakan preventif seperti penjadwalan bimbingan konseling dapat segera dilakukan.</p>
          </div>
          <h6 class="fw-bold small text-uppercase text-muted mb-2">Langkah Kerja Dosen PA:</h6>
          <ol class="small text-muted ps-3 mb-0 d-flex flex-column gap-2">
            <li>Buka menu <strong>Data Skrining Mahasiswa</strong> untuk melihat daftar 20 mahasiswa perwalian Anda.</li>
            <li>Prioritaskan mahasiswa dengan indikator <strong>Risiko Tinggi</strong> (terutama yang memiliki pemikiran menyakiti diri atau tekanan akademik ekstrem).</li>
            <li>Klik tombol <strong>Detail</strong> pada mahasiswa terkait untuk memberikan catatan bimbingan dan mengubah status menjadi <strong>Dijadwalkan Konseling</strong>.</li>
            <li>Mahasiswa akan otomatis menerima <strong>notifikasi jadwal bimbingan</strong> saat mereka login ke akun masing-masing.</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Data Skrining Terbaru -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
      <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Pengisian Skrining Terkini Mahasiswa Bimbingan</h6>
      <a href="data_mahasiswa.php" class="btn btn-outline-primary btn-sm rounded-pill">
        Kelola 20 Mahasiswa <i class="bi bi-arrow-right"></i>
      </a>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Waktu</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <th>IPK</th>
            <th>Hasil SPK AI</th>
            <th>Status Konseling</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($latest_data)): ?>
            <?php foreach ($latest_data as $mhs): ?>
              <tr>
                <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($mhs['created_at'])) ?></td>
                <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($mhs['nim']) ?></span></td>
                <td><strong><?= htmlspecialchars($mhs['nama_mahasiswa']) ?></strong></td>
                <td><?= number_format($mhs['ipk'], 2) ?></td>
                <td>
                  <?php if ($mhs['hasil_prediksi'] == 1): ?>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1">
                      <i class="bi bi-exclamation-octagon-fill me-1"></i> Risiko Tinggi (<?= $mhs['probabilitas_tinggi'] ?>%)
                    </span>
                  <?php else: ?>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1">
                      <i class="bi bi-check-circle-fill me-1"></i> Risiko Rendah (<?= $mhs['probabilitas_rendah'] ?>%)
                    </span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($mhs['status_konseling'] === 'Dijadwalkan Konseling'): ?>
                    <span class="badge bg-warning text-dark">Dijadwalkan Konseling</span>
                  <?php elseif ($mhs['status_konseling'] === 'Selesai Konseling'): ?>
                    <span class="badge bg-success">Selesai</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Belum Ditindaklanjuti</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <a href="detail_mahasiswa.php?id=<?= $mhs['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="bi bi-eye me-1"></i> Detail & Catatan
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center py-4 text-muted">Belum ada data skrining dari mahasiswa perwalian Anda.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</main>

<footer class="footer mt-auto py-3 bg-light border-top">
  <div class="container text-center text-muted small">
    MindCare SPK Dosen &copy; <?= date('Y') ?> &bull; Dosen PA: <?= htmlspecialchars($dosen_nama) ?>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php if (($total_tinggi + $total_rendah) > 0): ?>
<script>
  const ctxProporsi = document.getElementById('chartProporsi').getContext('2d');
  new Chart(ctxProporsi, {
    type: 'doughnut',
    data: {
      labels: ['Risiko Rendah', 'Risiko Tinggi'],
      datasets: [{
        data: [<?= $total_rendah ?>, <?= $total_tinggi ?>],
        backgroundColor: ['#198754', '#dc3545'],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } },
      cutout: '70%'
    }
  });
</script>
<?php endif; ?>

</body>
</html>
