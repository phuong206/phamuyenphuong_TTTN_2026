<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

// ===== FIX MÓN LẺ (QUAN TRỌNG NHẤT) =====
if (isset($_GET['mon_le'])) {
    unset($_SESSION['buffet']);
    unset($_SESSION['trang_mieng']);
    unset($_SESSION['lau']);
}

// ===== LẤY ID BÀN =====
if (isset($_GET['id_ban'])) {
    $_SESSION['id_ban'] = $_GET['id_ban'];
}

// ===== NHẬN DỮ LIỆU =====
if (isset($_GET['buffet'])) {
    $_SESSION['buffet'] = $_GET['buffet'];
}

if (isset($_GET['trang_mieng'])) {
    $_SESSION['trang_mieng'] = $_GET['trang_mieng'];
}

if (isset($_GET['lau'])) {
    $_SESSION['lau'] = $_GET['lau'];
}

$id_ban = $_SESSION['id_ban'] ?? 1;

// ===== LOGIC =====
$isBuffet = isset($_SESSION['buffet']);
$goi = $_SESSION['buffet'] ?? 0;
$coTrangMieng = isset($_SESSION['trang_mieng']);

// ===== QUERY =====
if ($isBuffet) {

    if ($coTrangMieng) {
        // buffet + tráng miệng → ẩn nước
        $sql = "
        SELECT * FROM mon_an 
        WHERE trang_thai = 1
        AND goi_buffet <= $goi
        AND goi_buffet > 0
        AND ten_mon NOT LIKE '%coca%'
        AND ten_mon NOT LIKE '%pepsi%'
        AND ten_mon NOT LIKE '%7up%'
        AND ten_mon NOT LIKE '%trà%'
        ";
    } else {
        // buffet thường
        $sql = "
        SELECT * FROM mon_an 
        WHERE trang_thai = 1
        AND goi_buffet <= $goi
        AND goi_buffet > 0
        ";
    }
} else {
    // món lẻ = full menu
    $sql = "SELECT * FROM mon_an WHERE trang_thai = 1";
}

$menu = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <link rel="stylesheet" href="../css/menu.css">
</head>

<body>

    <h2>Bàn <?= $id_ban ?></h2>

    <!-- HIỂN THỊ LOẠI -->
    <?php if ($isBuffet): ?>
        <p style="color:#38bdf8;">Gói Buffet <?= $goi ?>K</p>
    <?php else: ?>
        <p style="color:#facc15;">Món lẻ</p>
    <?php endif; ?>

    <!-- LINK CHỌN LẠI -->
    <a href="?mon_le=1&id_ban=<?= $id_ban ?>" style="margin-right:10px;">🍽 Món lẻ</a>
    <a href="../../html/chon_buffet.html">🍱 Buffet</a>
    <br><br>

    <a href="gio_hang.php">🛒 Giỏ hàng</a>

    <form action="../actions/them_xoa_sua.php?action=them" method="POST">

        <div class="grid">

            <?php foreach ($menu as $m): ?>

                <?php
                $img = !empty($m['hinh_anh'])
                    ? "../../images/" . $m['hinh_anh']
                    : "../../images/default.jpg";
                ?>

                <div class="card">
                    <img src="<?= $img ?>">

                    <h3><?= $m['ten_mon'] ?></h3>

                    <!-- HIỂN THỊ GIÁ -->
                    <?php if ($isBuffet): ?>
                        <p style="color:#22c55e;">Buffet</p>
                    <?php else: ?>
                        <p><?= number_format($m['gia']) ?> đ</p>
                    <?php endif; ?>

                    <div class="qty">
                        <button type="button" onclick="giam(this)">-</button>

                        <input type="number"
                            name="so_luong[<?= $m['id'] ?>]"
                            value="0"
                            min="0">

                        <button type="button" onclick="tang(this)">+</button>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <button type="submit">Thêm vào giỏ</button>

    </form>

    <!-- ===== TRÁNG MIỆNG ===== -->
    <?php if ($isBuffet && !$coTrangMieng): ?>
        <div class="card" style="border:2px dashed #38bdf8; margin:15px;">
            <h3>🍨 Buffet tráng miệng</h3>
            <p>+49,000 đ</p>

            <a href="?buffet=<?= $goi ?>&trang_mieng=1&lau=<?= $_SESSION['lau'] ?? '' ?>&id_ban=<?= $id_ban ?>"
                class="btn">
                Thêm ngay
            </a>
        </div>
    <?php endif; ?>

    <script src="../js/menu.js"></script>

</body>

</html>