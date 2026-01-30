<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';
if ($_SESSION['role'] !== 'admin') { die('Akses ditolak'); }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    // password default
    $new_pass = 'user123';
    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password_hash=? WHERE id=?");
    $stmt->bind_param('si', $hash, $id);
    $stmt->execute();
}
header('Location: ' . BASE_URL . '/pages/users.php');
exit;
?>
