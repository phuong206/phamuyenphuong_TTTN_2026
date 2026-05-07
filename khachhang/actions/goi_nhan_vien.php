<?php

require_once __DIR__ . "/../../config/db.php";

$db = new Database();

$conn = $db->connect();

$id_ban =
$_POST['id_ban'] ?? 0;

if($id_ban == 0){

    exit();

}

/* =========================
   CHECK ĐÃ CÓ CHƯA
========================= */

$sql = "
SELECT *
FROM thong_bao_nhan_vien
WHERE id_ban = ?
AND trang_thai = 'cho_xu_ly'
";

$stmt = $conn->prepare($sql);

$stmt->execute([$id_ban]);

$check =
$stmt->fetch(PDO::FETCH_ASSOC);

/* =========================
   ĐÃ CÓ
========================= */

if($check){

    $sql = "
    UPDATE thong_bao_nhan_vien

    SET
        so_lan_goi = so_lan_goi + 1,
        thoi_gian = NOW()

    WHERE id = ?
    ";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([

        $check['id']

    ]);

}

/* =========================
   CHƯA CÓ
========================= */

else{

    $sql = "
    INSERT INTO thong_bao_nhan_vien(

        id_ban,
        noi_dung,
        trang_thai,
        so_lan_goi

    )

    VALUES(

        ?,
        ?,
        'cho_xu_ly',
        1

    )
    ";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([

        $id_ban,
        'Khách cần hỗ trợ'

    ]);

}

echo "success";