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

            $sl = (int)$sl;

            if ($sl > 0) {

                /* đã có món */

                if (isset($_SESSION['gio_hang'][$id])) {

                    $_SESSION['gio_hang'][$id] += $sl;
                }

                /* chưa có */ else {

                    $_SESSION['gio_hang'][$id] = $sl;
                }
            }
        }

        header("Location: ../pages/gio_hang.php");

        exit();


        // ===== XÓA =====

    case 'xoa':

        $id = $_GET['id'] ?? 0;

        if (isset($_SESSION['gio_hang'][$id])) {

            unset($_SESSION['gio_hang'][$id]);
        }

        // nếu rỗng

        if (empty($_SESSION['gio_hang'])) {

            $_SESSION['gio_hang'] = [];
        }

        header("Location: ../pages/gio_hang.php");

        exit();


        // ===== CẬP NHẬT =====

    case 'cap_nhat':

        if (isset($_POST['so_luong'])) {

            foreach ($_POST['so_luong'] as $id => $sl) {

                if ($sl > 0) {

                    $_SESSION['gio_hang'][$id] = $sl;
                } else {

                    unset($_SESSION['gio_hang'][$id]);
                }
            }
        }

        header("Location: ../pages/gio_hang.php");

        exit();


        // ===== TRẠNG THÁI =====

    case 'trang_thai':

        $id = $_GET['id'] ?? 0;

        $status = $_GET['status'] ?? '';

        $sql = "
        UPDATE don_hang
        SET trang_thai = ?
        WHERE id = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([

            $status,
            $id

        ]);

        echo "OK";

        exit();


        // ===== DEFAULT =====

    default:

        header("Location: ../pages/gio_hang.php");

        exit();
}
