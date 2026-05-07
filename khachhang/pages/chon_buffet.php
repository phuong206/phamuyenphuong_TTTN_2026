<?php

session_start();

/* =========================
   LẤY ID BÀN
========================= */

if (isset($_GET['id_ban'])) {

    $_SESSION['id_ban']
        = $_GET['id_ban'];
}

$id_ban =
    $_SESSION['id_ban'] ?? 0;

if ($id_ban == 0) {

    die("Không tìm thấy bàn");
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Chọn Buffet</title>

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

            color: #2b2b2b;

            padding: 18px;
        }

        /* =========================
   HEADER
========================= */

        .header {

            margin-bottom: 25px;
        }

        .logo {

            font-size: 30px;

            font-weight: 700;

            color: #3a2e25;
        }

        .sub {

            margin-top: 5px;

            color: #8b7355;

            font-size: 14px;
        }

        /* =========================
   GRID
========================= */

        .grid {

            display: flex;

            flex-direction: column;

            gap: 18px;
        }

        /* =========================
   INPUT
========================= */

        input {

            display: none;
        }

        /* =========================
   CARD
========================= */

        .card {

            background:
                rgba(255, 255, 255, 0.88);

            backdrop-filter: blur(12px);

            border:
                2px solid transparent;

            border-radius: 24px;

            padding: 22px;

            cursor: pointer;

            transition: 0.25s;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.05);

            position: relative;
        }

        .card:hover {

            transform: translateY(-3px);
        }

        /* radio */

        .circle {

            position: absolute;

            top: 22px;

            right: 22px;

            width: 22px;

            height: 22px;

            border-radius: 50%;

            border: 2px solid #b8a38f;

            background: white;
        }

        input:checked+.card {

            border-color: #6b4f3b;

            box-shadow:
                0 15px 35px rgba(107, 79, 59, 0.15);
        }

        input:checked+.card .circle {

            background: #6b4f3b;

            border-color: #6b4f3b;

            box-shadow:
                0 0 0 4px rgba(107, 79, 59, 0.15);
        }

        /* =========================
   TEXT
========================= */

        .card h3 {

            font-size: 28px;

            margin-bottom: 10px;

            color: #3a2e25;
        }

        .price {

            font-size: 24px;

            font-weight: 700;

            margin-bottom: 18px;

            color: #6b4f3b;
        }

        .list {

            padding-left: 18px;

            color: #6b7280;

            line-height: 1.9;
        }

        /* =========================
   DESSERT
========================= */

        .extra {

            background:
                linear-gradient(135deg,
                    rgba(107, 79, 59, 0.08),
                    rgba(139, 107, 74, 0.08));
        }

        /* =========================
   BUTTON
========================= */

        .btn {

            width: 100%;

            margin-top: 28px;

            border: none;

            padding: 18px;

            border-radius: 20px;

            background:
                linear-gradient(135deg,
                    #8b6b4a,
                    #6b4f3b);

            color: white;

            font-size: 18px;

            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 15px 35px rgba(107, 79, 59, 0.18);
        }

        /* =========================
   MOBILE
========================= */

        @media(max-width:600px) {

            body {

                padding: 15px;
            }

            .logo {

                font-size: 25px;
            }

            .sub {

                font-size: 13px;
            }

            .card {

                padding: 18px;

                border-radius: 20px;
            }

            .card h3 {

                font-size: 24px;
            }

            .price {

                font-size: 20px;
            }

            .list {

                font-size: 14px;
            }

            .btn {

                padding: 16px;

                font-size: 16px;
            }

        }
    </style>

</head>

<body>

    <!-- HEADER -->

    <div class="header">

        <div class="logo">

            Customer_Buffet

        </div>

        <div class="sub">

            Chọn gói buffet - Bàn <?= $id_ban ?>

        </div>

    </div>

    <!-- FORM -->

    <form
        action="menu.php"
        method="GET">

        <input
            type="hidden"
            name="id_ban"
            value="<?= $id_ban ?>">

        <div class="grid">

            <!-- 299 -->

            <label>

                <input
                    type="radio"
                    name="buffet"
                    value="299"
                    required>

                <div class="card">

                    <div class="circle"></div>

                    <h3>

                        Gói tiêu chuẩn

                    </h3>

                    <div class="price">

                        299,000đ

                    </div>

                    <ul class="list">

                        <li>Heo + rau + viên</li>
                        <li>Giới hạn các món Dimsum</li>


                    </ul>

                </div>

            </label>

            <!-- 399 -->

            <label>

                <input
                    type="radio"
                    name="buffet"
                    value="399">

                <div class="card">

                    <div class="circle"></div>

                    <h3>

                        Gói cao cấp

                    </h3>

                    <div class="price">

                        399,000đ

                    </div>

                    <ul class="list">

                        <li>Heo + rau + viên</li>
                        <li>Bò Mỹ + hải sản</li>


                    </ul>

                </div>

            </label>

            <!-- 499 -->

            <label>

                <input
                    type="radio"
                    name="buffet"
                    value="499">

                <div class="card">

                    <div class="circle"></div>

                    <h3>

                        Gói VIP

                    </h3>

                    <div class="price">

                        499,000đ

                    </div>

                    <ul class="list">

                        <li>Full menu cao cấp</li>

                    </ul>

                </div>

            </label>

            <!-- TRÁNG MIỆNG -->

            <label>

                <input
                    type="checkbox"
                    name="trang_mieng"
                    value="1">

                <div class="card extra">

                    <div class="circle"></div>

                    <h3>

                        🍨 Tráng miệng

                    </h3>

                    <div class="price">

                        +49,000đ

                    </div>

                    <ul class="list">

                        <li>Kem + bánh + trái cây</li>

                    </ul>

                </div>

            </label>

        </div>

        <button class="btn">

            Xác nhận chọn gói

        </button>

    </form>

</body>

</html>