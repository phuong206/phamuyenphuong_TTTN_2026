<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_don = $_POST['id_don'];

$stmt = $conn->prepare("
    UPDATE chi_tiet_don_hang
    SET trang_thai = 'dang_nau'
    WHERE id_don_hang = ? AND trang_thai = 'chua_gui'
");
$stmt->execute([$id_don]);

echo "OK";