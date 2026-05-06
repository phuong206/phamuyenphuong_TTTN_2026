<?php
require_once __DIR__ . "/../../config/db.php";
$db = new Database();
$conn = $db->connect();

/* lấy thông báo CHƯA xử lý */
$stmt = $conn->query("
SELECT tb.*, b.so_ban
FROM thong_bao_nhan_vien tb
JOIN ban b ON b.id = tb.id_ban
WHERE tb.trang_thai = 'chua_xu_ly'
ORDER BY tb.id DESC
");

$ds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thông báo</title>

<style>
body{
    background:linear-gradient(135deg,#0f172a,#1e3a8a);
    color:white;
    font-family:sans-serif;
    padding:20px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

.card{
    background:#1e293b;
    padding:15px;
    border-radius:12px;
    margin-bottom:10px;
    cursor:pointer;
    transition:0.2s;
}

.card:hover{
    transform:translateY(-3px);
    background:#334155;
}

.btn{
    margin-top:10px;
    padding:8px;
    border:none;
    border-radius:8px;
    background:#22c55e;
    color:white;
    cursor:pointer;
}
</style>
</head>

<body>

<h2>🔔 Thông báo</h2>
<div style="text-align:center; margin-bottom:20px;">
    <button 
    onclick="location.href='hien_thi_thong_bao.php'"
    style="
    padding:10px 15px;
    border:none;
    border-radius:8px;
    background:#2563eb;
    color:white;
    cursor:pointer;
    ">
    📋 Xem lịch sử thông báo
    </button>
</div>
<?php if(empty($ds)): ?>
    <p style="text-align:center;">Không có yêu cầu nào</p>
<?php endif; ?>

<?php foreach($ds as $tb): ?>

<div class="card" onclick="location.href='hien_thi_thong_bao.php'">

    <b>🔔 Bàn <?= $tb['so_ban'] ?> đang gọi nhân viên</b>

    <form action="../xu_ly/xu_ly_thong_bao.php" method="POST">
        <input type="hidden" name="id_tb" value="<?= $tb['id'] ?>">
        <button class="btn">✔ Phục vụ</button>
    </form>

</div>

<?php endforeach; ?>

</body>
</html>