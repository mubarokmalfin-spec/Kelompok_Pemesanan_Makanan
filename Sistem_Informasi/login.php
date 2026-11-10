<?php
require_once 'koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'dosen') {
        header('Location: dosen/dashboard.php');
        exit;
    } elseif ($_SESSION['role'] === 'mahasiswa') {
        header('Location: dashboard_mahasiswa.php');
        exit;
    }
}

$error = null;
$active_tab = $_POST['role_login'] ?? ($_GET['tab'] ?? 'mahasiswa');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = trim($_POST['role_login'] ?? 'mahasiswa');
    $active_tab = $role;

    if ($role === 'mahasiswa') {
        $nim = trim($_POST['nim'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($nim) || empty($password)) {
            $error = 'NIM dan Password wajib diisi.';
        } else {
            if ($pdo) {
                $stmt = $pdo->prepare("SELECT m.*, d.nama_dosen, d.nidn, d.email as email_dosen 
                                       FROM `mahasiswa` m 
                                       JOIN `dosen` d ON m.dosen_id = d.id 
                                       WHERE m.`nim` = ?");
                $stmt->execute([$nim]);
                $mhs = $stmt->fetch();

                if ($mhs && (password_verify($password, $mhs['password']) || $password === $mhs['password_plain'])) {
                    $_SESSION['user_id'] = $mhs['id'];
                    $_SESSION['nim'] = $mhs['nim'];
                    $_SESSION['nama_lengkap'] = $mhs['nama_mahasiswa'];
                    $_SESSION['prodi'] = $mhs['prodi'];
                    $_SESSION['angkatan'] = $mhs['angkatan'];
                    $_SESSION['jenis_kelamin'] = $mhs['jenis_kelamin'];
                    $_SESSION['usia'] = $mhs['usia'];
                    $_SESSION['dosen_id'] = $mhs['dosen_id'];
                    $_SESSION['nama_dosen_pa'] = $mhs['nama_dosen'];
                    $_SESSION['nidn_dosen_pa'] = $mhs['nidn'];
                    $_SESSION['role'] = 'mahasiswa';

                    header('Location: dashboard_mahasiswa.php');
                    exit;
                } else {
                    $error = 'NIM atau Password salah. Silakan cek file DAFTAR_AKUN.md untuk melihat kredensial.';
                }
            } else {
                $error = 'Koneksi ke database gagal.';
            }
        }
    } elseif ($role === 'dosen') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = 'Username dan Password wajib diisi.';
        } else {
            if ($pdo) {
                $stmt = $pdo->prepare("SELECT * FROM `dosen` WHERE `username` = ?");
                $stmt->execute([$username]);
                $dosen = $stmt->fetch();

                if ($dosen && (password_verify($password, $dosen['password']) || $password === $dosen['password_plain'])) {
                    $_SESSION['user_id'] = $dosen['id'];
                    $_SESSION['username'] = $dosen['username'];
                    $_SESSION['nama_lengkap'] = $dosen['nama_dosen'];
                    $_SESSION['nidn'] = $dosen['nidn'];
                    $_SESSION['role'] = 'dosen';

                    header('Location: dosen/dashboard.php');
                    exit;
                } else {
                    $error = 'Username atau Password salah. Silakan cek file DAFTAR_AKUN.md untuk melihat kredensial.';
                }
            } else {
                $error = 'Koneksi ke database gagal.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Pengguna - MindCare SPK</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100 justify-content-between">

<?php include 'navbar.php'; ?>

<main class="container my-auto py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      
      <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
          
          <div class="text-center mb-4">
            <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 60px; height: 60px; font-size: 1.75rem;">
              <i class="bi bi-shield-lock-fill"></i>
            </span>
            <h4 class="fw-bold mb-1">Masuk ke Sistem</h4>
            <p class="text-muted small">Pilih peran akun Anda untuk melanjutkan</p>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
              <i class="bi bi-exclamation-circle-fill me-1"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <!-- Nav Pills: Mahasiswa & Dosen -->
          <ul class="nav nav-pills nav-fill mb-4 bg-light p-1 rounded-pill" id="loginTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link rounded-pill py-2 <?= ($active_tab === 'mahasiswa') ? 'active' : '' ?>" id="tab-mahasiswa" data-bs-toggle="pill" data-bs-target="#content-mahasiswa" type="button" role="tab">
                <i class="bi bi-person-fill me-1"></i> Mahasiswa
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link rounded-pill py-2 <?= ($active_tab === 'dosen') ? 'active' : '' ?>" id="tab-dosen" data-bs-toggle="pill" data-bs-target="#content-dosen" type="button" role="tab">
                <i class="bi bi-person-badge-fill me-1"></i> Dosen PA
              </button>
            </li>
          </ul>

          <div class="tab-content" id="loginTabContent">
            
            <!-- FORM LOGIN MAHASISWA -->
            <div class="tab-pane fade <?= ($active_tab === 'mahasiswa') ? 'show active' : '' ?>" id="content-mahasiswa" role="tabpanel">
              <form action="login.php" method="POST">
                <input type="hidden" name="role_login" value="mahasiswa">
                
                <div class="mb-3">
                  <label for="nim" class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-credit-card-2-front text-muted"></i></span>
                    <input type="text" class="form-control" id="nim" name="nim" placeholder="Contoh: 230101001" required value="<?= htmlspecialchars($_POST['nim'] ?? '230101001') ?>">
                  </div>
                </div>

                <div class="mb-4">
                  <label for="password_mhs" class="form-label">Password Mahasiswa</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-key text-muted"></i></span>
                    <input type="password" class="form-control" id="password_mhs" name="password" placeholder="Masukkan password unik mahasiswa" required>
                  </div>
                  <div class="form-text small">Password unik tertera pada file <code>DAFTAR_AKUN.md</code></div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-semibold shadow-sm">
                  <i class="bi bi-box-arrow-in-right me-1"></i> Masuk sebagai Mahasiswa
                </button>
              </form>
            </div>

            <!-- FORM LOGIN DOSEN -->
            <div class="tab-pane fade <?= ($active_tab === 'dosen') ? 'show active' : '' ?>" id="content-dosen" role="tabpanel">
              <form action="login.php" method="POST">
                <input type="hidden" name="role_login" value="dosen">

                <div class="mb-3">
                  <label for="username_dosen" class="form-label">Username Dosen</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" class="form-control" id="username_dosen" name="username" placeholder="Contoh: dosen_hendra" required value="<?= htmlspecialchars($_POST['username'] ?? 'dosen_hendra') ?>">
                  </div>
                  <div class="form-text small">Pilihan: <code>dosen_hendra</code>, <code>dosen_sri</code>, <code>dosen_agus</code>, <code>dosen_dewi</code>, <code>dosen_bambang</code></div>
                </div>

                <div class="mb-4">
                  <label for="password_dosen" class="form-label">Password Dosen</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-key text-muted"></i></span>
                    <input type="password" class="form-control" id="password_dosen" name="password" placeholder="Masukkan password unik dosen" required>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-semibold shadow-sm">
                  <i class="bi bi-box-arrow-in-right me-1"></i> Masuk sebagai Dosen PA
                </button>
              </form>
            </div>

          </div>

          <div class="mt-4 p-3 bg-light rounded-3 small text-muted text-center border">
            <div><i class="bi bi-info-circle me-1 text-primary"></i> <strong>Informasi Kredensial Akun:</strong></div>
            <div>Buka file <strong><code>DAFTAR_AKUN.md</code></strong> untuk melihat seluruh 5 akun Dosen dan 100 akun Mahasiswa beserta password unik masing-masing.</div>
          </div>

        </div>
      </div>

    </div>
  </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
