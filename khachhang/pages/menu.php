<?php

session_start();

require_once __DIR__ . "/../../config/db.php";

$db = new Database();

$conn = $db->connect();

/* =========================
   FIX MÓN LẺ
========================= */

// if (isset($_GET['mon_le'])) {

//     unset($_SESSION['buffet']);
//     unset($_SESSION['trang_mieng']);
//     unset($_SESSION['lau']);
// }

/* =========================
   LẤY ID BÀN
========================= */

if (isset($_GET['id_ban'])) {

    $_SESSION['id_ban'] = $_GET['id_ban'];
}

$id_ban = $_SESSION['id_ban'] ?? 0;

if ($id_ban == 0) {

    die("Không tìm thấy bàn");
}

/* =========================
   NHẬN DỮ LIỆU
========================= */

if (isset($_GET['buffet'])) {

    $_SESSION['buffet'] = $_GET['buffet'];
}

if (isset($_GET['trang_mieng'])) {

    $_SESSION['trang_mieng'] = $_GET['trang_mieng'];
}

if (isset($_GET['lau'])) {

    $_SESSION['lau'] = $_GET['lau'];
}

/* =========================
   LOGIC
========================= */

$isBuffet = isset($_SESSION['buffet']);
$id_loai =
    $_GET['id_loai'] ?? 0;

/* =========================
   LẤY DANH MỤC
========================= */

$sqlLoai = "
SELECT *
FROM loai_mon
ORDER BY id ASC
";

$dsLoai =
    $conn
    ->query($sqlLoai)
    ->fetchAll(PDO::FETCH_ASSOC);
$goi = $_SESSION['buffet'] ?? 0;

$coTrangMieng = isset($_SESSION['trang_mieng']);
/* ========================= 
        MODE MÓN LẺ 
========================= */
$isMonLe = isset($_GET['mon_le']);
$where = "
WHERE trang_thai = 1
";

/* =========================
   FILTER LOẠI
========================= */

if ($id_loai != 0) {

    $where .= "
    AND id_loai = $id_loai
    ";
}

/* =========================
   BUFFET
========================= */
if ($isBuffet && !$isMonLe) {

    $where .= "
    AND (
        goi_buffet <= $goi
        AND goi_buffet > 0
    )
    ";

    /* buffet thường không hiện nước */

    if (!$coTrangMieng) {

        $where .= "
        AND id_loai != 10
        ";
    }
}

/* =========================
   QUERY
========================= */

$sql = "
SELECT *
FROM mon_an
$where
ORDER BY id_loai ASC
";

$menu =
    $conn
    ->query($sql)
    ->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta
        name="apple-mobile-web-app-capable"
        content="yes">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/menu.css">
    <title>Menu</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {

            background: #f8fafc;

            color: #0f172a;
        }

        /* =========================
                    HEADER
        ========================= */

        .header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 20px;
        }

        .logo {

            font-size: 30px;

            font-weight: 700;
        }

        .sub {

            margin-top: 5px;

            color: #64748b;

            font-size: 14px;
        }

        .cart-btn {

            width: 50px;

            height: 50px;

            border-radius: 14px;

            background: #0f172a;

            color: white;

            display: flex;

            justify-content: center;

            align-items: center;

            text-decoration: none;

            font-size: 22px;
        }

        /* =========================
   CATEGORY
========================= */

        .category-wrap {

            display: flex;

            gap: 10px;

            overflow-x: auto;

            padding: 0 20px 15px;

            scrollbar-width: none;
        }

        .category-wrap::-webkit-scrollbar {

            display: none;
        }

        .cat {

            min-width: max-content;

            border: none;

            background: white;

            padding: 12px 18px;

            border-radius: 12px;

            border: 1px solid #cbd5e1;

            cursor: pointer;

            font-weight: 600;
        }

        .cat.active {

            background: #0f172a;

            color: white;
        }

        /* =========================
   MENU
========================= */

        .menu-list {

            padding: 0 20px 100px;
        }

        .food-card {

            background: white;

            border: 1px solid #cbd5e1;

            border-radius: 18px;

            padding: 12px;

            display: flex;

            align-items: center;

            gap: 15px;

            margin-bottom: 15px;
        }

        .food-card img {

            width: 90px;

            height: 90px;

            flex-shrink: 0;

            object-fit: cover;

            border-radius: 14px;

            background: #e2e8f0;
        }

        .food-info {

            flex: 1;
        }

        .food-info h3 {

            font-size: 18px;

            margin-bottom: 8px;
        }

        .price {

            color: #64748b;

            font-size: 16px;
        }

        .right-box {

            display: flex;

            align-items: center;
        }

        /* =========================
   QTY
========================= */

        .qty-box {

            display: flex;

            align-items: center;

            gap: 8px;
        }

        .qty-box button {

            width: 32px;

            height: 32px;

            border: none;

            border-radius: 10px;

            background: #0f172a;

            color: white;

            font-size: 18px;

            cursor: pointer;
            min-width: 32px;

            min-height: 32px;
        }

        .qty-box input {

            width: 40px;

            height: 32px;

            text-align: center;

            border: 1px solid #cbd5e1;

            border-radius: 8px;
        }

        /* =========================
   SUBMIT
========================= */

        .submit-btn {

            position: fixed;

            bottom: 20px;

            left: 20px;

            right: 20px;

            border: none;

            background: #0f172a;

            color: white;

            padding: 16px;

            border-radius: 18px;

            font-size: 18px;

            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* =========================
   BUFFET CARD
========================= */

        .buffet-box {

            background: white;

            margin: 20px;

            padding: 20px;

            border-radius: 18px;

            border: 2px dashed #38bdf8;
        }

        .buffet-box h3 {

            margin-bottom: 10px;
        }

        .buffet-btn {

            display: inline-block;

            margin-top: 10px;

            padding: 12px 16px;

            background: #0f172a;

            color: white;

            text-decoration: none;

            border-radius: 12px;
        }

        @media(max-width:600px) {

            .header {

                padding: 15px;
            }

            .logo {

                font-size: 24px;
            }

            .sub {

                font-size: 12px;
            }

            .cart-btn {

                width: 45px;

                height: 45px;

                font-size: 20px;
            }

            .category-wrap {

                padding: 0 15px 12px;

                gap: 8px;
            }

            .cat {

                padding: 10px 14px;

                font-size: 13px;
            }

            .menu-list {

                padding: 0 15px 100px;
            }

            .food-card {

                padding: 10px;

                gap: 10px;

                border-radius: 15px;
            }

            .food-card img {

                width: 75px;

                height: 75px;
            }

            .food-info h3 {

                font-size: 15px;

                margin-bottom: 5px;
            }

            .price {

                font-size: 13px;
            }

            .qty-box {

                gap: 5px;
            }

            .qty-box button {

                width: 28px;

                height: 28px;

                font-size: 16px;
            }

            .qty-box input {

                width: 34px;

                height: 28px;

                font-size: 13px;
            }

            .submit-btn {

                left: 15px;

                right: 15px;

                bottom: 15px;

                padding: 14px;

                font-size: 16px;

                border-radius: 15px;
            }

            .buffet-box {

                margin: 15px;

                padding: 15px;
            }

        }

        body {

            overflow-x: hidden;
        }

        /* =========================
   ✨ LUXURY UI
========================= */

        body {

            background:
                linear-gradient(180deg,
                    #f8f5f0,
                    #f1ece4,
                    #ebe4da);

            color: #2b2b2b;
        }

        /* HEADER */

        .header {

            background:
                rgba(255, 255, 255, 0.75);

            backdrop-filter: blur(12px);

            border-bottom:
                1px solid rgba(0, 0, 0, 0.06);

            position: sticky;

            top: 0;

            z-index: 999;
        }

        .title,
        .logo {

            color: #3a2e25;

            font-weight: 700;
        }

        .sub {

            color: #8b7355;
        }

        /* BUTTON */

        .btn,
        button[type="submit"] {

            background:
                linear-gradient(135deg,
                    #6b4f3b,
                    #8b6b4a);

            color: white;

            box-shadow:
                0 8px 20px rgba(107, 79, 59, 0.18);

            transition: 0.25s;
        }

        .btn:hover,
        button[type="submit"]:hover {

            transform: translateY(-2px);

            opacity: 0.95;
        }

        /* CATEGORY */

        .cat,
        .category-wrap button {

            background: white;

            color: #5c4633;

            border:
                1px solid #d8cbbd;

            transition: 0.25s;
        }

        .cat.active,
        .category-wrap button.active {

            background:
                linear-gradient(135deg,
                    #8b6b4a,
                    #6b4f3b);

            color: white;

            border: none;

            box-shadow:
                0 8px 20px rgba(107, 79, 59, 0.15);
        }

        /* CARD */

        .card,
        .food-card {

            background:
                rgba(255, 255, 255, 0.85);

            border:
                1px solid rgba(0, 0, 0, 0.05);

            backdrop-filter: blur(10px);

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.06);

            transition: 0.25s;
        }

        .card:hover,
        .food-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 14px 35px rgba(107, 79, 59, 0.12);
        }

        /* TEXT */

        .card h3,
        .food-info h3 {

            color: #3a2e25;
        }

        .card p,
        .price {

            color: #8b6b4a;

            font-weight: 600;
        }

        /* IMAGE */

        .card img,
        .food-card img {

            border-radius: 18px;

            border:
                1px solid rgba(0, 0, 0, 0.04);
        }

        /* QTY */

        .qty button,
        .qty-box button {

            background:
                linear-gradient(135deg,
                    #8b6b4a,
                    #6b4f3b);

            color: white;

            box-shadow:
                0 5px 15px rgba(107, 79, 59, 0.15);
        }

        .qty input,
        .qty-box input {

            background: #f8f5f0;

            color: #3a2e25;

            border:
                1px solid #d6c8ba;
        }

        /* BUFFET */

        .buffet-box {

            background:
                linear-gradient(135deg,
                    rgba(139, 107, 74, 0.08),
                    rgba(107, 79, 59, 0.08));

            border:
                1px solid rgba(107, 79, 59, 0.12);

            backdrop-filter: blur(10px);
        }

        .buffet-box h3 {

            color: #6b4f3b;
        }

        .buffet-btn {

            background:
                linear-gradient(135deg,
                    #8b6b4a,
                    #6b4f3b);

            color: white;
        }

        /* SCROLL */

        ::-webkit-scrollbar {

            height: 6px;

            width: 6px;
        }

        ::-webkit-scrollbar-thumb {

            background: #8b6b4a;

            border-radius: 999px;
        }

        .bell-btn {

            width: 52px;

            height: 52px;

            border: none;

            border-radius: 16px;

            background:
                linear-gradient(135deg,
                    #c6a27e,
                    #a67c52);

            color: white;

            font-size: 22px;

            cursor: pointer;

            box-shadow:
                0 10px 25px rgba(166, 124, 82, 0.2);

            transition: 0.25s;
        }

        .bell-btn:hover {

            transform: translateY(-2px);
        }

        .bell-btn,
        .cart-btn {

            width: 52px;
            height: 52px;

            display: flex;

            justify-content: center;
            align-items: center;

            border-radius: 18px;

            font-size: 24px;

            text-decoration: none;

        }
    </style>

</head>

<body>

    <!-- HEADER -->

    <div class="header">

        <div>

            <div class="logo">

                Customer_Home

            </div>

            <div class="sub">

                Menu - Bàn <?= $id_ban ?>

            </div>

        </div>

        <div
            style="display:flex;gap:10px;">

            <!-- CHUÔNG -->

            <button
                class="bell-btn"
                onclick="goiNhanVien()">
                🔔
            </button>

            <!-- GIỎ HÀNG -->

            <a
                href="gio_hang.php?id_ban=<?= $id_ban ?>"
                class="cart-btn">
                🛒
            </a>

        </div>

    </div>

    <!-- CATEGORY -->

    <div class="category-wrap">

        <a
            href="?id_ban=<?= $id_ban ?>"
            class="
        cat
        <?= $id_loai == 0 ? 'active' : '' ?>
        "
            style="text-decoration:none;">
            Tất cả
        </a>

        <?php foreach ($dsLoai as $loai) { ?>

            <a
                href="
            ?id_ban=<?= $id_ban ?>
            &id_loai=<?= $loai['id'] ?>
            "
                class="
            cat
            <?= $id_loai == $loai['id'] ? 'active' : '' ?>
            "
                style="text-decoration:none;">

                <?= $loai['ten_loai'] ?>

            </a>

        <?php } ?>

        <a
            href="?mon_le=1&id_ban=<?= $id_ban ?>"
            class="cat"
            style="
            text-decoration:none;
            display:flex;
            align-items:center;
        ">
            🍽 Món lẻ
        </a>

        <a
            href="chon_buffet.php?id_ban=<?= $id_ban ?>"
            class="cat"
            style="text-decoration:none;display:flex;align-items:center;">
            🍱 Buffet
        </a>

    </div>

    <!-- MENU -->

    <form
        action="
../actions/them_xoa_sua.php
?action=them&id_ban=<?= $id_ban ?>
"
        method="POST">

        <div class="menu-list">

            <?php foreach ($menu as $m): ?>

                <?php

                $img = !empty($m['hinh_anh'])

                    ? "../../images/" . $m['hinh_anh']

                    : "../../images/default.jpg";

                ?>

                <div class="food-card">

                    <img src="<?= $img ?>">

                    <div class="food-info">

                        <h3>

                            <?= $m['ten_mon'] ?>

                        </h3>

                        <?php if ($isBuffet): ?>

                            <p class="price">

                                Buffet

                            </p>

                        <?php else: ?>

                            <p class="price">

                                <?= number_format($m['gia']) ?>đ

                            </p>

                        <?php endif; ?>

                    </div>

                    <div class="right-box">

                        <div class="qty-box">

                            <button
                                type="button"
                                onclick="giam(this)">
                                -
                            </button>

                            <input
                                type="number"
                                name="so_luong[<?= $m['id'] ?>]"
                                value="0"
                                min="0">

                            <button
                                type="button"
                                onclick="tang(this)">
                                +
                            </button>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <button
            type="submit"
            class="submit-btn">
            Thêm vào giỏ
        </button>

    </form>

    <!-- TRÁNG MIỆNG -->

    <?php if ($isBuffet && !$coTrangMieng): ?>

        <div class="buffet-box">

            <h3>

                🍨 Buffet tráng miệng

            </h3>

            <p>

                +49,000đ

            </p>

            <a
                href="
        ?buffet=<?= $goi ?>
        &trang_mieng=1
        &lau=<?= $_SESSION['lau'] ?? '' ?>
        &id_ban=<?= $id_ban ?>
        "
                class="buffet-btn">
                Thêm ngay
            </a>

        </div>

    <?php endif; ?>

    <script>
        function tang(btn) {

            let input =
                btn.previousElementSibling;

            input.value =
                parseInt(input.value) + 1;

        }

        function giam(btn) {

            let input =
                btn.nextElementSibling;

            if (parseInt(input.value) > 0) {

                input.value =
                    parseInt(input.value) - 1;

            }

        }
    </script>
    <script>
        function goiNhanVien() {

            fetch(
                    "../actions/goi_nhan_vien.php", {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },

                        body: "id_ban=<?= $id_ban ?>"

                    }
                )

                .then(res => res.text())

                .then(data => {

                    alert(
                        "Nhân viên sẽ đến hỗ trợ bạn"
                    );

                });

        }
    </script>
</body>

</html>