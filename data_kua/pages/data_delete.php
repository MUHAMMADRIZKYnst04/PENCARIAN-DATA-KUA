<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$year_selected = isset($_GET['year']) ? (int)$_GET['year'] : 0;
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM nikah_data WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: ' . BASE_URL . '/pages/data_list.php?year=' . $year_selected);
exit;
?>
