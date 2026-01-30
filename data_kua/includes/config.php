<?php
// config.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "data_kua"; // ganti sesuai DB kamu

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// ===== Auto-detect BASE_URL =====
if (!defined('BASE_URL')) {
    $__project_fs_path = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $__docroot_fs_path = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    if ($__docroot_fs_path && strpos($__project_fs_path, $__docroot_fs_path) === 0) {
        $__rel = substr($__project_fs_path, strlen($__docroot_fs_path));
        $__rel = '/' . ltrim($__rel, '/');
    } else {
        $__rel = '';
    }
    define('BASE_URL', rtrim($__rel, '/'));
}
?>
