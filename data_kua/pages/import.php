<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';

$year_selected = isset($_GET['year']) ? (int)$_GET['year'] : 0;
$msg = "";
$err = "";
$import_counts = []; // year => count

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['import_file']['tmp_name'];
        $name = $_FILES['import_file']['name'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (!in_array($ext, ['csv'])) {
            $err = "Format file harus CSV.";
        } else {
            $handle = fopen($tmp, 'r');
            if ($handle) {
                $header = fgetcsv($handle);
                $count = 0;
                $stmt = $conn->prepare("INSERT INTO nikah_data (nomor_akta, nama_suami, nama_istri, tgl_nikah) VALUES (?,?,?,?)");
                while(($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 4) continue;
                    $nomor_akta = trim($row[0]);
                    $nama_suami = trim($row[1]);
                    $nama_istri = trim($row[2]);
                    $tgl_nikah  = trim($row[3]);
                    // jika user pilih tahun spesifik & tanggal tidak di tahun itu, paksa
                    if ($year_selected > 0) {
                        // gunakan tanggal import tapi ganti tahun?
                        // Untuk sekarang: skip jika bukan tahun yg dipilih
                        $y = (int)date('Y', strtotime($tgl_nikah));
                        if ($y != $year_selected) continue;
                    }
                    $stmt->bind_param('ssss', $nomor_akta, $nama_suami, $nama_istri, $tgl_nikah);
                    $stmt->execute();
                    $count++;
                    $y = (int)date('Y', strtotime($tgl_nikah));
                    $import_counts[$y] = ($import_counts[$y] ?? 0) + 1;
                }
                fclose($handle);
                $msg = "Import berhasil: $count baris.";
            } else {
                $err = "Tidak bisa membaca file.";
            }
        }
    } else {
        $err = "Upload gagal.";
    }
}

include __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3 text-white">Import Data CSV <?php echo $year_selected>0?('Tahun '.$year_selected):''; ?></h1>
<?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
<?php if($msg): ?><div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

<div class="import-dropzone mb-3">
  <p>Tarik & letakkan file CSV ke sini atau klik untuk pilih file.</p>
  <small>Format kolom: nomor_akta,nama_suami,nama_istri,tgl_nikah (YYYY-MM-DD)</small>
  <?php if($year_selected>0): ?><br><small>Hanya baris tahun <?php echo $year_selected; ?> yang akan diimpor.</small><?php endif; ?>
</div>
<form method="post" action="" enctype="multipart/form-data">
  <input type="file" id="importFile" name="import_file" accept=".csv" hidden>
  <button type="submit" class="btn btn-primary">Import</button>
  <a href="<?php echo BASE_URL; ?>/pages/data_list.php?year=<?php echo $year_selected; ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php if(!empty($import_counts)): ?>
<hr>
<h5 class="text-white">Ringkasan Import:</h5>
<ul class="text-white">
<?php foreach($import_counts as $y=>$c): ?>
  <li><?php echo $y; ?> : <?php echo $c; ?> baris</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
