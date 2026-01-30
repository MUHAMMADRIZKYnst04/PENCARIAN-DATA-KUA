<?php
require_once __DIR__ . '/../includes/auth.php';
$counts = [];
$res = $conn->query("SELECT YEAR(tgl_nikah) as y, COUNT(*) as c FROM nikah_data GROUP BY y");
while($r = $res->fetch_assoc()){ $counts[$r['y']] = $r['c']; }

include __DIR__ . '/../includes/header.php';
$start_year = 2010;
$end_year = 2026;
?>
<h1 class="mb-4 text-center text-white">Pilih Tahun Pernikahan</h1>
<div class="row g-4 justify-content-center">
<?php for($y=$start_year;$y<=$end_year;$y++): ?>
  <div class="col-6 col-md-4 col-lg-3">
    <a href="<?php echo BASE_URL; ?>/pages/data_list.php?year=<?php echo $y; ?>" class="text-decoration-none">
      <div class="card shadow-sm text-center border-0 h-100 year-card">
        <div class="card-body d-flex flex-column justify-content-center" style="min-height:120px;">
          <h3 class="fw-bold mb-0"><?php echo $y; ?></h3>
          <small class='text-muted'><?php echo isset($counts[$y])?$counts[$y]:0; ?> data</small>
        </div>
      </div>
    </a>
  </div>
<?php endfor; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
