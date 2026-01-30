<?php
session_start();
require_once __DIR__ . '/includes/config.php';

$err = "";
$ok  = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $role      = trim($_POST['role'] ?? 'user');

    if ($username === '' || $password === '' || $password2 === '') {
        $err = "Semua field wajib diisi.";
    } elseif ($password !== $password2) {
        $err = "Password konfirmasi tidak sama.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $err = "Username sudah dipakai.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password_hash, full_name, role) VALUES (?,?,?,?)");
            $stmt->bind_param('ssss', $username, $hash, $full_name, $role);
            if ($stmt->execute()) {
                $ok = "Registrasi berhasil! Silakan login.";
            } else {
                $err = "Gagal registrasi: " . $conn->error;
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="form-auth card overflow-hidden shadow-lg">
  <div class="row g-0">
    <div class="col-md-6 d-none d-md-block">
      <img src="<?php echo BASE_URL; ?>/assets/img/1.png" alt="Register Image" class="img-fluid h-100 w-100" style="object-fit:cover;">
    </div>
    <div class="col-md-6 bg-dark bg-opacity-75 text-white p-4">
      <h2 class="mb-3 text-center">Register</h2>
      <?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
      <?php if($ok):  ?><div class="alert alert-success"><?php echo htmlspecialchars($ok); ?></div><?php endif; ?>
      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="full_name" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <select class="form-select" name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Ulangi Password</label>
          <input type="password" name="password2" class="form-control" required>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-success">Register</button>
          <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-link text-white">Sudah punya akun? Login</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
