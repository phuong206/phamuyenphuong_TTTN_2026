<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_mon = $_POST['id_mon'];
$id_don = $_POST['id_don'];

/* kiểm tra đã có chưa */
$stmt = $conn->prepare("
    SELECT * FROM chi_tiet_don_hang 
    WHERE id_don_hang = ? AND id_mon = ?
");
$stmt->execute([$id_don, $id_mon]);

if ($stmt->rowCount() > 0) {
    $stmt = $conn->prepare("
        UPDATE chi_tiet_don_hang 
        SET so_luong = so_luong + 1
        WHERE id_don_hang = ? AND id_mon = ?
    ");
    $stmt->execute([$id_don, $id_mon]);
} else {
    $stmt = $conn->prepare("
        INSERT INTO chi_tiet_don_hang (id_don_hang,id_mon,so_luong,trang_thai)
        VALUES (?,?,1,'chua_gui')
    ");
    $stmt->execute([$id_don, $id_mon]);
}

echo "OK";