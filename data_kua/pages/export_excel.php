<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';
$year = isset($_GET['year'])?(int)$_GET['year']:0;
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_nikah_$year.xls");
echo "ID\tNomor Akta\tNama Suami\tNama Istri\tTanggal Nikah\n";
$sql = "SELECT * FROM nikah_data WHERE YEAR(tgl_nikah)=? ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i',$year);
$stmt->execute();
$res=$stmt->get_result();
while($row=$res->fetch_assoc()){
  echo $row['id']."\t".$row['nomor_akta']."\t".$row['nama_suami']."\t".$row['nama_istri']."\t".$row['tgl_nikah']."\n";
}
?>
