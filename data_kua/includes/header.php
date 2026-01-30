<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/config.php';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nikah Web</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo BASE_URL; ?>/assets/css/custom.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>/index.php">KUA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if(isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/pages/data_years.php">Kelola Data</a></li>
        <?php if(isset($_SESSION['role']) && $_SESSION['role']==='admin'): ?>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/pages/users.php">Manajemen Akun</a></li>
        <?php endif; ?>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <button id="themeToggle" class="btn btn-sm btn-outline-light ms-2">🌓</button>
      </li>
        <?php if(isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><span class="navbar-text me-2"><?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/logout.php">Logout</a></li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
