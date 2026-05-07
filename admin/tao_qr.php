<?php

include "phpqrcode/qrlib.php";

require_once __DIR__ . "/../config/db.php";

$db = new Database();

$conn = $db->connect();

$sql = "SELECT * FROM ban";

$stmt = $conn->prepare($sql);

$stmt->execute();

$dsBan = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Tạo QR Bàn</title>

    <style>

        body{
            font-family:Arial;
            background:#f1f5f9;
            padding:30px;
        }

        h1{
            margin-bottom:30px;
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:20px;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:15px;
            text-align:center;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        }

        .card img{
            width:200px;
            margin-bottom:15px;
        }

        .card h3{
            margin-bottom:10px;
        }

    </style>

</head>

<body>

<h1>QR Code Bàn Ăn</h1>

<div class="grid">

<?php

foreach($dsBan as $row) {

    $id_ban = $row['id'];

    $so_ban = $row['so_ban'];

    $link =
    "http://192.168.1.40/E_MENU/khachhang/menu.php?id_ban=$id_ban";

    $folder = "../images/qr/";

    if(!file_exists($folder)) {

        mkdir($folder, 0777, true);

    }

    $file = $folder . $so_ban . ".png";

    QRcode::png($link, $file, QR_ECLEVEL_H, 10);

?>

    <div class="card">

        <h3><?= $so_ban ?></h3>

        <img src="../images/qr/<?= $so_ban ?>.png">

        <p>Quét để gọi món</p>

    </div>

<?php } ?>

</div>

</body>
</html>