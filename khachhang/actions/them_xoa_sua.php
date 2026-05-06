<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$action = $_GET['action'] ?? '';

if (!isset($_SESSION['gio_hang'])) {
    $_SESSION['gio_hang'] = [];
}

switch ($action) {

    // ===== THÊM GIỎ =====
    case 'them':
        foreach ($_POST['so_luong'] as $id => $sl) {
            if ($sl > 0) {
                $_SESSION['gio_hang'][$id] = $sl;
            }
        }
        header("Location: ../pages/gio_hang.php");
        break;

    // ===== XÓA =====
    case 'xoa':
        $id = $_GET['id'];
        unset($_SESSION['gio_hang'][$id]);
        header("Location: ../pages/gio_hang.php");
        break;

    // ===== CẬP NHẬT =====
    case 'cap_nhat':
        foreach ($_POST['so_luong'] as $id => $sl) {
            if ($sl > 0) {
                $_SESSION['gio_hang'][$id] = $sl;
            } else {
                unset($_SESSION['gio_hang'][$id]);
            }
        }
        header("Location: ../pages/gio_hang.php");
        break;

    // ===== CẬP NHẬT TRẠNG THÁI =====
    case 'trang_thai':
        $id = $_GET['id'];
        $status = $_GET['status'];

        $conn->query("
            UPDATE don_hang 
            SET trang_thai = '$status' 
            WHERE id = $id
        ");

        echo "OK";
        break;

    default:
        echo "Sai action";
}