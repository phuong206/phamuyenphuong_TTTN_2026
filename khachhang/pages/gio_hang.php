<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$gio = $_SESSION['gio_hang'] ?? [];
$total = 0;
?>
<link rel="stylesheet" href="../css/gio_hang.css">

<h2>🛒 Giỏ hàng</h2>

<?php foreach ($gio as $id => $sl):
    $m = $conn->query("SELECT * FROM mon_an WHERE id=$id")->fetch();

    if (isset($_SESSION['buffet'])) {
        $tien = 0;
    } else {
        $tien = $m['gia'] * $sl;
    }

    $total += $tien;
?>

    <div class="item">

        <img src="../../images/<?= $m['hinh_anh'] ?>">

        <div class="info">
            <h4><?= $m['ten_mon'] ?></h4>
            <p>Số lượng: <?= $sl ?></p>
            <p class="price"><?= number_format($tien) ?> đ</p>
        </div>

        <a class="remove" href="../actions/them_xoa_sua.php?action=xoa&id=<?= $id ?>">❌</a>

    </div>

<?php endforeach; ?>

<!-- tiền buffet -->
<?php if (isset($_SESSION['buffet'])): ?>
    <p>Buffet: <?= number_format($_SESSION['buffet'] * 1000) ?> đ</p>
<?php $total += $_SESSION['buffet'] * 1000;
endif; ?>

<!-- tráng miệng -->
<?php if (isset($_SESSION['trang_mieng'])): ?> 
    <p>Tráng miệng: 49,000 đ</p>
<?php $total += 49000;
endif; ?>

<div class="total">Tổng: <?= number_format($total) ?> đ</div>

<a href="menu.php" class="btn">⬅ Quay lại</a>
<a href="../actions/dat_mon.php" class="btn">Đặt món</a>