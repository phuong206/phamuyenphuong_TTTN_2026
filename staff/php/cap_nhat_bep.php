<?php

require_once __DIR__ . "/../../config/db.php";

$db = new Database();

$conn = $db->connect();

$id = $_POST['id'] ?? 0;

/* =========================
   HOÀN THÀNH MÓN
========================= */

$sql = "
UPDATE chi_tiet_don_hang

SET trang_thai='da_phuc_vu'

WHERE id=?
";

$stmt = $conn->prepare($sql);

$stmt->execute([$id]);

header("Location: bep.php");

exit();