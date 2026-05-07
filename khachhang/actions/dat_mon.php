<?php

session_start();

require_once __DIR__ . "/../../config/db.php";

$db = new Database();

$conn = $db->connect();

/* =========================
   LẤY GIỎ HÀNG
========================= */

$gio =
$_SESSION['gio_hang'] ?? [];

/* =========================
   CHECK GIỎ HÀNG
========================= */

if(empty($gio)){

    die("Giỏ hàng đang trống");

}

/* =========================
   LẤY ID BÀN
========================= */

$id_ban =
$_SESSION['id_ban'] ?? 0;

if($id_ban == 0){

    die("Không tìm thấy bàn");

}

/* =========================
   SỐ KHÁCH
========================= */

$so_khach =
$_SESSION['so_khach'] ?? 1;

/* =========================
   CHECK BUFFET
========================= */

$isBuffet =
isset($_SESSION['buffet']);

$goi =
$_SESSION['buffet'] ?? 0;

/* =========================
   TÍNH TỔNG
========================= */

$tong = 0;

/* =========================
   TIỀN BUFFET
========================= */

if($isBuffet){

    $tong +=
    ($goi * 1000)
    * $so_khach;

}

/* =========================
   TRÁNG MIỆNG
========================= */

if(isset($_SESSION['trang_mieng'])){

    $tong +=
    49000 * $so_khach;

}

/* =========================
   MÓN ĂN
========================= */

foreach($gio as $id => $sl){

    /* bỏ món số lượng 0 */

    if($sl <= 0){

        continue;

    }

    $sql = "
    SELECT *
    FROM mon_an
    WHERE id=?
    ";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([$id]);

    $m =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$m){

        continue;

    }

    $gia = 0;

    /* =========================
       MÓN LẺ
    ========================= */

    if(!$isBuffet){

        $gia =
        $m['gia'];

    }

    /* =========================
       BUFFET NHƯNG GỌI NGOÀI GÓI
    ========================= */

    else{

        if($m['goi_buffet'] > $goi){

            $gia =
            $m['gia'];

        }

    }

    /* cộng tổng */

    $tong +=
    $gia * $sl;

}

/* =========================
   VAT 8%
========================= */

$vat =
$tong * 0.08;

$tong += $vat;

/* =========================
   CHECK ĐƠN HIỆN TẠI
========================= */

$sql = "

SELECT *

FROM don_hang

WHERE id_ban=?

AND trang_thai != 'da_thanh_toan'

AND trang_thai != 'da_huy'

ORDER BY id DESC

LIMIT 1

";

$stmt =
$conn->prepare($sql);

$stmt->execute([

    $id_ban

]);

$don_hien_tai =
$stmt->fetch(PDO::FETCH_ASSOC);


/* =========================
   TẠO / DÙNG LẠI ĐƠN
========================= */

if($don_hien_tai){

    /* dùng lại đơn */

    $id_don =
    $don_hien_tai['id'];

}
else{

    /* tạo đơn mới */

    $sql = "

    INSERT INTO don_hang(

        id_ban,
        tong_tien,
        trang_thai,
        so_khach,
        loai,
        id_goi_buffet

    )

    VALUES(

        ?,
        ?,
        'dang_phuc_vu',
        ?,
        ?,
        ?

    )

    ";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([

        $id_ban,
        $tong,
        $so_khach,
        $isBuffet ? 'buffet' : 'le',
        $goi

    ]);

    $id_don =
    $conn->lastInsertId();

}

/* =========================
   CHI TIẾT ĐƠN HÀNG
========================= */

foreach($gio as $id => $sl){

    if($sl <= 0){

        continue;

    }

    $sql = "
    SELECT *
    FROM mon_an
    WHERE id=?
    ";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([$id]);

    $m =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$m){

        continue;

    }

    $gia = 0;

    /* =========================
       MÓN LẺ
    ========================= */

    if(!$isBuffet){

        $gia =
        $m['gia'];

    }

    /* =========================
       NGOÀI GÓI BUFFET
    ========================= */

    else{

        if($m['goi_buffet'] > $goi){

            $gia =
            $m['gia'];

        }

    }

    /* =========================
   CHECK MÓN TỒN TẠI
========================= */

$sql = "

SELECT *

FROM chi_tiet_don_hang

WHERE id_don_hang=?

AND id_mon=?

";

$stmt =
$conn->prepare($sql);

$stmt->execute([

    $id_don,
    $id

]);

$check_mon =
$stmt->fetch(PDO::FETCH_ASSOC);

/* đã có */

if($check_mon){

    $sql = "

    UPDATE chi_tiet_don_hang

    SET
        so_luong =
        so_luong + ?

    WHERE id=?

    ";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([

        $sl,
        $check_mon['id']

    ]);

}

/* chưa có */

else{

    $sql = "

    INSERT INTO chi_tiet_don_hang(

        id_don_hang,
        id_mon,
        so_luong,
        gia,
        trang_thai

    )

    VALUES(

        ?,
        ?,
        ?,
        ?,
        'chua_gui'

    )

    ";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([

        $id_don,
        $id,
        $sl,
        $gia

    ]);

}

    /* =========================
       UPDATE TỔNG TIỀN
    ========================= */

$sql = "

UPDATE don_hang

SET tong_tien=?

WHERE id=?

";

$stmt =
$conn->prepare($sql);

$stmt->execute([

    $tong,
    $id_don

]); 

}

/* =========================
   XÓA SESSION GIỎ HÀNG
========================= */

unset($_SESSION['gio_hang']);

/* =========================
   CHUYỂN TRANG
========================= */

header(
    "Location: ../pages/trang_thai_don.php?success=1"
);

exit();

?>