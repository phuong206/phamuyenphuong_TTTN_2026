<?php

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conn = $db->connect();

/* =========================
   DOANH THU THÁNG
========================= */

$sql = "
SELECT SUM(tong_tien) AS doanh_thu

FROM don_hang

WHERE trang_thai = 'da_thanh_toan'

AND MONTH(created_at) = MONTH(NOW())

AND YEAR(created_at) = YEAR(NOW())
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$doanhThu =
$stmt->fetch(PDO::FETCH_ASSOC);

$tongDoanhThu =
$doanhThu['doanh_thu'] ?? 0;

/* =========================
   TỔNG ĐƠN THÁNG
========================= */

$sql = "
SELECT COUNT(*) AS tong_don

FROM don_hang

WHERE MONTH(created_at) = MONTH(NOW())

AND YEAR(created_at) = YEAR(NOW())
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$tongDon =
$stmt->fetch(PDO::FETCH_ASSOC);

$soDon =
$tongDon['tong_don'] ?? 0;

/* =========================
   MÓN BÁN CHẠY
========================= */

$sql = "
SELECT
    mon_an.ten_mon,
    SUM(chi_tiet_don_hang.so_luong)
    AS tong_ban

FROM chi_tiet_don_hang

LEFT JOIN mon_an
ON chi_tiet_don_hang.id_mon =
mon_an.id

GROUP BY mon_an.id

ORDER BY tong_ban DESC

LIMIT 5
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$topMon =
$stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   DOANH THU 7 NGÀY
========================= */

$sql = "
SELECT
    DATE(created_at) AS ngay,
    SUM(tong_tien) AS doanh_thu

FROM don_hang

WHERE trang_thai = 'da_thanh_toan'

AND created_at >=
DATE_SUB(NOW(), INTERVAL 7 DAY)

GROUP BY DATE(created_at)

ORDER BY ngay ASC
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$dsDoanhThu =
$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'thanh_phan/header.php'; ?>
<?php include 'thanh_phan/sidebar.php'; ?>

<div class="main">

    <div class="top">

        <div>

            <h1>Thống kê</h1>

            <p>
                Báo cáo doanh thu hệ thống
            </p>

        </div>

    </div>

    <!-- CARDS -->

    <div class="cards">

        <div class="card">

            <h3>Doanh thu tháng</h3>

            <p>

                <?= number_format(
                    $tongDoanhThu
                ) ?>đ

            </p>

        </div>

        <div class="card">

            <h3>Đơn hàng tháng</h3>

            <p>

                <?= $soDon ?>

            </p>

        </div>

        <div class="card">

            <h3>Món bán chạy</h3>

            <p>

                <?=
                $topMon[0]['ten_mon']
                ?? 'Chưa có'
                ?>

            </p>

        </div>

    </div>

    <!-- TOP MÓN -->

    <div class="table-box">

        <h2>Top món bán chạy</h2>

        <table class="order-table">

            <tr>

                <th>Món ăn</th>

                <th>Số lượng bán</th>

            </tr>

            <?php foreach($topMon as $mon){ ?>

            <tr>

                <td>

                    <?= $mon['ten_mon'] ?>

                </td>

                <td>

                    <?= $mon['tong_ban'] ?>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

    <!-- DOANH THU -->

    <div class="table-box">

        <h2>Doanh thu 7 ngày</h2>

        <table class="order-table">

            <tr>

                <th>Ngày</th>

                <th>Doanh thu</th>

            </tr>

            <?php foreach($dsDoanhThu as $row){ ?>

            <tr>

                <td>

                    <?= $row['ngay'] ?>

                </td>

                <td>

                    <?= number_format(
                        $row['doanh_thu']
                    ) ?>đ

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

<?php include 'thanh_phan/footer.php'; ?>