<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_ban = $_SESSION['id_ban'] ?? 1;

$data = $conn->query("
    SELECT * FROM don_hang 
    WHERE id_ban = $id_ban 
    ORDER BY id DESC
")->fetchAll();
?>

<h2>📊 Trạng thái đơn</h2>

<?php if(isset($_GET['success'])): ?>
<div style="background:#16a34a;padding:10px;border-radius:10px;">
    🎉 Đặt món thành công
</div>
<?php endif; ?>

<?php foreach($data as $d): ?>
<div style="background:#1e293b;margin:10px;padding:10px;border-radius:10px;">
    <p>Đơn #<?= $d['id'] ?></p>
    <p>Trạng thái: <?= $d['trang_thai'] ?></p>
    <p>Tổng: <?= number_format($d['tong_tien']) ?> đ</p>
</div>
<?php endforeach; ?>

<a href="menu.php">⬅ Quay lại menu</a>