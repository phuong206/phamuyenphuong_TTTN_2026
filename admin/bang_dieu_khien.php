<?php

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conn = $db->connect();

/* =========================
   THỐNG KÊ
========================= */

$tongDoanhThu = $conn->query("
    SELECT SUM(tong_tien)
    FROM don_hang
    WHERE trang_thai != 'da_huy'
")->fetchColumn();

if (!$tongDoanhThu) {
    $tongDoanhThu = 0;
}

$donHomNay = $conn->query("
    SELECT COUNT(*)
    FROM don_hang
    WHERE DATE(created_at) = CURDATE()
")->fetchColumn();

$tongKhach = $conn->query("
    SELECT SUM(so_khach)
    FROM don_hang
    WHERE trang_thai != 'da_huy'
")->fetchColumn();


if (!$tongKhach) {
    $tongKhach = 0;
}

/* =========================
   MÓN PHỔ BIẾN
========================= */

$sqlMon = "
SELECT 
    mon_an.ten_mon,
    SUM(chi_tiet_don_hang.so_luong) as tong_sl
FROM chi_tiet_don_hang

JOIN mon_an
ON chi_tiet_don_hang.id_mon = mon_an.id

GROUP BY mon_an.id

ORDER BY tong_sl DESC

LIMIT 1
";

$stmtMon = $conn->prepare($sqlMon);

$stmtMon->execute();

$monPhoBien = $stmtMon->fetch(PDO::FETCH_ASSOC);

/* =========================
   ĐƠN HÀNG GẦN ĐÂY
========================= */

$sqlDon = "
SELECT 
    don_hang.*,
    ban.so_ban

FROM don_hang

JOIN ban
ON don_hang.id_ban = ban.id

ORDER BY don_hang.id DESC

LIMIT 5
";

$stmtDon = $conn->prepare($sqlDon);

$stmtDon->execute();

$dsDon = $stmtDon->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'thanh_phan/header.php'; ?>
<?php include 'thanh_phan/sidebar.php'; ?>

<div class="main">

    <div class="top">
        <h1>Dashboard</h1>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Tổng doanh thu</h3>

            <p>
                <?= number_format($tongDoanhThu) ?>đ
            </p>
        </div>

        <div class="card">
            <h3>Đơn hôm nay</h3>

            <p>
                <?= $donHomNay ?>
            </p>
        </div>

        <div class="card">
            <h3>Khách hàng</h3>

            <p>
                <?= $tongKhach ?>
            </p>
        </div>

        <div class="card">
            <h3>Món phổ biến</h3>

            <p>
                <?= $monPhoBien['ten_mon'] ?? 'Chưa có' ?>
            </p>
        </div>

    </div>

    <div class="table-box">

        <h2>Đơn hàng gần đây</h2>

        <br>

        <table>

            <tr>
                <th>ID</th>
                <th>Bàn</th>
                <th>Trạng thái</th>
                <th>Tổng tiền</th>
            </tr>

            <?php foreach ($dsDon as $row) { ?>

                <tr>

                    <td>
                        #<?= $row['id'] ?>
                    </td>

                    <td>
                        <?= $row['so_ban'] ?>
                    </td>

                    <td>

                        <?php

                        $class = "dang-cho";

                        if ($row['trang_thai'] == 'da_thanh_toan') {
                            $class = "hoan-thanh";
                        }

                        if ($row['trang_thai'] == 'hoan_thanh') {
                            $class = "hoan-thanh";
                        }

                        if ($row['trang_thai'] == 'cho_xu_ly') {
                            $class = "dang-cho";
                        }

                        if ($row['trang_thai'] == 'da_huy') {
                            $class = "btn-danger";
                        }

                        ?>

                        <span class="badge <?= $class ?>">

                            <?= $row['trang_thai'] ?>

                        </span>

                    </td>

                    <td>
                        <?= number_format($row['tong_tien']) ?>đ
                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

<?php include 'thanh_phan/footer.php'; ?>