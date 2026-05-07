<?php
require_once __DIR__ . "/../../config/db.php";
$db = new Database();
$conn = $db->connect();

$id_don = $_GET['id_don'] ?? 0;
if (!$id_don) die("Thiếu ID đơn");

/* lấy đơn */
$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id=?");
$stmt->execute([$id_don]);
$don = $stmt->fetch(PDO::FETCH_ASSOC);

$id_ban = $don['id_ban'];
$so_khach = $don['so_khach'] ?? 1;
$loai = $don['loai'];
$goi = $don['id_goi_buffet'] ?? 0;
$trang_mieng = $don['trang_mieng'] ?? 0;

/* giá gói */
$gia_goi = [1 => 299000, 2 => 399000, 3 => 499000];

/* lấy món */
$stmt = $conn->prepare("
SELECT ct.so_luong, m.ten_mon, m.gia, m.goi_buffet
FROM chi_tiet_don_hang ct
JOIN mon_an m ON m.id = ct.id_mon
WHERE ct.id_don_hang = ?
");
$stmt->execute([$id_don]);
$ds = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* tính tiền */
$tong = 0;

if ($loai == "buffet") {
    $tong = ($gia_goi[$goi] ?? 0) * $so_khach;

    foreach ($ds as $m) {
        if ($m['goi_buffet'] > $goi) {

            $tong +=
                $m['so_luong']
                *
                $m['gia'];
        }
    }

    if ($trang_mieng == 1) {
        $tong += 49000 * $so_khach;
    }
} else {
    foreach ($ds as $m) {
        $tong += $m['so_luong'] * $m['gia'];
    }

    if ($trang_mieng == 1) {
        $tong += 49000 * $so_khach;
    }
}

$vat = round($tong * 0.08);
$tong_cong = $tong + $vat;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            background: #0f172a;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            background: #1e293b;
            padding: 25px;
            border-radius: 16px;
            width: 400px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .total {
            margin-top: 15px;
            border-top: 1px solid #334155;
            padding-top: 10px;
        }

        button {
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
        }
    </style>
</head>

<body>

    <div class="box">

        <h2>Hóa đơn</h2>

        <?php foreach ($ds as $m): ?>

            <?php

            $thanh_tien = 0;

            /* món lẻ */

            if ($loai == "le") {

                $thanh_tien =
                    $m['so_luong']
                    *
                    $m['gia'];
            }

            /* buffet */ else {

                /* ngoài gói */

                if ($m['goi_buffet'] > $goi) {

                    $thanh_tien =
                        $m['so_luong']
                        *
                        $m['gia'];
                }
            }

            ?>

            <div class="item">

                <span>

                    <?= $m['ten_mon'] ?>
                    x<?= $m['so_luong'] ?>

                </span>

                <span>

                    <?= number_format($thanh_tien) ?>đ

                </span>

            </div>

        <?php endforeach; ?>

        <div class="total">
            <div class="item">
                <span>Tạm tính</span>
                <span><?= number_format($tong) ?>đ</span>
            </div>

            <div class="item">
                <span>VAT</span>
                <span><?= number_format($vat) ?>đ</span>
            </div>

            <div class="item">
                <b>Tổng</b>
                <b><?= number_format($tong_cong) ?>đ</b>
            </div>
        </div>

        <button onclick="location.href='xac_nhan.php?id_ban=<?= $id_ban ?>&id_don=<?= $id_don ?>&tong=<?= $tong_cong ?>'">
            Xác nhận thanh toán
        </button>

    </div>
</body>

</html>