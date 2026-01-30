<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';

// hanya admin
if ($_SESSION['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    exit;
}

// ambil semua user
$res = $conn->query("SELECT id, username, full_name, role, created_at FROM users ORDER BY id ASC");

include __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3">Manajemen Akun</h1>
<a href="<?php echo BASE_URL; ?>/pages/user_form.php" class="btn btn-success mb-3">Tambah User</a>
<div class="table-responsive">
<table class="table table-striped table-hover align-middle bg-white rounded">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Nama</th>
      <th>Role</th>
      <th>Dibuat</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php while($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo htmlspecialchars($row['username']); ?></td>
      <td><?php echo htmlspecialchars($row['full_name']); ?></td>
      <td><?php echo htmlspecialchars($row['role']); ?></td>
      <td><?php echo $row['created_at']; ?></td>
      <td class="table-actions">
        <a href="<?php echo BASE_URL; ?>/pages/user_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="<?php echo BASE_URL; ?>/pages/user_reset.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" onclick="return confirm('Reset password ke default?');">Reset Password</a>
        <a href="<?php echo BASE_URL; ?>/pages/user_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user?');">Hapus</a>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
