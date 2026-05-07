<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_ban = $_GET['id_ban'] ?? 0;

/* lấy đơn */
$stmt = $conn->prepare("
    SELECT * FROM don_hang 
    WHERE id_ban = ? AND trang_thai = 'cho_xu_ly'
    ORDER BY id DESC LIMIT 1
");
$stmt->execute([$id_ban]);
$don = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$don) {
    echo "<script>alert('Bàn chưa có khách');location.href='danh_sach_ban.php';</script>";
    exit;
}

$id_don = $don['id'];
$so_khach = $don['so_khach'];

/* lấy món đã gọi */
$stmt = $conn->prepare("
    SELECT ct.*, m.ten_mon, m.gia
    FROM chi_tiet_don_hang ct
    JOIN mon_an m ON ct.id_mon = m.id
    WHERE ct.id_don_hang = ?
");
$stmt->execute([$id_don]);
$ds = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* tính tiền */
$tong = 0;
foreach ($ds as $m) {
    $tong += $m['so_luong'] * $m['gia'];
}

/* map trạng thái */
$map = [
    "chua_gui" => "Chưa gửi",
    "dang_nau" => "Đang nấu",
    "da_phuc_vu" => "Đã phục vụ"
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Bàn phục vụ</title>

<style>
body{
    font-family:Segoe UI;
    background:linear-gradient(135deg,#0f172a,#1e3a8a);
    padding:30px;color:white;
}

.box{
    background:#f8fafc;color:black;
    border-radius:12px;padding:15px;margin-bottom:15px;
}

.mon{
    display:flex;justify-content:space-between;
    padding:10px;border-bottom:1px solid #ddd;
}

.tag{padding:4px 8px;border-radius:6px;font-size:12px;}
.chua_gui{background:#e2e8f0;}
.dang_nau{background:#94a3b8;color:white;}
.da_phuc_vu{background:#0f172a;color:white;}

.btn{padding:10px;border:none;border-radius:8px;cursor:pointer;}
.btn-dark{background:#0f172a;color:white;}
.btn-outline{border:1px solid #ccc;background:white;}
</style>
</head>

<body>

<h2>Bàn <?= $id_ban ?> - Đang phục vụ</h2>

<div class="box">
<b>Số khách:</b> <?= $so_khach ?>
</div>

<div class="box">
<h3>Danh sách món đã gọi</h3>

<?php foreach($ds as $m): ?>
<div class="mon">
    <div>
        <?= $m['ten_mon'] ?> <br>
        SL: <?= $m['so_luong'] ?>
    </div>

    <div class="tag <?= $m['trang_thai'] ?>">
        <?= $map[$m['trang_thai']] ?>
    </div>
</div>
<?php endforeach; ?>

</div>

<div class="box">

<div style="display:flex;justify-content:space-between">
    <span>Tổng:</span>
    <b><?= number_format($tong) ?>đ</b>
</div>

<br>

<button
    class="btn btn-outline"
    onclick="guiBep()"
>
    Gửi bếp
</button>

<?php if($_SESSION['id_vai_tro'] == 6){ ?>

<button
    class="btn btn-outline"
    onclick="location.href='bep.php'"
>
    🍳 Bếp
</button>

<?php } ?>

<button
    class="btn btn-dark"
    onclick="
        location.href=
        'thanh_toan.php?id_don=<?= $id_don ?>'
    "
>
    Thanh toán
</button>

</div>

<script>
function guiBep(){
    fetch("../xu_ly/gui_bep.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"id_don=<?= $id_don ?>"
    }).then(()=>location.reload());
}
</script>

</body>
</html>