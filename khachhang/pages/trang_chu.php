<?php

session_start();

/* =========================
   LẤY ID BÀN
========================= */

if(isset($_GET['id_ban'])){

    $_SESSION['id_ban'] =
    $_GET['id_ban'];

}

$id_ban =
$_SESSION['id_ban'] ?? 0;

?>

<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Trang chủ khách hàng</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{

    min-height:100vh;

    background:
    linear-gradient(
        135deg,
        #f8f5f0,
        #f1ece4,
        #ebe4da
    );

    overflow:hidden;
}

/* =========================
   BACKGROUND DECOR
========================= */

.blur1{

    position:absolute;

    width:450px;

    height:450px;

    border-radius:50%;

    background:
    rgba(139,107,74,0.12);

    filter:blur(80px);

    top:-120px;

    left:-120px;
}

.blur2{

    position:absolute;

    width:400px;

    height:400px;

    border-radius:50%;

    background:
    rgba(212,163,115,0.12);

    filter:blur(80px);

    bottom:-120px;

    right:-120px;
}

/* =========================
   CONTAINER
========================= */

.wrapper{

    position:relative;

    z-index:2;

    width:100%;

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    padding:30px;
}

.container{

    width:100%;

    max-width:520px;

    background:
    rgba(255,255,255,0.78);

    backdrop-filter:blur(18px);

    border:
    1px solid rgba(255,255,255,0.5);

    border-radius:35px;

    padding:45px;

    box-shadow:
    0 25px 60px rgba(0,0,0,0.08);

    text-align:center;
}

/* =========================
   TEXT
========================= */

.logo{

    font-size:50px;

    margin-bottom:10px;
}

h1{

    color:#3a2e25;

    font-size:38px;

    margin-bottom:10px;
}

.sub{

    color:#8b7355;

    margin-bottom:35px;

    font-size:15px;
}

/* =========================
   TABLE
========================= */

.table-box{

    background:
    linear-gradient(
        135deg,
        rgba(139,107,74,0.08),
        rgba(107,79,59,0.08)
    );

    padding:18px;

    border-radius:20px;

    margin-bottom:30px;

    color:#6b4f3b;

    font-weight:700;

    font-size:18px;
}

/* =========================
   BUTTON
========================= */

.btn{

    display:flex;

    align-items:center;

    justify-content:center;

    gap:12px;

    width:100%;

    padding:18px;

    margin-bottom:18px;

    border-radius:22px;

    text-decoration:none;

    color:white;

    font-size:18px;

    font-weight:700;

    transition:0.3s;

    box-shadow:
    0 12px 30px rgba(0,0,0,0.08);
}

.btn:hover{

    transform:
    translateY(-3px)
    scale(1.01);
}

/* buffet */

.buffet{

    background:
    linear-gradient(
        135deg,
        #8b6b4a,
        #6b4f3b
    );
}

/* món lẻ */

.monle{

    background:
    linear-gradient(
        135deg,
        #d4a373,
        #b08968
    );
}

/* icon */

.icon{

    font-size:24px;
}

/* =========================
   MOBILE
========================= */

@media(max-width:600px){

    .container{

        padding:30px;
    }

    h1{

        font-size:30px;
    }

    .btn{

        font-size:16px;

        padding:16px;
    }

}

</style>

</head>

<body>

<div class="blur1"></div>

<div class="blur2"></div>

<div class="wrapper">

    <div class="container">

        <div class="logo">

            🍽️

        </div>

        <h1>

            Welcome

        </h1>

        <div class="sub">

            Hệ thống gọi món thông minh

        </div>

        <div class="table-box">

            Bàn <?= $id_ban ?>

        </div>

        <!-- BUFFET -->

        <a
            href="
            chon_buffet.php?id_ban=<?= $id_ban ?>"
            class="btn buffet"
        >

            <span class="icon">

                🍱

            </span>

            Buffet

        </a>

        <!-- MÓN LẺ -->

        <a
            href="
            menu.php?id_ban=<?= $id_ban ?>&mon_le=1"
            class="btn monle"
        >

            <span class="icon">

                🍜

            </span>

            Món lẻ

        </a>

    </div>

</div>

</body>
</html>