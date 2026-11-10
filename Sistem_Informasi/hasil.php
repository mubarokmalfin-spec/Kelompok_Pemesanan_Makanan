<?php
require_once 'koneksi.php';

$data = null;
$id = intval($_GET['id'] ?? 0);

if ($id > 0 && $pdo) {
    $stmt = $pdo->prepare("SELECT s.*, d.nama_dosen, d.nidn 
                           FROM `skrining` s 
                           LEFT JOIN `dosen` d ON s.dosen_id = d.id 
                           WHERE s.`id` = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();
}

if (!$data) {
    header('Location: index.php');
    exit;
}

$is_high_risk = ($data['hasil_prediksi'] == 1);
$factors_list = array_map('trim', explode(',', $data['faktor_utama']));

$back_url = 'index.php';
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'mahasiswa') {
        $back_url = 'dashboard_mahasiswa.php';
    } elseif ($_SESSION['role'] === 'dosen') {
        $back_url = 'dosen/data_mahasiswa.php';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil Analisis Skrining - MindCare SPK</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    @media print {
      .navbar, .footer, .no-print {
        display: none !important;
      }
      body {
        background: #fff !important;
      }
      .card {
        border: 1px solid #ccc !important;
        box-shadow: none !important;
      }
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-4">
  
  <div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <a href="<?= $back_url ?>" class="btn btn-outline-secondary btn-sm rounded-pill">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
    <div class="d-flex gap-2">
      <button onclick="window.print()" class="btn btn-primary btn-sm rounded-pill shadow-sm">
        <i class="bi bi-printer me-1"></i> Cetak Lembar Hasil
      </button>
    </div>
  </div>

  <div class="row g-4 justify-content-center">
    
    <!-- Kolom Kiri: Ringkasan Hasil & Visualisasi -->
    <div class="col-lg-5">
      
      <!-- Card Status Prediksi -->
      <div class="card shadow-sm border-0 mb-4 overflow-hidden">
        <div class="card-body p-4 text-center <?= $is_high_risk ? 'bg-danger bg-opacity-10' : 'bg-success bg-opacity-10' ?>">
          <div class="mb-3">
            <span class="d-inline-flex align-items-center justify-content-center rounded-circle <?= $is_high_risk ? 'bg-danger text-white' : 'bg-success text-white' ?>" style="width: 72px; height: 72px; font-size: 2rem;">
              <i class="bi <?= $is_high_risk ? 'bi-exclamation-octagon-fill' : 'bi-check-circle-fill' ?>"></i>
            </span>
          </div>
          <span class="badge <?= $is_high_risk ? 'bg-danger' : 'bg-success' ?> px-3 py-2 rounded-pill fs-6 mb-2">
            <?= htmlspecialchars($data['kategori']) ?>
          </span>
          <h4 class="fw-bold mb-1">
            <?= $is_high_risk ? 'Perlu Perhatian & Bimbingan' : 'Kondisi Mental Relatif Stabil' ?>
          </h4>
          <p class="text-muted small mb-0">
            Dianalisis pada <?= date('d M Y, H:i', strtotime($data['created_at'])) ?> WIB
          </p>
        </div>
      </div>

      <!-- Card Chart Probabilitas -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Probabilitas Tingkat Risiko</h6>
        </div>
        <div class="card-body p-4 text-center">
          <div style="max-width: 250px; margin: 0 auto;">
            <canvas id="riskChart"></canvas>
          </div>
          <div class="row mt-3 text-center">
            <div class="col-6 border-end">
              <span class="text-muted small">Risiko Rendah</span>
              <h5 class="fw-bold text-success mb-0"><?= $data['probabilitas_rendah'] ?>%</h5>
            </div>
            <div class="col-6">
              <span class="text-muted small">Risiko Tinggi</span>
              <h5 class="fw-bold text-danger mb-0"><?= $data['probabilitas_tinggi'] ?>%</h5>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Kolom Kanan: Detail & Catatan Rekomendasi -->
    <div class="col-lg-7">
      
      <!-- Card Data Diri Mahasiswa & Dosen PA -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Identitas Mahasiswa & Pembimbing</h6>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-sm-6">
              <small class="text-muted d-block">Nama Lengkap</small>
              <strong class="fs-6"><?= htmlspecialchars($data['nama_mahasiswa']) ?></strong>
            </div>
            <div class="col-sm-6">
              <small class="text-muted d-block">NIM</small>
              <strong class="fs-6 font-monospace"><?= htmlspecialchars($data['nim']) ?></strong>
            </div>
            <div class="col-sm-6">
              <small class="text-muted d-block">Program Studi & Angkatan</small>
              <span><?= htmlspecialchars($data['prodi']) ?> (<?= htmlspecialchars($data['angkatan']) ?>)</span>
            </div>
            <div class="col-sm-6">
              <small class="text-muted d-block">Dosen Pembimbing Akademik</small>
              <span class="text-primary fw-semibold"><?= htmlspecialchars($data['nama_dosen'] ?? 'Dosen PA') ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Faktor Utama Pemicu -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-diagram-3-fill me-2 text-primary"></i>Faktor Pemicu Risiko Teratas (Feature Importance)</h6>
        </div>
        <div class="card-body p-4">
          <p class="small text-muted mb-3">Model mengidentifikasi 3 indikator yang memiliki pengaruh paling signifikan terhadap kondisi tingkat stres kamu:</p>
          <div class="d-flex flex-column gap-2">
            <?php foreach ($factors_list as $index => $faktor): ?>
              <div class="d-flex align-items-center justify-content-between p-3 rounded-3 <?= $is_high_risk ? 'bg-danger bg-opacity-10 border border-danger-subtle' : 'bg-light border' ?>">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge <?= $is_high_risk ? 'bg-danger' : 'bg-primary' ?> rounded-pill">#<?= $index + 1 ?></span>
                  <span class="fw-semibold"><?= htmlspecialchars($faktor) ?></span>
                </div>
                <i class="bi bi-graph-up-arrow <?= $is_high_risk ? 'text-danger' : 'text-primary' ?>"></i>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Card Catatan Refleksi & Rekomendasi Tindakan -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-lightbulb-fill me-2 text-warning"></i>Catatan Refleksi & Rekomendasi Solusi</h6>
        </div>
        <div class="card-body p-4">
          <div class="alert <?= $is_high_risk ? 'alert-warning' : 'alert-success' ?> mb-3">
            <div class="fw-semibold mb-1"><i class="bi bi-info-circle me-1"></i> Catatan Sistem:</div>
            <p class="mb-0 small"><?= htmlspecialchars($data['catatan_refleksi']) ?></p>
          </div>

          <h6 class="fw-bold small text-uppercase text-muted mb-2">Langkah Tindak Lanjut yang Disarankan:</h6>
          <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
            <?php if ($is_high_risk): ?>
              <li class="d-flex align-items-start gap-2">
                <i class="bi bi-check2-circle text-danger fs-6"></i>
                <span><strong>Konsultasi Akademik:</strong> Jadwalkan bimbingan konseling dengan Dosen Pembimbing Akademik (PA) untuk mendiskusikan beban SKS dan target perkuliahan.</span>
              </li>
              <li class="d-flex align-items-start gap-2">
                <i class="bi bi-check2-circle text-danger fs-6"></i>
                <span><strong>Perbaiki Pola Istirahat:</strong> Hindari begadang berlebihan, targetkan tidur minimal 6-7 jam agar fungsi kognitif otak pulih optimal.</span>
              </li>
              <li class="d-flex align-items-start gap-2">
                <i class="bi bi-check2-circle text-danger fs-6"></i>
                <span><strong>Cari Dukungan Sosial:</strong> Ceritakan kendalamu pada teman dekat, keluarga, atau manfaatkan layanan konseling psikologi kampus. Anda tidak sendirian.</span>
              </li>
            <?php else: ?>
              <li class="d-flex align-items-start gap-2">
                <i class="bi bi-check2-circle text-success fs-6"></i>
                <span><strong>Pertahankan Ritme Belajar:</strong> Lanjutkan manajemen waktu belajar harian yang seimbang dengan waktu hiburan dan istirahat.</span>
              </li>
              <li class="d-flex align-items-start gap-2">
                <i class="bi bi-check2-circle text-success fs-6"></i>
                <span><strong>Jaga Pola Makan & Olahraga:</strong> Pola makan bergizi terbukti menunjang fokus dan ketahanan stres jangka panjang.</span>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>

    </div>

  </div>
</main>

<?php include 'footer.php'; ?>

<script>
  const ctx = document.getElementById('riskChart').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Risiko Rendah', 'Risiko Tinggi'],
      datasets: [{
        data: [<?= $data['probabilitas_rendah'] ?>, <?= $data['probabilitas_tinggi'] ?>],
        backgroundColor: ['#198754', '#dc3545'],
        hoverOffset: 4,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { font: { size: 12 }, padding: 15 }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return ' ' + context.label + ': ' + context.parsed + '%';
            }
          }
        }
      },
      cutout: '70%'
    }
  });
</script>

</body>
</html>
