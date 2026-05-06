<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_ct = $_POST['id_ct'];
$trang_thai = $_POST['trang_thai'];

/*
trang_thai:
- chua_gui
- dang_nau
- da_phuc_vu
*/

$stmt = $conn->prepare("
    UPDATE chi_tiet_don_hang
    SET trang_thai = ?
    WHERE id = ?
");

$stmt->execute([$trang_thai, $id_ct]);

echo "OK";