<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$data = json_decode(file_get_contents("php://input"), true);

$role = $data['role'];
$username = $data['username'];

$newPass = rand(100000,999999);

// update mật khẩu
$sql = "
UPDATE tai_khoan

SET
    mat_khau=?,
    doi_mat_khau=1

WHERE email=?
";
$stmt = $conn->prepare($sql);
$stmt->execute([$newPass, $username]);

// log
$sqlLog = "INSERT INTO log_reset (email, role) VALUES (?,?)";
$stmtLog = $conn->prepare($sqlLog);
$stmtLog->execute([$username, $role]);

echo json_encode([
    "message" => "Mật khẩu mới: $newPass"
]);
?>