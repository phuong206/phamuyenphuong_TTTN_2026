<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

/* ===== LẤY DỮ LIỆU ===== */

$id_ban = $_POST['id_ban'];

$so_khach = $_POST['so_khach'];

$loai = $_POST['loai']; // buffet | le

$goi = $_POST['goi_buffet'] ?? null;

$dat_truoc =
isset($_POST['dat_truoc']) ? 1 : 0;

$thoi_gian_den =
$_POST['thoi_gian_den'] ?? null;

/* ===== THÊM ===== */

$trang_mieng =
isset($_POST['trang_mieng']) ? 1 : 0;

/* ===== CHECK ĐƠN HIỆN TẠI ===== */

$stmt = $conn->prepare("
    SELECT * FROM don_hang 
    WHERE id_ban = ?
    AND trang_thai IN(
        'cho_xu_ly',
        'dat_truoc'
    )
");

$stmt->execute([$id_ban]);

if ($stmt->rowCount() > 0) {

    die("Bàn đã có khách rồi");

}

/* ===== XÁC ĐỊNH TRẠNG THÁI ===== */

if ($dat_truoc) {

    $trang_thai = 'dat_truoc';

} else {

    $trang_thai = 'cho_xu_ly';

}

/* ===== TÍNH TIỀN ===== */

$tong_tien = 0;

/* buffet */

if($loai == "buffet") {

    $gia_buffet = 0;

    /* ===== GÓI ===== */

    if($goi == 1) {

        $gia_buffet = 299000;

    }

    if($goi == 2) {

        $gia_buffet = 399000;

    }

    if($goi == 3) {

        $gia_buffet = 499000;

    }

    $tong_tien =
    $so_khach * $gia_buffet;

}

/* ===== TRÁNG MIỆNG ===== */

if($trang_mieng) {

    $tong_tien +=
    $so_khach * 49000;

}

/* ===== TẠO ĐƠN ===== */

$stmt = $conn->prepare("
    INSERT INTO don_hang 
    (
        id_ban,
        so_khach,
        loai,
        id_goi_buffet,
        tong_tien,
        trang_thai,
        thoi_gian_den,
        created_at
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->execute([

    $id_ban,
    $so_khach,
    $loai,
    $goi,
    $tong_tien,
    $trang_thai,
    $thoi_gian_den

]);

/* ===== UPDATE TRẠNG THÁI BÀN ===== */

if ($dat_truoc) {

    // bàn đặt trước

    $stmt = $conn->prepare("
        UPDATE ban
        SET trang_thai = 3
        WHERE id = ?
    ");

} else {

    // bàn có khách

    $stmt = $conn->prepare("
        UPDATE ban
        SET trang_thai = 2
        WHERE id = ?
    ");
}

$stmt->execute([$id_ban]);

/* ===== CHUYỂN TRANG ===== */
if ($dat_truoc) {
    header("Location: ../php/danh_sach_ban.php");
} else {
    header("Location: ../php/ban_dang_phuc_vu.php?id_ban=$id_ban");
}