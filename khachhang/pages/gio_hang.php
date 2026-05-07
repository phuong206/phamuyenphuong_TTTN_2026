<?php

session_start();

require_once __DIR__ . "/../../config/db.php";

$db = new Database();

$conn = $db->connect();

/* =========================
   GIỎ HÀNG
========================= */

$gio =
    $_SESSION['gio_hang'] ?? [];

$total = 0;

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Giỏ hàng</title>

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

            color: #2b2b2b;

            min-height: 100vh;

            padding: 16px;
        }

        /* =========================
   HEADER
========================= */

        .header {

            margin-bottom: 22px;
        }

        .logo {

            font-size: 28px;

            font-weight: 700;

            color: #3a2e25;
        }

        .sub {

            margin-top: 4px;

            color: #8b7355;

            font-size: 14px;
        }

        /* =========================
   ITEM
========================= */

        .item {

            display: flex;

            gap: 14px;

            align-items: center;

            background:
                rgba(255, 255, 255, 0.82);

            backdrop-filter: blur(12px);

            padding: 14px;

            border-radius: 22px;

            margin-bottom: 16px;

            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.05);

            border:
                1px solid rgba(0, 0, 0, 0.04);
        }

        /* IMAGE */

        .item img {

            width: 90px;

            height: 90px;

            object-fit: cover;

            border-radius: 18px;

            flex-shrink: 0;
        }

        /* INFO */

        .info {

            flex: 1;
        }

        .info h4 {

            color: #3a2e25;

            margin-bottom: 8px;

            font-size: 18px;
        }

        .info p {

            color: #7c6755;

            margin-bottom: 4px;

            font-size: 14px;
        }

        .price {

            color: #8b6b4a !important;

            font-weight: 700;
        }

        /* REMOVE */

        .remove {

            width: 38px;

            height: 38px;

            border-radius: 12px;

            background:
                rgba(220, 38, 38, 0.08);

            display: flex;

            justify-content: center;

            align-items: center;

            text-decoration: none;

            font-size: 18px;
        }

        /* =========================
   SUMMARY
========================= */

        .summary {

            background:
                rgba(255, 255, 255, 0.82);

            backdrop-filter: blur(12px);

            border-radius: 24px;

            padding: 20px;

            margin-top: 20px;

            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.05);
        }

        .row {

            display: flex;

            justify-content: space-between;

            margin-bottom: 12px;

            color: #6b4f3b;
        }

        .total {

            margin-top: 15px;

            padding-top: 15px;

            border-top:
                1px dashed #d6c8ba;

            display: flex;

            justify-content: space-between;

            align-items: center;

            font-size: 22px;

            font-weight: 700;

            color: #3a2e25;
        }

        /* =========================
   BUTTON
========================= */

        .btn-group {

            display: flex;

            gap: 12px;

            margin-top: 25px;
        }

        .btn {

            flex: 1;

            padding: 16px;

            border-radius: 18px;

            text-decoration: none;

            text-align: center;

            font-weight: 700;

            font-size: 15px;

            transition: 0.25s;
        }

        /* BACK */

        .back {

            background: white;

            color: #6b4f3b;

            border:
                1px solid #d6c8ba;
        }

        /* ORDER */

        .order {

            background:
                linear-gradient(135deg,
                    #8b6b4a,
                    #6b4f3b);

            color: white;

            box-shadow:
                0 12px 30px rgba(107, 79, 59, 0.15);
        }

        .btn:hover {

            transform: translateY(-2px);
        }

        /* EMPTY */

        .empty {

            text-align: center;

            padding: 50px 20px;

            color: #8b7355;

            background: white;

            border-radius: 22px;

            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.05);
        }

        /* MOBILE */

        @media(max-width:600px) {

            body {

                padding: 14px;
            }

            .item {

                padding: 12px;

                border-radius: 18px;
            }

            .item img {

                width: 75px;

                height: 75px;
            }

            .info h4 {

                font-size: 16px;
            }

            .info p {

                font-size: 13px;
            }

            .btn-group {

                flex-direction: column;
            }

            .btn {

                width: 100%;
            }

        }
    </style>

</head>

<body>

    <!-- HEADER -->

    <div class="header">

        <div class="logo">

            🛒 Giỏ hàng

        </div>

        <div class="sub">

            Kiểm tra món trước khi đặt

        </div>

    </div>

    <?php

    $coMon = false;

    foreach ($gio as $id => $sl):

        if ($sl <= 0) {

            continue;
        }

        $sql = "
    SELECT *
    FROM mon_an
    WHERE id=?
    ";

        $stmt =
            $conn->prepare($sql);

        $stmt->execute([$id]);

        $m =
            $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$m) {

            continue;
        }

        $coMon = true;

        if (isset($_SESSION['buffet'])) {

            $tien = 0;
        } else {

            $tien =
                $m['gia'] * $sl;
        }

        $total += $tien;

    ?>

        <div class="item">

            <img
                src="../../images/<?= $m['hinh_anh'] ?>">

            <div class="info">

                <h4>

                    <?= $m['ten_mon'] ?>

                </h4>

                <p>

                    Số lượng:
                    <?= $sl ?>

                </p>

                <p class="price">

                    <?= number_format($tien) ?> đ

                </p>

            </div>

            <a
                class="remove"
                href="../actions/them_xoa_sua.php?action=xoa&id=<?= $id ?>">
                ❌
            </a>

        </div>

    <?php endforeach; ?>

    <!-- EMPTY -->

    <?php if (!$coMon) { ?>

        <div class="empty">

            🛒 Giỏ hàng đang trống

        </div>

    <?php } ?>

    <!-- SUMMARY -->

    <?php if ($coMon) { ?>

        <div class="summary">

            <?php if (isset($_SESSION['buffet'])): ?>

                <div class="row">

                    <span>Buffet</span>

                    <span>

                        <?= number_format(
                            $_SESSION['buffet'] * 1000
                        ) ?> đ

                    </span>

                </div>

            <?php

                $total +=
                    $_SESSION['buffet'] * 1000;

            endif;

            ?>

            <?php if (isset($_SESSION['trang_mieng'])): ?>

                <div class="row">

                    <span>Tráng miệng</span>

                    <span>49,000 đ</span>

                </div>

            <?php

                $total += 49000;

            endif;

            ?>

            <div class="total">

                <span>Tổng</span>

                <span>

                    <?= number_format($total) ?> đ

                </span>

            </div>

        </div>

    <?php } ?>

    <!-- BUTTON -->

    <div class="btn-group">

        <a
            href="menu.php"
            class="btn back">
            ⬅ Quay lại
        </a>

        <?php if ($coMon) { ?>

            <a
                href="../actions/dat_mon.php"
                class="btn order">
                Đặt món
            </a>

        <?php } ?>

    </div>

</body>

</html>