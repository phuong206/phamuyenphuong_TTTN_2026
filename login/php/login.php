<?php
require_once __DIR__ . "/../../config/db.php";

// tạo object
$db = new Database();

// gọi kết nối
$conn = $db->connect();

$data = json_decode(file_get_contents("php://input"), true);

$role = $data['role'];
$username = $data['username'];
$password = $data['password'];

if ($role == "admin") {
    $sql = "SELECT * FROM tai_khoan WHERE email=? AND mat_khau=? AND id_vai_tro=1";
} else {
    $sql = "SELECT * FROM tai_khoan WHERE email=? AND mat_khau=? AND id_vai_tro=2";
}

// dùng PDO
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $password]);

if ($stmt->rowCount() > 0) {
    session_start();
    $_SESSION['user'] = $username;

    echo json_encode([
        "success" => true,
        "redirect" => $role == "admin" ? "../../admin" : "../staff/php/danh_sach_ban.php"
    ]);
} else {
    echo json_encode(["success" => false]);
}


?>