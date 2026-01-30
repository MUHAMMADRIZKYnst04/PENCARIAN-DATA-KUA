<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';
if ($_SESSION['role'] !== 'admin') { die('Akses ditolak'); }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    // jangan hapus user yang sedang login sendiri? (optional)
    if ($id == $_SESSION['user_id']) {
        // skip hapus diri sendiri
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
header('Location: ' . BASE_URL . '/pages/users.php');
exit;
?>
