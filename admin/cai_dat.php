<?php

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conn = $db->connect();

/* =========================
   TẠO DỮ LIỆU MẶC ĐỊNH
========================= */

$sql = "
SELECT *
FROM cai_dat
LIMIT 1
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$check =
$stmt->fetch(PDO::FETCH_ASSOC);

if(!$check){

    $sql = "
    INSERT INTO cai_dat(
        ten_nha_hang,
        so_dien_thoai,
        email,
        dia_chi,
        vat
    )
    VALUES(
        'BBQ Buffet',
        '0901234567',
        'bbq@gmail.com',
        'TP.HCM',
        10
    )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute();
}

/* =========================
   UPDATE
========================= */

if(isset($_POST['luu'])) {

    $ten_nha_hang =
    $_POST['ten_nha_hang'];

    $so_dien_thoai =
    $_POST['so_dien_thoai'];

    $email =
    $_POST['email'];

    $dia_chi =
    $_POST['dia_chi'];

    $vat =
    $_POST['vat'];

    $sql = "
    UPDATE cai_dat

    SET
        ten_nha_hang=?,
        so_dien_thoai=?,
        email=?,
        dia_chi=?,
        vat=?

    WHERE id=1
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([

        $ten_nha_hang,
        $so_dien_thoai,
        $email,
        $dia_chi,
        $vat

    ]);

    header("Location: cai_dat.php");

    exit();
}

/* =========================
   LẤY DỮ LIỆU
========================= */

$sql = "
SELECT *
FROM cai_dat
LIMIT 1
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$caiDat =
$stmt->fetch(PDO::FETCH_ASSOC);

?>

<?php include 'thanh_phan/header.php'; ?>
<?php include 'thanh_phan/sidebar.php'; ?>

<div class="main">

    <div class="top">

        <div>

            <h1>Cài đặt hệ thống</h1>

            <p>
                Quản lý thông tin nhà hàng
            </p>

        </div>

    </div>

    <div class="form-box">

        <form method="POST">

            <div class="form-group">

                <label>Tên nhà hàng</label>

                <input
                    type="text"
                    name="ten_nha_hang"
                    class="form-control"
                    value="<?=
                    $caiDat['ten_nha_hang']
                    ?>"
                >

            </div>

            <div class="form-group">

                <label>Số điện thoại</label>

                <input
                    type="text"
                    name="so_dien_thoai"
                    class="form-control"
                    value="<?=
                    $caiDat['so_dien_thoai']
                    ?>"
                >

            </div>

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?=
                    $caiDat['email']
                    ?>"
                >

            </div>

            <div class="form-group">

                <label>Địa chỉ</label>

                <textarea
                    name="dia_chi"
                    class="form-control"
                ><?=
                $caiDat['dia_chi']
                ?></textarea>

            </div>

            <div class="form-group">

                <label>VAT (%)</label>

                <input
                    type="number"
                    name="vat"
                    class="form-control"
                    value="<?=
                    $caiDat['vat']
                    ?>"
                >

            </div>

            <button
                type="submit"
                name="luu"
                class="btn btn-primary"
            >
                Lưu thay đổi
            </button>

        </form>

    </div>

</div>

<?php include 'thanh_phan/footer.php'; ?>