<?php

session_start();

require_once __DIR__ . "/../../config/db.php";

$db = new Database();

$conn = $db->connect();

$id_ban =
    $_SESSION['id_ban'] ?? 1;

/* =========================
   CHECK BUFFET
========================= */

$isBuffet =
    isset($_SESSION['buffet']);

$goi =
    $_SESSION['buffet'] ?? 0;

/* =========================
   LẤY ĐƠN
========================= */

$stmt = $conn->prepare("

SELECT *

FROM don_hang 

WHERE id_ban=?

AND trang_thai!='da_thanh_toan'

ORDER BY id DESC

LIMIT 1

");

$stmt->execute([$id_ban]);

$don =
    $stmt->fetch(PDO::FETCH_ASSOC);

$id_don =
    $don['id'] ?? 0;

/* =========================
   LẤY MÓN
========================= */

$stmt = $conn->prepare("

SELECT
    ct.*,
    m.ten_mon,
    m.goi_buffet

FROM chi_tiet_don_hang ct

JOIN mon_an m
ON m.id = ct.id_mon

WHERE ct.id_don_hang=?

");

$stmt->execute([$id_don]);

$ds =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   CHIA MÓN
========================= */

$mon_buffet = [];

$mon_le = [];

foreach ($ds as $m) {

    if ($isBuffet) {

        if ($m['goi_buffet'] <= $goi) {

            $mon_buffet[] = $m;
        } else {

            $mon_le[] = $m;
        }
    } else {

        $mon_le[] = $m;
    }
}

/* =========================
   TÍNH TẠM TÍNH
========================= */

$tong = 0;

/* buffet */

if ($isBuffet) {

    $so_khach =
        $don['so_khach'] ?? 1;

    $tong +=
        ($goi * 1000)
        * $so_khach;
}

/* món lẻ */

foreach ($mon_le as $m) {

    $tong +=
        $m['so_luong']
        *
        ($m['gia'] ?? 0);
}

/* tráng miệng */

if (isset($_SESSION['trang_mieng'])) {

    $tong +=
        49000
        *
        ($don['so_khach'] ?? 1);
}

/* VAT */

$vat =
    $tong * 0.08;

$tong += $vat;

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Trạng thái đơn</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {

            background:
                linear-gradient(180deg,
                    #f8f5f0,
                    #f1ece4,
                    #ebe4da);

            min-height: 100vh;

            padding: 16px;

            color: #2b2b2b;
        }

        .box {

            width: 100%;

            max-width: 550px;

            margin: auto;
        }

        /* =========================
   HEADER
========================= */

        .top {

            margin-bottom: 22px;
        }

        h2 {

            color: #3a2e25;

            margin-bottom: 5px;
        }

        .sub {

            color: #8b7355;

            font-size: 14px;
        }

        /* =========================
   SECTION
========================= */

        .section {

            margin-bottom: 20px;
        }

        .section-title {

            font-size: 16px;

            font-weight: 700;

            margin-bottom: 12px;

            color: #6b4f3b;
        }

        /* =========================
   ITEM
========================= */

        .item {

            background:
                rgba(255, 255, 255, 0.85);

            backdrop-filter: blur(10px);

            border-radius: 20px;

            padding: 15px;

            margin-bottom: 12px;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .row {

            display: flex;

            justify-content: space-between;

            align-items: center;
        }

        .name {

            font-weight: 700;

            color: #3a2e25;
        }

        .qty {

            color: #8b7355;

            font-size: 13px;

            margin-top: 5px;
        }

        .tag {

            padding: 6px 12px;

            border-radius: 999px;

            font-size: 12px;

            font-weight: 700;
        }

        /* buffet */

        .buffet {

            background:
                rgba(34, 197, 94, 0.12);

            color: #15803d;
        }

        /* món lẻ */

        .extra {

            background:
                rgba(245, 158, 11, 0.12);

            color: #b45309;
        }

        /* =========================
   STATUS BAR
========================= */

        .bar {

            height: 7px;

            background: #ede7df;

            border-radius: 999px;

            overflow: hidden;

            margin-top: 12px;
        }

        .fill {

            height: 100%;

            border-radius: 999px;

            background:
                linear-gradient(90deg,
                    #8b6b4a,
                    #6b4f3b);
        }

        .status {

            margin-top: 7px;

            text-align: right;

            font-size: 12px;

            color: #8b7355;
        }

        /* =========================
   SUMMARY
========================= */

        .summary {

            background:
                rgba(255, 255, 255, 0.85);

            backdrop-filter: blur(10px);

            border-radius: 24px;

            padding: 20px;

            margin-top: 25px;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .total {

            display: flex;

            justify-content: space-between;

            align-items: center;

            font-size: 20px;

            font-weight: 700;

            color: #3a2e25;
        }

        .note {

            margin-top: 8px;

            font-size: 13px;

            color: #8b7355;
        }

        /* =========================
   BUTTON
========================= */

        .btn-group {

            display: flex;

            gap: 12px;

            margin-top: 22px;
        }

        .btn {

            flex: 1;

            padding: 16px;

            border: none;

            border-radius: 18px;

            cursor: pointer;

            font-weight: 700;

            transition: 0.25s;
        }

        .outline {

            background: white;

            border:
                1px solid #d6c8ba;

            color: #6b4f3b;
        }

        .primary {

            background:
                linear-gradient(135deg,
                    #8b6b4a,
                    #6b4f3b);

            color: white;
        }

        .btn:hover {

            transform: translateY(-2px);
        }

        /* mobile */

        @media(max-width:600px) {

            .btn-group {

                flex-direction: column;
            }

        }
    </style>

</head>

<body>

    <div class="box">

        <!-- HEADER -->

        <div class="top">

            <h2>

                📱 Trạng thái đơn

            </h2>

            <div class="sub">

                Bàn <?= $id_ban ?>

            </div>

        </div>

        <!-- MÓN BUFFET -->

        <?php if (count($mon_buffet) > 0) { ?>

            <div class="section">

                <div class="section-title">

                    🍱 Món trong buffet

                </div>

                <?php foreach ($mon_buffet as $m): ?>

                    <?php

                    $tt =
                        $m['trang_thai'] ?? 'cho';

                    $percent = 25;

                    $text = "Đang chờ";

                    if ($tt == 'dang_nau') {

                        $percent = 60;

                        $text = "Đang nấu";
                    }

                    if ($tt == 'da_phuc_vu') {

                        $percent = 100;

                        $text = "Đã hoàn thành";
                    }

                    ?>

                    <div class="item">

                        <div class="row">

                            <div>

                                <div class="name">

                                    <?= $m['ten_mon'] ?>

                                </div>

                                <div class="qty">

                                    SL:
                                    <?= $m['so_luong'] ?>

                                </div>

                            </div>

                            <div class="tag buffet">

                                Buffet

                            </div>

                        </div>

                        <div class="bar">

                            <div
                                class="fill"
                                style="width:<?= $percent ?>%"></div>

                        </div>

                        <div class="status">

                            <?= $text ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php } ?>

        <!-- MÓN LẺ -->

        <?php if (count($mon_le) > 0) { ?>

            <div class="section">

                <div class="section-title">

                    🍜 Món gọi thêm

                </div>

                <?php foreach ($mon_le as $m): ?>

                    <?php

                    $tt =
                        $m['trang_thai'] ?? 'cho';

                    $percent = 25;

                    $text = "Đang chờ";

                    if ($tt == 'dang_nau') {

                        $percent = 60;

                        $text = "Đang nấu";
                    }

                    if ($tt == 'da_phuc_vu') {

                        $percent = 100;

                        $text = "Đã phục vụ";
                    }

                    ?>

                    <div class="item">

                        <div class="row">

                            <div>

                                <div class="name">

                                    <?= $m['ten_mon'] ?>

                                </div>

                                <div class="qty">

                                    SL:
                                    <?= $m['so_luong'] ?>

                                </div>

                            </div>

                            <div class="tag extra">

                                Món lẻ

                            </div>

                        </div>

                        <div class="bar">

                            <div
                                class="fill"
                                style="width:<?= $percent ?>%"></div>

                        </div>

                        <div class="status">

                            <?= $text ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php } ?>

        <!-- SUMMARY -->

        <!-- TẠM TÍNH -->

        <div class="summary">

            <?php if ($isBuffet) { ?>

                <div class="item">

                    <div class="row">

                        <div>

                            <div class="name">

                                🍱 Buffet <?= $goi ?>K

                            </div>

                            <div class="qty">

                                <?= $don['so_khach'] ?? 1 ?>
                                người

                            </div>

                        </div>

                        <div class="tag buffet">

                            <?= number_format(
                                ($goi * 1000)
                                    *
                                    ($don['so_khach'] ?? 1)
                            ) ?>đ

                        </div>

                    </div>

                </div>

            <?php } ?>

            <!-- món lẻ -->

            <?php foreach ($mon_le as $m) { ?>

                <div class="item">

                    <div class="row">

                        <div>

                            <div class="name">

                                <?= $m['ten_mon'] ?>

                            </div>

                            <div class="qty">

                                SL:
                                <?= $m['so_luong'] ?>

                            </div>

                        </div>

                        <div class="tag extra">

                            <?= number_format(
                                $m['gia']
                                    *
                                    $m['so_luong']
                            ) ?>đ

                        </div>

                    </div>

                </div>

            <?php } ?>

            <!-- tráng miệng -->

            <?php if (isset($_SESSION['trang_mieng'])) { ?>

                <div class="item">

                    <div class="row">

                        <div>

                            <div class="name">

                                🍨 Buffet tráng miệng

                            </div>

                        </div>

                        <div class="tag extra">

                            <?= number_format(
                                49000
                                    *
                                    ($don['so_khach'] ?? 1)
                            ) ?>đ

                        </div>

                    </div>

                </div>

            <?php } ?>

            <!-- VAT -->

            <div class="item">

                <div class="row">

                    <div>

                        <div class="name">

                            VAT 8%

                        </div>

                    </div>

                    <div class="tag extra">

                        <?= number_format($vat) ?>đ

                    </div>

                </div>

            </div>

            <!-- TOTAL -->

            <div class="summary">

                <div class="total">

                    <span>Tạm tính</span>

                    <span>

                        <?= number_format($tong) ?>đ

                    </span>

                </div>

                <div class="note">

                    Giá tạm tính chưa bao gồm ưu đãi tại quầy

                </div>

            </div>

            <!-- BUTTON -->

            <div class="btn-group">

                <button
                    class="btn outline"
                    onclick="
            location.href=
            'menu.php?id_ban=<?= $id_ban ?>'
            ">

                    Thêm món

                </button>

                

            </div>

        </div>

</body>

</html>