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
    $sql = "SELECT * FROM tai_khoan WHERE email=? AND mat_khau=? AND id_vai_tro!=1";
}

// dùng PDO
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $password]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {

    session_start();

    $_SESSION['user']
        = $username;

    $_SESSION['id_tai_khoan']
        = $user['id'];

    $_SESSION['id_vai_tro']
        = $user['id_vai_tro'];

    $_SESSION['ho_ten']
        = $user['ho_ten'];

    /* =========================
       CHECK ĐỔI MẬT KHẨU
    ========================= */

    if ($user['doi_mat_khau'] == 1) {

        echo json_encode([

            "success" => true,

            "redirect" =>
            "../../login/php/doi_mat_khau.php"

        ]);

        exit();
    }

    /* =========================
       LOGIN BÌNH THƯỜNG
    ========================= */

    echo json_encode([

        "success" => true,

        "redirect" =>

        $role == "admin"

            ?

            "../../admin/bang_dieu_khien.php"

            :

            "../../staff/php/danh_sach_ban.php"

    ]);
} else {

    echo json_encode([

        "success" => false

    ]);
}
