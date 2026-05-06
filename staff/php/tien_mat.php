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

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
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
        <h2>Tiền mặt</h2>

        <p><?= number_format($tong) ?>đ</p>

        <input type="number" id="tien" placeholder="Khách đưa">

        <button onclick="tinh()">Tính</button>
        <div id="kq"></div>

        <form action="../xu_ly/xu_ly_thanh_toan.php" method="POST">
            <input type="hidden" name="id_ban" value="<?= $id_ban ?>">
            <input type="hidden" name="id_don" value="<?= $id_don ?>">
            <input type="hidden" name="tong" value="<?= $tong ?>">
            <input type="hidden" name="phuong_thuc" value="tien_mat">
            <button>✔ Hoàn tất</button>
        </form>
    </div>

    <script>
        function tinh() {
            let tong = <?= $tong ?>;
            let tien = document.getElementById("tien").value;
            let thoi = tien - tong;
            document.getElementById("kq").innerText = "Tiền thối: " + thoi.toLocaleString() + "đ";
        }
    </script>

</body>

</html>