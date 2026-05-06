<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_ban = $_SESSION['id_ban'] ?? 1;
$gio = $_SESSION['gio_hang'] ?? [];

$tong = 0;

foreach ($gio as $id => $sl) {
    $m = $conn->query("SELECT gia FROM mon_an WHERE id=$id")->fetch();

    if(isset($_SESSION['buffet'])){
        $tong += 0;
    } else {
        $tong += $m['gia'] * $sl;
    }
}

// thêm buffet
if(isset($_SESSION['buffet'])){
    $tong += $_SESSION['buffet'] * 1000;
}

// thêm tráng miệng
if(isset($_SESSION['trang_mieng'])){
    $tong += 49000;
}

// tạo đơn
$conn->query("
INSERT INTO don_hang (id_ban, tong_tien, trang_thai)
VALUES ($id_ban, $tong, 'cho_xu_ly')
");

$id_don = $conn->lastInsertId();

// chi tiết đơn
foreach ($gio as $id => $sl) {
    $m = $conn->query("SELECT gia FROM mon_an WHERE id=$id")->fetch();

    $gia = isset($_SESSION['buffet']) ? 0 : $m['gia'];

    $conn->query("
    INSERT INTO chi_tiet_don_hang 
    (id_don_hang, id_mon, so_luong, gia)
    VALUES ($id_don, $id, $sl, $gia)
    ");
}

unset($_SESSION['gio_hang']);

header("Location: ../pages/trang_thai_don.php?success=1");