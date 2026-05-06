<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_ban = $_POST['id_ban'];

/* chuyển đơn */
$stmt = $conn->prepare("
    UPDATE don_hang 
    SET trang_thai = 'cho_xu_ly'
    WHERE id_ban = ? AND trang_thai = 'dat_truoc'
");
$stmt->execute([$id_ban]);

/* update bàn */
$stmt = $conn->prepare("
    UPDATE ban SET trang_thai = 2 WHERE id = ?
");
$stmt->execute([$id_ban]);

echo "OK";