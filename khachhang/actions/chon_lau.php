<?php

session_start();

/* =========================
   LẤY DỮ LIỆU
========================= */

$id_ban =
$_GET['id_ban']
??
$_SESSION['id_ban']
?? 0;

if($id_ban == 0){

    die("Không tìm thấy bàn");

}

$buffet =
$_GET['buffet']
?? '';

$trang_mieng =
$_GET['trang_mieng']
?? '';

?>

<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Chọn nước lẩu</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{

    background:
    linear-gradient(
        180deg,
        #f8f5f0,
        #f1ece4,
        #ebe4da
    );

    color:#2b2b2b;

    min-height:100vh;

    padding:16px;

    width:100%;

    overflow-x:hidden;
}

/* =========================
   HEADER
========================= */

.header{

    margin-bottom:25px;
}

.logo{

    font-size:30px;

    font-weight:700;

    color:#3a2e25;
}

.sub{

    margin-top:5px;

    color:#8b7355;

    font-size:14px;
}

/* =========================
   GRID
========================= */

.grid{

    display:grid;

    grid-template-columns:1fr;

    gap:18px;
}

/* =========================
   INPUT
========================= */

input{

    display:none;
}

/* =========================
   CARD
========================= */

.card{

    background:
    rgba(255,255,255,0.85);

    backdrop-filter:blur(12px);

    border:
    2px solid transparent;

    border-radius:24px;

    overflow:hidden;

    cursor:pointer;

    transition:0.25s;

    box-shadow:
    0 12px 30px rgba(0,0,0,0.05);

    position:relative;
}

.card:hover{

    transform:translateY(-3px);
}

/* checked */

input:checked + .card{

    border-color:#8b6b4a;

    box-shadow:
    0 18px 35px rgba(107,79,59,0.12);
}

/* image */

.card img{

    width:100%;

    height:clamp(150px,30vw,210px);

    object-fit:cover;

    display:block;
}
.card{

    width:100%;

    max-width:100%;
}
/* content */

.content{

    padding:18px;
}

.content h3{

    font-size:24px;

    color:#3a2e25;

    margin-bottom:8px;
}

.content p{

    color:#8b7355;

    line-height:1.6;
}

/* circle */

.circle{

    position:absolute;

    top:18px;

    right:18px;

    width:24px;

    height:24px;

    border-radius:50%;

    background:white;

    border:2px solid #d6c8ba;
}

input:checked + .card .circle{

    background:#8b6b4a;

    border-color:#8b6b4a;

    box-shadow:
    0 0 0 5px rgba(139,107,74,0.15);
}

/* button */

.btn{

    width:100%;

    margin-top:25px;

    padding:18px;

    border:none;

    border-radius:22px;

    background:
    linear-gradient(
        135deg,
        #8b6b4a,
        #6b4f3b
    );

    color:white;

    font-size:18px;

    font-weight:700;

    cursor:pointer;

    box-shadow:
    0 15px 35px rgba(107,79,59,0.15);
}

/* mobile */

@media(max-width:768px){

    body{

        padding:14px;
    }

    .logo{

        font-size:24px;
    }

    .sub{

        font-size:13px;
    }

    .grid{

        gap:14px;
    }

    .card{

        border-radius:20px;
    }

    .content{

        padding:14px;
    }

    .content h3{

        font-size:18px;
    }

    .content p{

        font-size:13px;

        line-height:1.5;
    }

    .btn{

        padding:15px;

        font-size:15px;

        border-radius:18px;
    }

}

/* iphone nhỏ */

@media(max-width:400px){

    body{

        padding:10px;
    }

    .logo{

        font-size:21px;
    }

    .sub{

        font-size:12px;
    }

    .content h3{

        font-size:16px;
    }

    .content p{

        font-size:12px;
    }

    .btn{

        font-size:14px;

        padding:14px;
    }

}
.btn{

    margin-bottom:
    calc(env(safe-area-inset-bottom) + 10px);
}
form{

    width:100%;
}
</style>

</head>

<body>

<!-- HEADER -->

<div class="header">

    <div class="logo">

        Customer_Hotpot

    </div>

    <div class="sub">

        Chọn nước lẩu - Bàn <?= $id_ban ?>

    </div>

</div>

<!-- FORM -->

<form action="menu.php" method="GET">

    <!-- hidden -->

    <input
        type="hidden"
        name="id_ban"
        value="<?= $id_ban ?>"
    >

    <input
        type="hidden"
        name="buffet"
        value="<?= $buffet ?>"
    >

    <input
        type="hidden"
        name="trang_mieng"
        value="<?= $trang_mieng ?>"
    >

    <div class="grid">

        <!-- THÁI -->

        <label>

            <input
                type="radio"
                name="lau"
                value="thai"
                required
            >

            <div class="card">

                <div class="circle"></div>

                <img
                    src="../../images/lau-thai-tomyum.jpg"
                >

                <div class="content">

                    <h3>

                        Lẩu Thái

                    </h3>

                    <p>

                        Chua cay đậm vị,
                        hải sản và bò cực hợp

                    </p>

                </div>

            </div>

        </label>

        <!-- KIMCHI -->

        <label>

            <input
                type="radio"
                name="lau"
                value="kimchi"
            >

            <div class="card">

                <div class="circle"></div>

                <img
                    src="../../images/lau-kim-chi.jpg"
                >

                <div class="content">

                    <h3>

                        Lẩu Kimchi

                    </h3>

                    <p>

                        Vị cay Hàn Quốc,
                        thơm béo nhẹ

                    </p>

                </div>

            </div>

        </label>

        <!-- MALA -->

        <label>

            <input
                type="radio"
                name="lau"
                value="mala"
            >

            <div class="card">

                <div class="circle"></div>

                <img
                    src="../../images/lau-mala.jpg"
                >

                <div class="content">

                    <h3>

                        Lẩu Mala

                    </h3>

                    <p>

                        Cay tê đậm vị Tứ Xuyên

                    </p>

                </div>

            </div>

        </label>

        <!-- NẤM -->

        <label>

            <input
                type="radio"
                name="lau"
                value="nam"
            >

            <div class="card">

                <div class="circle"></div>

                <img
                    src="../../images/lau-nam.jpg"
                >

                <div class="content">

                    <h3>

                        Lẩu Nấm

                    </h3>

                    <p>

                        Thanh ngọt tự nhiên,
                        dễ ăn

                    </p>

                </div>

            </div>

        </label>

        <!-- 2 NGĂN -->

        <label>

            <input
                type="radio"
                name="lau"
                value="2ngan"
            >

            <div class="card">

                <div class="circle"></div>

                <img
                    src="../../images/lau-2-ngan.jpg"
                >

                <div class="content">

                    <h3>

                        Lẩu 2 Ngăn

                    </h3>

                    <p>

                        Kết hợp 2 vị nước lẩu
                        trong cùng một nồi

                    </p>

                </div>

            </div>

        </label>

    </div>

    <button class="btn">

        Vào Menu

    </button>

</form>

</body>
</html>