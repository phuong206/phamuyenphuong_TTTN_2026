<?php
require_once __DIR__ . "/../../config/db.php";
$db = new Database();
$conn = $db->connect();

$stmt = $conn->query("
SELECT tb.*, b.so_ban
FROM thong_bao_nhan_vien tb
JOIN ban b ON b.id = tb.id_ban
WHERE tb.trang_thai = 'da_xu_ly'
ORDER BY tb.thoi_gian DESC
");

$ds = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* fix time */
function timeAgo($time){
    if(!$time) return "Không rõ";

    $timestamp = strtotime($time);
    if(!$timestamp) return "Lỗi thời gian";

    $diff = time() - $timestamp;

    if($diff < 10) return "Vừa xong";
    if($diff < 60) return $diff . " giây trước";
    if($diff < 3600) return floor($diff/60) . " phút trước";
    if($diff < 86400) return floor($diff/3600) . " giờ trước";

    return date("d/m H:i", $timestamp); // quá lâu thì hiện giờ thật
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Lịch sử thông báo</title>

<style>
body{
    background:#0f172a;
    color:white;
    font-family:sans-serif;
    padding:20px;
}

.box{
    max-width:600px;
    margin:auto;
    background:#1e293b;
    padding:20px;
    border-radius:12px;
}

.item{
    border-bottom:1px solid #334155;
    padding:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.badge{
    font-size:12px;
    background:#2563eb;
    padding:4px 8px;
    border-radius:6px;
}

.done{
    color:#22c55e;
    font-size:12px;
}

.info{
    display:flex;
    flex-direction:column;
    gap:4px;
}

.ban{
    font-size:16px;
    font-weight:600;
}

.meta{
    font-size:12px;
    color:#94a3b8;
}

.time{
    color:#22c55e;
    font-weight:600;
    font-size:14px;
}
</style>
</head>

<body>

<div class="box">

<h2>📋 Lịch sử thông báo</h2>

<?php if(empty($ds)): ?>
    <p style="text-align:center;">Chưa có lịch sử</p>
<?php endif; ?>

<?php foreach($ds as $tb): ?>

<div class="item">

<div class="info">
    <span class="badge">Gọi NV</span>

    <div class="ban">
        Bàn <?= htmlspecialchars($tb['so_ban']) ?>
    </div>

    <div class="meta">
        <?= $tb['so_lan_goi'] ?> lần • ✔ Đã phục vụ
    </div>
</div>

<div class="time">
    <?= date("H:i d/m/Y", strtotime($tb['thoi_gian'])) ?>
</div>

</div>

<?php endforeach; ?>

</div>

</body>
</html>