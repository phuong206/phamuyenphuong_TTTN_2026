<?php
$id_ban = $_GET['id_ban'];
$id_don = $_GET['id_don'];
$tong = $_GET['tong'];
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
            width: 300px;
            text-align: center;
        }

        button {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
        }
    </style>
</head>

<body>

    <div class="box">
        <h2>Xác nhận</h2>
        <p><?= number_format($tong) ?>đ</p>

        <button onclick="location.href='tien_mat.php?id_ban=<?= $id_ban ?>&id_don=<?= $id_don ?>&tong=<?= $tong ?>'">
            💵 Tiền mặt
        </button>

        <button onclick="location.href='chuyen_khoan.php?id_ban=<?= $id_ban ?>&id_don=<?= $id_don ?>&tong=<?= $tong ?>'">
            🏦 Chuyển khoản
        </button>
    </div>

</body>

</html>