<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_don = $_POST['id_don'] ?? 0;
$id_ban = $_POST['id_ban'] ?? 0;
$tong = $_POST['tong'] ?? 0;
$phuong_thuc = $_POST['phuong_thuc'] ?? '';

if (!$id_don || !$id_ban) {
    die("Thiếu dữ liệu thanh toán");
}

/* chống thanh toán 2 lần */
$stmt = $conn->prepare("SELECT trang_thai FROM don_hang WHERE id=?");
$stmt->execute([$id_don]);
$check = $stmt->fetch(PDO::FETCH_ASSOC);

if ($check['trang_thai'] == 'da_thanh_toan') {
    die("Đơn đã thanh toán rồi");
}

/* lưu thanh toán */
$conn->prepare("
INSERT INTO thanh_toan(id_don_hang, so_tien, phuong_thuc, thoi_gian)
VALUES (?, ?, ?, NOW())
")->execute([$id_don, $tong, $phuong_thuc]);

/* update đơn */
$conn->prepare("
UPDATE don_hang 
SET tong_tien=?, trang_thai='da_thanh_toan'
WHERE id=?
")->execute([$tong, $id_don]);

/* reset bàn */
$conn->prepare("
UPDATE ban SET trang_thai=1 WHERE id=?
")->execute([$id_ban]);
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
            padding: 30px;
            border-radius: 16px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="box">
        <h2>✔ Thanh toán thành công</h2>
        <p><?= number_format($tong) ?>đ</p>
        <button onclick="location.href='../php/danh_sach_ban.php'">Quay lại</button>
    </div>

</body>

</html>