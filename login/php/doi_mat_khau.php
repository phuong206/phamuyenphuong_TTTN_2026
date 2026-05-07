<?php

session_start();

require_once __DIR__ . "/../../config/db.php";

$db = new Database();

$conn = $db->connect();

/* =========================
   CHƯA LOGIN
========================= */

if(!isset($_SESSION['id_tai_khoan'])){

    header("Location: ../login.html");

    exit();

}

/* =========================
   ĐỔI MẬT KHẨU
========================= */

if(isset($_POST['doi'])){

    $mat_khau_moi =
    trim($_POST['mat_khau_moi']);

    $xac_nhan_mat_khau =
    trim($_POST['xac_nhan_mat_khau']);

    /* ===== CHECK RỖNG ===== */

    if(
        empty($mat_khau_moi)
        ||
        empty($xac_nhan_mat_khau)
    ){

        $loi =
        "Vui lòng nhập đầy đủ thông tin";

    }

    /* ===== CHECK KHỚP ===== */

    else if(
        $mat_khau_moi
        !=
        $xac_nhan_mat_khau
    ){

        $loi =
        "Mật khẩu xác nhận không khớp";

    }

    /* ===== UPDATE ===== */

    else {

        $id =
        $_SESSION['id_tai_khoan'];

        $sql = "
        UPDATE tai_khoan

        SET
            mat_khau=?,
            doi_mat_khau=0

        WHERE id=?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([

            $mat_khau_moi,
            $id

        ]);

        /* =========================
           CHUYỂN TRANG
        ========================= */

        if($_SESSION['id_vai_tro'] == 1){

            header(
                "Location: ../../admin/bang_dieu_khien.php"
            );

        } else {

            header(
                "Location: ../../staff/php/danh_sach_ban.php"
            );

        }

        exit();

    }

}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<title>Đổi mật khẩu</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{

    height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    background:
    linear-gradient(
        135deg,
        #0f172a,
        #1e293b,
        #334155
    );

    overflow:hidden;
}

/* =========================
   HIỆU ỨNG NỀN
========================= */

body::before{

    content:"";

    position:absolute;

    width:500px;
    height:500px;

    background:
    rgba(59,130,246,0.25);

    border-radius:50%;

    top:-120px;
    left:-120px;

    filter:blur(80px);
}

body::after{

    content:"";

    position:absolute;

    width:450px;
    height:450px;

    background:
    rgba(168,85,247,0.25);

    border-radius:50%;

    bottom:-120px;
    right:-120px;

    filter:blur(80px);
}

/* =========================
   BOX
========================= */

.box{

    width:430px;

    background:
    rgba(255,255,255,0.12);

    backdrop-filter:blur(15px);

    border:1px solid
    rgba(255,255,255,0.2);

    padding:40px;

    border-radius:25px;

    position:relative;

    z-index:10;

    box-shadow:
    0 10px 40px rgba(0,0,0,0.3);
}

/* =========================
   TITLE
========================= */

.logo{

    width:75px;
    height:75px;

    border-radius:20px;

    background:
    linear-gradient(
        135deg,
        #3b82f6,
        #8b5cf6
    );

    display:flex;

    justify-content:center;

    align-items:center;

    margin:0 auto 20px;

    color:white;

    font-size:30px;

    font-weight:bold;

    box-shadow:
    0 8px 20px rgba(59,130,246,0.4);
}

h1{

    text-align:center;

    color:white;

    margin-bottom:10px;

    font-size:28px;
}

.desc{

    text-align:center;

    color:#cbd5e1;

    margin-bottom:30px;

    font-size:14px;

    line-height:1.6;
}

/* =========================
   FORM
========================= */

.form-group{

    margin-bottom:22px;
}

label{

    display:block;

    margin-bottom:8px;

    color:#e2e8f0;

    font-size:14px;

    font-weight:600;
}

.form-control{

    width:100%;

    padding:14px;

    border:none;

    border-radius:14px;

    background:
    rgba(255,255,255,0.15);

    color:white;

    outline:none;

    font-size:15px;

    transition:0.25s;
}

.form-control::placeholder{

    color:#cbd5e1;
}

.form-control:focus{

    background:
    rgba(255,255,255,0.2);

    box-shadow:
    0 0 0 3px rgba(59,130,246,0.3);
}

/* =========================
   BUTTON
========================= */

.btn{

    width:100%;

    padding:14px;

    border:none;

    border-radius:14px;

    background:
    linear-gradient(
        135deg,
        #3b82f6,
        #8b5cf6
    );

    color:white;

    font-size:15px;

    font-weight:bold;

    cursor:pointer;

    transition:0.3s;

    margin-top:10px;

    box-shadow:
    0 8px 20px rgba(59,130,246,0.35);
}

.btn:hover{

    transform:translateY(-2px);

    box-shadow:
    0 12px 25px rgba(59,130,246,0.45);
}

/* =========================
   ERROR
========================= */

.error{

    background:
    rgba(239,68,68,0.2);

    border:
    1px solid rgba(239,68,68,0.3);

    color:#fecaca;

    padding:13px;

    border-radius:14px;

    margin-bottom:20px;

    font-size:14px;
}

</style>

</head>

<body>

<div class="box">

    <h1>Đổi mật khẩu</h1>

    <p class="desc">

        Vui lòng đổi mật khẩu mới để tiếp tục

    </p>

    <?php if(isset($loi)){ ?>

        <div class="error">

            <?= $loi ?>

        </div>

    <?php } ?>

    <form method="POST">

        <div class="form-group">

            <label>Mật khẩu mới</label>

            <input
                type="password"
                name="mat_khau_moi"
                class="form-control"
                placeholder="Nhập mật khẩu mới"
            >

        </div>

        <div class="form-group">

            <label>Xác nhận mật khẩu</label>

            <input
                type="password"
                name="xac_nhan_mat_khau"
                class="form-control"
                placeholder="Nhập lại mật khẩu"
            >

        </div>

        <button
            type="submit"
            name="doi"
            class="btn"
        >
            Đổi mật khẩu
        </button>

    </form>

</div>

</body>
</html>