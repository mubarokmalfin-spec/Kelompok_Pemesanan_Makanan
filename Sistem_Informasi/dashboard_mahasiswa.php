<?php
require_once 'koneksi.php';

// Proteksi Halaman: Mahasiswa Wajib Login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: login.php?tab=mahasiswa');
    exit;
}

$mhs_id = $_SESSION['user_id'];
$riwayat_skrining = [];
$notif_konseling = null;

if ($pdo) {
    // 1. Ambil data terbaru mahasiswa & dosen PA
    $stmtMhs = $pdo->prepare("SELECT m.*, d.nama_dosen, d.nidn, d.email as email_dosen 
                              FROM `mahasiswa` m 
                              JOIN `dosen` d ON m.dosen_id = d.id 
                              WHERE m.`id` = ?");
    $stmtMhs->execute([$mhs_id]);
    $profil = $stmtMhs->fetch();

    // 2. Ambil seluruh riwayat skrining mahasiswa ini
    $stmtRiwayat = $pdo->prepare("SELECT * FROM `skrining` WHERE `mahasiswa_id` = ? ORDER BY `created_at` DESC");
    $stmtRiwayat->execute([$mhs_id]);
    $riwayat_skrining = $stmtRiwayat->fetchAll();

    // 3. Cek apakah ada notifikasi bimbingan konseling aktif dari Dosen PA
    if (!empty($riwayat_skrining)) {
        $latest = $riwayat_skrining[0];
        if ($latest['status_konseling'] === 'Dijadwalkan Konseling') {
            $notif_konseling = [
                'tipe' => 'warning',
                'judul' => 'Panggilan Bimbingan / Konseling Akademik',
                'pesan' => $latest['catatan_dosen'] ?: 'Dosen Pembimbing Akademik Anda telah menjadwalkan bimbingan konseling terkait hasil skrining kesehatan mental dan beban studi.',
                'dosen' => $profil['nama_dosen']
            ];
        } elseif ($latest['status_konseling'] === 'Selesai Konseling') {
            $notif_konseling = [
                'tipe' => 'success',
                'judul' => 'Sesi Konseling Selesai',
                'pesan' => 'Sesi konseling telah selesai dilaksanakan. Tetap jaga semangat perkuliahan dan pola hidup seimbang!',
                'dosen' => $profil['nama_dosen']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Mahasiswa - MindCare SPK</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-4">

  <!-- NOTIFIKASI JIKA ADA JADWAL BIMBINGAN DARI DOSEN PA -->
  <?php if ($notif_konseling): ?>
    <div class="alert alert-<?= $notif_konseling['tipe'] ?> border-<?= $notif_konseling['tipe'] ?> shadow-sm p-4 rounded-4 mb-4" role="alert">
      <div class="d-flex align-items-start gap-3">
        <span class="fs-1 text-<?= $notif_konseling['tipe'] ?>">
          <i class="bi bi-bell-fill"></i>
        </span>
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="alert-heading fw-bold mb-1">
              <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($notif_konseling['judul']) ?>
            </h5>
            <span class="badge bg-<?= $notif_konseling['tipe'] ?> text-uppercase px-2 py-1">Pemberitahuan Dosen PA</span>
          </div>
          <p class="mb-2 fs-6">
            <?= nl2br(htmlspecialchars($notif_konseling['pesan'])) ?>
          </p>
          <hr class="my-2">
          <small class="text-muted">
            <i class="bi bi-person-check-fill me-1"></i> Dosen Pembimbing Akademik: <strong><?= htmlspecialchars($notif_konseling['dosen']) ?></strong>
          </small>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- HEADER PROFILE & DOSEN PA -->
  <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
      <div>
        <span class="badge bg-white text-primary px-3 py-1 rounded-pill mb-2 fw-semibold">Portal Mahasiswa</span>
        <h3 class="fw-bold mb-1">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>!</h3>
        <p class="mb-0 opacity-75">
          NIM: <strong><?= htmlspecialchars($_SESSION['nim']) ?></strong> &bull; 
          Program Studi: <strong><?= htmlspecialchars($_SESSION['prodi']) ?></strong> &bull; 
          Angkatan: <strong><?= htmlspecialchars($_SESSION['angkatan']) ?></strong>
        </p>
      </div>
      <div>
        <a href="index.php" class="btn btn-light text-primary fw-semibold px-4 py-2 rounded-pill shadow-sm">
          <i class="bi bi-clipboard2-pulse-fill me-1"></i> Mulai Skrining Baru
        </a>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    
    <!-- Kartu Informasi Dosen Pembimbing Akademik -->
    <div class="col-md-5">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-person-badge text-primary me-2"></i>Dosen Pembimbing Akademik (Dosen PA)</h6>
        </div>
        <div class="card-body p-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="avatar-badge bg-primary text-white fs-4" style="width: 54px; height: 54px;">
              <i class="bi bi-person-workspace"></i>
            </div>
            <div>
              <h5 class="fw-bold mb-0 text-primary"><?= htmlspecialchars($_SESSION['nama_dosen_pa']) ?></h5>
              <small class="text-muted">NIDN: <?= htmlspecialchars($_SESSION['nidn_dosen_pa']) ?></small>
            </div>
          </div>
          <div class="p-3 bg-light rounded-3 small">
            <p class="mb-1 text-muted">Dosen PA bertindak sebagai pembimbing dalam memantau perkembangan akademik serta kondisi kesehatan psikologis Anda selama masa studi.</p>
            <div class="text-muted"><i class="bi bi-info-circle me-1"></i> Jika Anda mengalami kendala atau stres berat, silakan berkonsultasi langsung.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kartu Status Skrining Terkini -->
    <div class="col-md-7">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="mb-0 fw-bold"><i class="bi bi-activity text-primary me-2"></i>Status Skrining Terkini</h6>
        </div>
        <div class="card-body p-4">
          <?php if (!empty($riwayat_skrining)): 
            $latest = $riwayat_skrining[0];
            $is_high = ($latest['hasil_prediksi'] == 1);
          ?>
            <div class="d-flex align-items-center justify-content-between p-3 rounded-4 <?= $is_high ? 'bg-danger bg-opacity-10 border border-danger-subtle' : 'bg-success bg-opacity-10 border border-success-subtle' ?> mb-3">
              <div>
                <span class="badge <?= $is_high ? 'bg-danger' : 'bg-success' ?> px-3 py-1 rounded-pill mb-1">
                  <?= htmlspecialchars($latest['kategori']) ?>
                </span>
                <h5 class="fw-bold mb-0 <?= $is_high ? 'text-danger' : 'text-success' ?>">
                  <?= $is_high ? 'Tingkat Stres / Risiko Tinggi' : 'Kondisi Mental Stabil' ?>
                </h5>
                <small class="text-muted">Dianalisis pada <?= date('d M Y, H:i', strtotime($latest['created_at'])) ?> WIB</small>
              </div>
              <div class="text-end">
                <span class="text-muted small d-block">Probabilitas</span>
                <h4 class="fw-bold mb-0 <?= $is_high ? 'text-danger' : 'text-success' ?>">
                  <?= $is_high ? $latest['probabilitas_tinggi'] : $latest['probabilitas_rendah'] ?>%
                </h4>
              </div>
            </div>
            <a href="hasil.php?id=<?= $latest['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill">
              <i class="bi bi-file-earmark-medical me-1"></i> Buka Lembar Hasil Lengkap
            </a>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="bi bi-clipboard2-check display-6 text-muted mb-2 d-block"></i>
              <p class="text-muted mb-3">Anda belum pernah melakukan skrining kesehatan mental.</p>
              <a href="index.php" class="btn btn-primary rounded-pill btn-sm px-4">
                Mulai Skrining Sekarang
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

  <!-- TABEL RIWAYAT SKRINING MAHASISWA -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
      <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pemeriksaan Anda</h6>
      <span class="badge bg-light text-dark border">Total: <?= count($riwayat_skrining) ?> Kali Skrining</span>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Tanggal Skrining</th>
            <th>IPK Saat Tes</th>
            <th>Hasil Klasifikasi AI</th>
            <th>Probabilitas Stres</th>
            <th>Status Tindak Lanjut</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($riwayat_skrining)): ?>
            <?php foreach ($riwayat_skrining as $idx => $r): ?>
              <tr>
                <td><?= $idx + 1 ?></td>
                <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?> WIB</td>
                <td><span class="fw-semibold"><?= number_format($r['ipk'], 2) ?></span></td>
                <td>
                  <?php if ($r['hasil_prediksi'] == 1): ?>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1">
                      <i class="bi bi-exclamation-octagon-fill me-1"></i> Risiko Tinggi
                    </span>
                  <?php else: ?>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1">
                      <i class="bi bi-check-circle-fill me-1"></i> Risiko Rendah
                    </span>
                  <?php endif; ?>
                </td>
                <td><strong><?= $r['probabilitas_tinggi'] ?>%</strong></td>
                <td>
                  <?php if ($r['status_konseling'] === 'Dijadwalkan Konseling'): ?>
                    <span class="badge bg-warning text-dark"><i class="bi bi-calendar-event me-1"></i> Dijadwalkan Konseling</span>
                  <?php elseif ($r['status_konseling'] === 'Selesai Konseling'): ?>
                    <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i> Selesai Konseling</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Tersimpan</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <a href="hasil.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="bi bi-eye me-1"></i> Hasil Detail
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat pengisian kuesioner.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</main>

<?php include 'footer.php'; ?>

</body>
</html>
