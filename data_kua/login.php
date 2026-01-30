<?php
session_start();
require_once __DIR__ . '/includes/config.php';

$err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $err = "Username dan password wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password_hash, full_name, role FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['role'] = $row['role'];
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $err = "Password salah.";
            }
        } else {
            $err = "User tidak ditemukan.";
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="form-auth card overflow-hidden shadow-lg">
  <div class="row g-0">
    <div class="col-md-6 d-none d-md-block">
      <img src="<?php echo BASE_URL; ?>/assets/img/1.png" alt="Login Image" class="img-fluid h-100 w-100" style="object-fit:cover;">
    </div>
    <div class="col-md-6 bg-dark bg-opacity-75 text-white p-4">
      <h2 class="mb-3 text-center">Login</h2>
      <?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Login</button>
          <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-link text-white">Belum punya akun? Register</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
