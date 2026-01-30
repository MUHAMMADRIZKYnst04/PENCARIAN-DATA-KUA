<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';

$keyword = trim($_GET['q'] ?? '');
$year_selected = isset($_GET['year']) ? (int)$_GET['year'] : 0;
if ($year_selected <= 0) {
    header('Location: ' . BASE_URL . '/pages/data_years.php');
    exit;
}

$sql = "SELECT * FROM nikah_data";
$where = [];
$params = [];
$types = '';

if ($keyword !== '') {
    $where[] = "(nama_suami LIKE ? OR nama_istri LIKE ? OR nomor_akta LIKE ?)";
    $kw = '%' . $keyword . '%';
    $params[] = $kw; $params[] = $kw; $params[] = $kw;
    $types .= 'sss';
}

$suami = trim($_GET['suami'] ?? '');
$istri = trim($_GET['istri'] ?? '');
if($suami!==''){ $where[] = "nama_suami LIKE ?"; $params[]='%'.$suami.'%'; $types.='s'; }
if($istri!==''){ $where[] = "nama_istri LIKE ?"; $params[]='%'.$istri.'%'; $types.='s'; }
$where[] = "YEAR(tgl_nikah) = ?";
$params[] = $year_selected;
$types .= 'i';

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id ASC";

$limit = 20;
$page = isset($_GET['page'])?(int)$_GET['page']:1;
if($page<1) $page=1;
$offset = ($page-1)*$limit;
// count total
$count_sql = "SELECT COUNT(*) as total FROM nikah_data";
$count_where = implode(" AND ", $where);
if($count_where){ $count_sql .= " WHERE $count_where"; }
$count_stmt = $conn->prepare($count_sql);
if($types!==''){ $count_stmt->bind_param($types,...$params); }
$count_stmt->execute();
$count_res=$count_stmt->get_result()->fetch_assoc();
$total_rows=$count_res['total'];
$total_pages=ceil($total_rows/$limit);
$sql .= " LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

include __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3 text-white">Data Pernikahan Tahun <?php echo $year_selected; ?></h1>

<form class="row row-cols-lg-auto g-3 align-items-center mb-3" method="get" action="">
  <input type="hidden" name="year" value="<?php echo $year_selected; ?>">
  <div class="col-12">
    <input type="text" name="q" value="<?php echo htmlspecialchars($keyword); ?>" class="form-control" placeholder="Cari nama / akta">
  </div>
  
<div class="col-12">
  <input type="text" name="suami" value="<?php echo htmlspecialchars($_GET['suami']??''); ?>" class="form-control" placeholder="Nama Suami">
</div>
<div class="col-12">
  <input type="text" name="istri" value="<?php echo htmlspecialchars($_GET['istri']??''); ?>" class="form-control" placeholder="Nama Istri">
</div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">Cari</button>
    <a href="<?php echo BASE_URL; ?>/pages/data_form.php?year=<?php echo $year_selected; ?>" class="btn btn-success">Tambah Data</a>
    <a href="<?php echo BASE_URL; ?>/pages/import.php?year=<?php echo $year_selected; ?>" class="btn btn-warning text-white">Import</a>
    <a href="<?php echo BASE_URL; ?>/pages/data_years.php" class="btn btn-secondary">Kembali Tahun</a>
    <a href="<?php echo BASE_URL; ?>/pages/export_excel.php?year=<?php echo $year_selected; ?>" class="btn btn-info text-white">Export Excel</a>
  </div>
</form>

<div class="table-responsive">
<table class="table table-striped table-hover align-middle table-sm bg-white rounded">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Nomor Akta</th>
      <th>Nama Suami</th>
      <th>Nama Istri</th>
      <th>Tgl Nikah</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php while($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo htmlspecialchars($row['nomor_akta']); ?></td>
      <td><?php echo htmlspecialchars($row['nama_suami']); ?></td>
      <td><?php echo htmlspecialchars($row['nama_istri']); ?></td>
      <td><?php echo htmlspecialchars($row['tgl_nikah']); ?></td>
      <td class="table-actions">
        <a href="<?php echo BASE_URL; ?>/pages/data_form.php?id=<?php echo $row['id']; ?>&year=<?php echo $year_selected; ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="<?php echo BASE_URL; ?>/pages/data_delete.php?id=<?php echo $row['id']; ?>&year=<?php echo $year_selected; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?');">Hapus</a>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</div>

<?php if($total_pages>1): ?>
<nav>
<ul class="pagination justify-content-center">
<?php for($p=1;$p<=$total_pages;$p++): ?>
<li class="page-item <?php echo $p==$page?'active':''; ?>"><a class="page-link" href="?year=<?php echo $year_selected; ?>&q=<?php echo urlencode($keyword); ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a></li>
<?php endfor; ?>
</ul>
</nav>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
