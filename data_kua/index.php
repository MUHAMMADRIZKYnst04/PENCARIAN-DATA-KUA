<?php
session_start();
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';
?>
<div class="mb-4">
  <div class="blur-box text-center">
    <h1 class="display-5 fw-bold">Selamat Datang di Pengelolan Data KUA</h1>
    <p class="fs-4">Aplikasi sederhana untuk manajemen data KUA. Silakan login untuk mulai mengelola data.</p>
    <?php if(!isset($_SESSION['user_id'])): ?>
    <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-primary btn-lg mt-2">Login</a>
    <?php else: ?>
    <a href="<?php echo BASE_URL; ?>/pages/data_years.php" class="btn btn-success btn-lg mt-2">Kelola Data</a>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
