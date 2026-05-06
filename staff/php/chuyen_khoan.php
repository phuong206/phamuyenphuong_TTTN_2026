<?php
$id_ban = $_GET['id_ban'] ?? 0;
$id_don = $_GET['id_don'] ?? 0;
$tong = $_GET['tong'] ?? 0;

if (!$id_ban || !$id_don || !$tong) {
    die("Thiếu dữ liệu");
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chuyển khoản</title>

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: white;
            font-family: 'Segoe UI', sans-serif;
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
            width: 320px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        h2 {
            color: #22c55e;
        }

        .price {
            font-size: 20px;
            margin: 10px 0;
        }

        img {
            margin: 15px 0;
            border-radius: 10px;
        }

        .bank {
            font-size: 13px;
            color: #94a3b8;
        }

        .btn {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>

<body>

    <div class="box">

        <h2>🏦 Chuyển khoản</h2>

        <div class="price">
            <?= number_format($tong) ?> đ
        </div>

        <!-- 🔥 QR AUTO THEO TIỀN -->
        <img src="https://img.vietqr.io/image/970422-9209805574258-compact2.png?amount=<?= $tong ?>&addInfo=Ban<?= $id_ban ?>" width="250">

        <div class="bank">
            Viettel Money<br>
            STK: 9704229209805574258
        </div>

        <!-- 🔥 XÁC NHẬN -->
        <form action="../xu_ly/xu_ly_thanh_toan.php" method="POST">
            <input type="hidden" name="id_ban" value="<?= $id_ban ?>">
            <input type="hidden" name="id_don" value="<?= $id_don ?>">
            <input type="hidden" name="tong" value="<?= $tong ?>">
            <input type="hidden" name="phuong_thuc" value="chuyen_khoan">

            <button class="btn">✔ Đã nhận tiền</button>
        </form>

    </div>

</body>

</html>