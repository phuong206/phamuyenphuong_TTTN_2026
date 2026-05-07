<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_ban = $_SESSION['id_ban'] ?? 1;

/* lấy đơn đang hoạt động */
$stmt = $conn->prepare("
SELECT * FROM don_hang 
WHERE id_ban=? AND trang_thai!='da_thanh_toan'
ORDER BY id DESC LIMIT 1
");
$stmt->execute([$id_ban]);
$don = $stmt->fetch(PDO::FETCH_ASSOC);

$id_don = $don['id'] ?? 0;

/* lấy món */
$stmt = $conn->prepare("
SELECT ct.*, m.ten_mon 
FROM chi_tiet_don_hang ct
JOIN mon_an m ON m.id = ct.id_mon
WHERE ct.id_don_hang=?
");
$stmt->execute([$id_don]);

$ds = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* tính tiền */
$tong = 0;
foreach($ds as $m){
    $tong += $m['so_luong'] * ($m['gia'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Trạng thái đơn</title>

<style>
body{
    background:#f1f5f9;
    font-family:sans-serif;
    display:flex;
    justify-content:center;
    padding:20px;
}

.box{
    width:360px;
    background:white;
    border-radius:14px;
    padding:20px;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

h2{
    font-size:18px;
    margin-bottom:5px;
}

.sub{
    font-size:13px;
    color:#64748b;
    margin-bottom:15px;
}

/* món */
.item{
    border:1px solid #e2e8f0;
    border-radius:10px;
    padding:10px;
    margin-bottom:10px;
}

.bar{
    height:6px;
    border-radius:6px;
    background:#e2e8f0;
    margin-top:6px;
    overflow:hidden;
}

.fill{
    height:100%;
    background:#0f172a;
}

.status{
    font-size:12px;
    margin-top:5px;
    text-align:right;
    color:#64748b;
}

/* tổng */
.total{
    margin-top:10px;
    font-weight:600;
    display:flex;
    justify-content:space-between;
}

/* button */
.btn-group{
    display:flex;
    gap:10px;
    margin-top:15px;
}

.btn{
    flex:1;
    padding:10px;
    border-radius:8px;
    border:none;
    cursor:pointer;
}

.btn-outline{
    border:1px solid #cbd5e1;
    background:white;
}

.btn-primary{
    background:#0f172a;
    color:white;
}
</style>

</head>

<body>

<div class="box">

<h2>📱 Trạng thái đơn</h2>
<div class="sub">Bàn <?= $id_ban ?></div>

<!-- danh sách món -->
<?php foreach($ds as $m): ?>

<?php
$tt = $m['trang_thai'] ?? 'cho';

$percent = 25;
$text = "Đang chờ";

if($tt == 'dang_nau'){
    $percent = 60;
    $text = "Đang nấu";
}
if($tt == 'da_phuc_vu'){
    $percent = 100;
    $text = "Đã phục vụ";
}
?>

<div class="item">
    <b><?= $m['ten_mon'] ?></b><br>
    <small>Số lượng: <?= $m['so_luong'] ?></small>

    <div class="bar">
        <div class="fill" style="width:<?= $percent ?>%"></div>
    </div>

    <div class="status"><?= $text ?></div>
</div>

<?php endforeach; ?>

<!-- tổng -->
<div class="total">
    <span>Tổng tiền:</span>
    <span><?= number_format($tong) ?>đ</span>
</div>

<!-- nút -->
<div class="btn-group">
    <button class="btn btn-outline"
        onclick="location.href='menu.php?id_ban=<?= $id_ban ?>'">
        Thêm món
    </button>

    <button class="btn btn-primary"
        onclick="location.href='thanh_toan.php?id_don=<?= $id_don ?>'">
        Thanh toán
    </button>
</div>

</div>

</body>
</html>