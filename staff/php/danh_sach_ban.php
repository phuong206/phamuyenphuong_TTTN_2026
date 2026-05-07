<?php
require_once __DIR__ . "/../../config/db.php";
$db = new Database();
$conn = $db->connect();

/* lấy trạng thái */
$stmt = $conn->query("
    SELECT b.*,
    (
        SELECT COUNT(*) 
        FROM don_hang d 
        WHERE d.id_ban = b.id 

        AND d.trang_thai != 'da_thanh_toan'

        AND d.trang_thai != 'da_huy'

    ) as co_khach
    FROM ban b
");
$bans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <div onclick="location.href='thong_bao.php'"
        style="
position:fixed;
top:20px;
right:30px;
font-size:22px;
cursor:pointer;
z-index:999;
">
        🔔
    </div>
    <title>Danh sách bàn</title>

    <style>
        :root {
            --primary: #0f172a;
            --accent: #2563eb;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            min-height: 100vh;
            padding: 30px;
        }

        h2 {
            color: #cbd5f5;
            font-size: 40px;
            margin-bottom: 20px;
            text-align: center;
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 25px;
        }

        .legend span {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #e2e8f0;
        }

        .dot {
            width: 14px;
            height: 14px;
            border-radius: 3px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .the-ban {
            padding: 22px;
            border-radius: 16px;
            text-align: center;
            cursor: pointer;
            background: white;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            transition: 0.25s;
        }

        .the-ban:hover {
            transform: translateY(-5px);
        }

        /* trạng thái */
        .trong {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
        }

        .co_khach {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: white;
        }

        .dat_truoc {
            background: #94a3b8;
            color: white;
        }

        /* ===== POPUP ===== */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .popup-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            width: 300px;
            text-align: center;
            animation: fadeIn 0.25s ease;
        }

        .popup-box h3 {
            margin-bottom: 10px;
        }

        .popup-box button {
            width: 100%;
            margin: 6px 0;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-den {
            background: #1e3a8a;
            color: white;
        }

        .btn-huy {
            background: #dc2626;
            color: white;
        }

        .btn-close {
            background: #e2e8f0;
        }

        @keyframes fadeIn {
            from {
                transform: scale(0.9);
                opacity: 0
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }
    </style>
</head>

<body>

    <h2>Danh sách bàn</h2>

    <div class="legend">
        <span>
            <div class="dot trong"></div> Trống
        </span>
        <span>
            <div class="dot co_khach"></div> Có khách
        </span>
        <span>
            <div class="dot dat_truoc"></div> Đặt trước
        </span>
    </div>

    <div class="grid">

        <?php foreach ($bans as $ban): ?>
            <?php
            $id = $ban['id'];
            $so_ban = $ban['so_ban'];

            if ($ban['trang_thai'] == 3) {
                $text = "Đặt trước";
                $class = "dat_truoc";
            } elseif ($ban['co_khach'] > 0) {
                $text = "Có khách";
                $class = "co_khach";
            } else {
                $text = "Trống";
                $class = "trong";
            }
            ?>

            <div class="the-ban <?= $class ?>" onclick="handleBan(<?= $id ?>, '<?= $class ?>')">
                <h3><?= htmlspecialchars($so_ban) ?></h3>
                <p><?= $text ?></p>
            </div>

        <?php endforeach; ?>

    </div>

    <!-- ===== POPUP ===== -->
    <div id="popup" class="popup">
        <div class="popup-box">
            <h3>Xử lý bàn</h3>
            <p>Chọn hành động</p>

            <button class="btn-den" onclick="khachDen()">Khách đã đến</button>
            <button class="btn-huy" onclick="huyBan()">Hủy đặt trước</button>
            <button class="btn-close" onclick="dongPopup()">Đóng</button>
        </div>
    </div>

    <script>
        let currentBan = null;

        function handleBan(idBan, status) {

            if (status === "dat_truoc") {
                currentBan = idBan;
                document.getElementById("popup").style.display = "flex";

            } else if (status === "trong") {
                location.href = "them_khach.php?id_ban=" + idBan;

            } else {
                location.href = "ban_dang_phuc_vu.php?id_ban=" + idBan;
            }
        }

        function khachDen() {
            fetch("../xu_ly/khach_den.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_ban=" + currentBan
            }).then(() => location.reload());
        }

        function huyBan() {
            fetch("../xu_ly/huy_dat_truoc.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_ban=" + currentBan
            }).then(() => location.reload());
        }

        function dongPopup() {
            document.getElementById("popup").style.display = "none";
        }
    </script>

</body>

</html>