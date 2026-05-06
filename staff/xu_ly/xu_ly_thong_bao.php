<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id = $_POST['id_tb'];

/* update trạng thái */
$conn->prepare("
UPDATE thong_bao_nhan_vien 
SET trang_thai = 'da_xu_ly'
WHERE id = ?
")->execute([$id]);

header("Location: ../php/thong_bao.php");