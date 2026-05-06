<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_ban = $_POST['id_ban'];

/* hủy đơn */
$stmt = $conn->prepare("
    UPDATE don_hang 
    SET trang_thai = 'da_huy'
    WHERE id_ban = ? AND trang_thai = 'dat_truoc'
");
$stmt->execute([$id_ban]);

/* trả bàn */
$stmt = $conn->prepare("
    UPDATE ban SET trang_thai = 1 WHERE id = ?
");
$stmt->execute([$id_ban]);

echo "OK";