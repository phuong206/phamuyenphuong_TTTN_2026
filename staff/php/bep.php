<?php

session_start();

require_once __DIR__ . "/../../config/db.php";

$db = new Database();

$conn = $db->connect();

/* =========================
   CHECK LOGIN
========================= */

if (!isset($_SESSION['id_vai_tro'])) {

    header("Location: ../../login/login.html");

    exit();
}

/* =========================
   CHỈ BẾP ĐƯỢC VÀO
========================= */

if ($_SESSION['id_vai_tro'] != 6) {

    die("Bạn không có quyền truy cập");
}

/* =========================
   LẤY MÓN CHỜ NẤU
========================= */

$sql = "
SELECT
    chi_tiet_don_hang.*,
    mon_an.ten_mon,
    ban.so_ban

FROM chi_tiet_don_hang

LEFT JOIN mon_an
ON chi_tiet_don_hang.id_mon = mon_an.id

LEFT JOIN don_hang
ON chi_tiet_don_hang.id_don_hang = don_hang.id

LEFT JOIN ban
ON don_hang.id_ban = ban.id

WHERE chi_tiet_don_hang.trang_thai != 'da_phuc_vu'

ORDER BY chi_tiet_don_hang.id DESC
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$danhSach =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Bếp</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial;
        }

        body {

            background:
                linear-gradient(135deg,
                    #0f172a,
                    #1e293b,
                    #334155);

            padding: 30px;

            min-height: 100vh;
        }

        h1 {

            margin-bottom: 25px;

            color: #0f172a;
        }

        .top-bar {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 30px;
        }

        .top-bar h1 {

            color: white;

            margin: 0;
        }

        .back-btn {

            text-decoration: none;

            background: white;

            color: #0f172a;

            padding: 12px 18px;

            border-radius: 12px;

            font-weight: bold;

            transition: 0.2s;
        }

        .back-btn:hover {

            background: #e2e8f0;
        }

        .grid {

            display: grid;

            grid-template-columns:
                repeat(auto-fill, minmax(300px, 1fr));

            gap: 20px;
        }

        .card {

            background:
                rgba(255, 255, 255, 0.95);

            border-radius: 22px;

            padding: 25px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.15);

            transition: 0.25s;
        }

        .card:hover {

            transform: translateY(-5px);
        }

        .card h2 {

            margin-bottom: 10px;

            color: #1e293b;
        }

        .info {

            margin-bottom: 8px;

            color: #475569;
        }

        .badge {

            display: inline-block;

            padding: 6px 12px;

            border-radius: 999px;

            background: #f59e0b;

            color: white;

            font-size: 13px;

            margin-top: 10px;
        }

        .btn {

            display: block;

            width: 100%;

            margin-top: 20px;

            padding: 13px;

            border: none;

            border-radius: 14px;

            background:
                linear-gradient(135deg,
                    #22c55e,
                    #16a34a);

            color: white;

            cursor: pointer;

            font-weight: bold;

            transition: 0.2s;

            box-shadow:
                0 6px 15px rgba(34, 197, 94, 0.3);
        }

        .btn:hover {

            transform: translateY(-2px);

            opacity: 0.95;
        }
    </style>

</head>

<body>

    <div class="top-bar">

        <h1>🍳 Quản lý bếp</h1>

        <a
            href="danh_sach_ban.php"
            class="back-btn">
            ← Quay lại
        </a>

    </div>

    <div class="grid">

        <?php foreach ($danhSach as $row) { ?>

            <div class="card">

                <h2>

                    <?= $row['ten_mon'] ?>

                </h2>

                <div class="info">

                    Bàn:
                    <?= $row['so_ban'] ?>

                </div>

                <div class="info">

                    Số lượng:
                    <?= $row['so_luong'] ?>

                </div>

                <?php

                $text = "Chờ nấu";

                $class = "#f59e0b";

                if ($row['trang_thai'] == 'dang_nau') {

                    $text = "Đang nấu";

                    $class = "#3b82f6";
                }

                if ($row['trang_thai'] == 'da_phuc_vu') {

                    $text = "Đã hoàn thành";

                    $class = "#22c55e";
                }

                ?>

                <span
                    class="badge"
                    style="background:<?= $class ?>">

                    <?= $text ?>

                </span>

                <form
                    action="cap_nhat_bep.php"
                    method="POST">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $row['id'] ?>">

                    <button class="btn">

                        Hoàn thành món

                    </button>

                </form>

            </div>

        <?php } ?>

    </div>

</body>

</html>