<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$year_selected = isset($_GET['year']) ? (int)$_GET['year'] : (isset($_POST['year'])?(int)$_POST['year']:0);

$nomor_akta = $nama_suami = $nama_istri = $tgl_nikah = "";
if ($year_selected > 0 && $tgl_nikah === '') {
    $tgl_nikah = $year_selected . '-01-01';
}

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM nikah_data WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $nomor_akta = $row['nomor_akta'];
        $nama_suami = $row['nama_suami'];
        $nama_istri = $row['nama_istri'];
        $tgl_nikah  = $row['tgl_nikah'];
        $year_selected = (int)date('Y', strtotime($tgl_nikah));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $year_selected = (int)($_POST['year'] ?? 0);
    $nomor_akta  = trim($_POST['nomor_akta'] ?? '');
    $nama_suami  = trim($_POST['nama_suami'] ?? '');
    $nama_istri  = trim($_POST['nama_istri'] ?? '');
    $tgl_nikah   = trim($_POST['tgl_nikah'] ?? '');

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE nikah_data SET nomor_akta=?, nama_suami=?, nama_istri=?, tgl_nikah=? WHERE id=?");
        $stmt->bind_param('ssssi', $nomor_akta, $nama_suami, $nama_istri, $tgl_nikah, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO nikah_data (nomor_akta, nama_suami, nama_istri, tgl_nikah) VALUES (?,?,?,?)");
        $stmt->bind_param('ssss', $nomor_akta, $nama_suami, $nama_istri, $tgl_nikah);
        $stmt->execute();
    }
    header('Location: ' . BASE_URL . '/pages/data_list.php?year=' . ($year_selected>0?$year_selected:date('Y',strtotime($tgl_nikah))));
    exit;
}

include __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3 text-white"><?php echo $id>0?'Edit':'Tambah'; ?> Data Pernikahan</h1>
<form method="post" action="">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="year" value="<?php echo $year_selected; ?>">
  <div class="mb-3">
    <label class="form-label text-white">Nomor Akta</label>
    <input type="text" name="nomor_akta" class="form-control" value="<?php echo htmlspecialchars($nomor_akta); ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label text-white">Nama Suami</label>
    <input type="text" name="nama_suami" class="form-control" value="<?php echo htmlspecialchars($nama_suami); ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label text-white">Nama Istri</label>
    <input type="text" name="nama_istri" class="form-control" value="<?php echo htmlspecialchars($nama_istri); ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label text-white">Tanggal Nikah</label>
    <input type="date" name="tgl_nikah" class="form-control" value="<?php echo htmlspecialchars($tgl_nikah); ?>" required>
  </div>
  <div class="d-grid gap-2 d-sm-flex justify-content-sm-start">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo BASE_URL; ?>/pages/data_list.php?year=<?php echo $year_selected; ?>" class="btn btn-secondary">Batal</a>
  </div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
