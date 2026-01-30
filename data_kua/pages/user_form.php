<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$username = $full_name = $role = '';
$info_msg = '';

if ($id > 0) {
    $stmt = $conn->prepare("SELECT username, full_name, role FROM users WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $username = $row['username'];
        $full_name = $row['full_name'];
        $role = $row['role'];
    } else {
        $info_msg = "User tidak ditemukan.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $role = trim($_POST['role'] ?? 'user');
    $password = $_POST['password'] ?? '';

    if ($id > 0) {
        // update (password opsional)
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username=?, full_name=?, role=?, password_hash=? WHERE id=?");
            $stmt->bind_param('ssssi', $username, $full_name, $role, $hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, full_name=?, role=? WHERE id=?");
            $stmt->bind_param('sssi', $username, $full_name, $role, $id);
        }
        $stmt->execute();
    } else {
        // insert baru
        if ($username === '' || $password === '') {
            $info_msg = "Username & Password wajib.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password_hash, full_name, role) VALUES (?,?,?,?)");
            $stmt->bind_param('ssss', $username, $hash, $full_name, $role);
            $stmt->execute();
        }
    }
    header('Location: ' . BASE_URL . '/pages/users.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3"><?php echo $id>0?'Edit User':'Tambah User'; ?></h1>
<?php if($info_msg): ?><div class="alert alert-info"><?php echo htmlspecialchars($info_msg); ?></div><?php endif; ?>
<form method="post" action="">
<input type="hidden" name="id" value="<?php echo $id; ?>">
  <div class="mb-3">
    <label class="form-label">Username</label>
    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Nama Lengkap</label>
    <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($full_name); ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Role</label>
    <select name="role" class="form-select">
      <option value="user" <?php echo $role==='user'?'selected':''; ?>>User</option>
      <option value="admin" <?php echo $role==='admin'?'selected':''; ?>>Admin</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Password <?php echo $id>0?'(kosongkan jika tidak ubah)':''; ?></label>
    <input type="password" name="password" class="form-control">
  </div>
  <div class="d-grid gap-2 d-sm-flex justify-content-sm-start">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo BASE_URL; ?>/pages/users.php" class="btn btn-secondary">Batal</a>
  </div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
