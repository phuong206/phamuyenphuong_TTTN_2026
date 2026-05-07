<?php

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conn = $db->connect();

/* =========================
   CẬP NHẬT TRẠNG THÁI
========================= */

if (isset($_POST['cap_nhat'])) {

    $id = $_POST['id'];

    $trang_thai = $_POST['trang_thai'];

    $sql = "
    UPDATE don_hang

    SET trang_thai=?

    WHERE id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $trang_thai,
        $id
    ]);

    header("Location: don_hang.php");
    exit();
}

/* =========================
   DANH SÁCH ĐƠN
========================= */

$sql = "
SELECT 
    don_hang.*,
    ban.so_ban

FROM don_hang

JOIN ban
ON don_hang.id_ban = ban.id

ORDER BY don_hang.id DESC
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$dsDon = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'thanh_phan/header.php'; ?>
<?php include 'thanh_phan/sidebar.php'; ?>

<div class="main">

    <div class="top">

        <div>

            <h1>Đơn hàng</h1>

            <p>
                Quản lý đơn hàng nhà hàng
            </p>

        </div>

    </div>

    <div class="table-box">

        <table class="order-table">

            <tr>

                <th>Mã đơn</th>
                <th>Bàn</th>
                <th>Khách</th>
                <th>Loại</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>

            </tr>

            <?php foreach ($dsDon as $row) { ?>

                <tr>

                    <td>

                        <strong>
                            #<?= $row['id'] ?>
                        </strong>

                    </td>

                    <td>

                        <?= $row['so_ban'] ?>

                    </td>

                    <td>

                        <?= $row['so_khach'] ?? 0 ?>

                    </td>

                    <td>

                        <?= strtoupper($row['loai']) ?>

                    </td>

                    <td>

                        <strong>
                            <?= number_format($row['tong_tien']) ?>đ
                        </strong>

                    </td>

                    <td>

                        <?php

                        $class = "dang-cho";
                        $text = "Chờ xử lý";

                        if ($row['trang_thai'] == 'da_thanh_toan') {

                            $class = "hoan-thanh";
                            $text = "Đã thanh toán";
                        }

                        if ($row['trang_thai'] == 'da_huy') {

                            $class = "da-huy";
                            $text = "Đã hủy";
                        }

                        ?>

                        <span class="badge <?= $class ?>">

                            <?= $text ?>

                        </span>

                    </td>

                    <td>

                        <?= date(
                            "d/m/Y H:i",
                            strtotime($row['created_at'])
                        ) ?>

                    </td>

                    <td>

                        <div class="action-group">

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $row['id'] ?>">

                                <select
                                    name="trang_thai"
                                    class="status-select">

                                    <option
                                        value="cho_xu_ly"

                                        <?= $row['trang_thai'] == 'cho_xu_ly'
                                            ? 'selected'
                                            : '' ?>>

                                        Chờ xử lý

                                    </option>

                                    <option
                                        value="da_thanh_toan"

                                        <?= $row['trang_thai'] == 'da_thanh_toan'
                                            ? 'selected'
                                            : '' ?>>

                                        Đã thanh toán

                                    </option>

                                    <option
                                        value="da_huy"

                                        <?= $row['trang_thai'] == 'da_huy'
                                            ? 'selected'
                                            : '' ?>>

                                        Đã hủy

                                    </option>

                                </select>

                                <button
                                    type="submit"
                                    name="cap_nhat"
                                    class="btn btn-warning btn-sm">
                                    Lưu
                                </button>

                            </form>

                            <a
                                href="chi_tiet_don_hang.php?id=<?= $row['id'] ?>"
                                class="btn btn-primary btn-sm">
                                Chi tiết
                            </a>

                        </div>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

<?php include 'thanh_phan/footer.php'; ?>