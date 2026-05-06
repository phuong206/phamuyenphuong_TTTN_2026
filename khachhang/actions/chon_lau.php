<?php
$buffet = $_GET['buffet'] ?? '';
$trang_mieng = $_GET['trang_mieng'] ?? '';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            background: #020617;
            color: white;
            font-family: sans-serif;
            padding: 15px;
        }

        h2 {
            text-align: center;
            color: #38bdf8;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .card {
            background: #1e293b;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card p {
            padding: 10px;
        }

        input {
            display: none;
        }

        input:checked+.card {
            border-color: #38bdf8;
            box-shadow: 0 0 10px #38bdf8;
        }

        .btn {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background: #38bdf8;
            color: black;
        }
    </style>
</head>

<body>

    <h2>Chọn Nước Lẩu</h2>

    <form action="../pages/menu.php" method="GET">

        <input type="hidden" name="buffet" value="<?= $buffet ?>">
        <input type="hidden" name="trang_mieng" value="<?= $trang_mieng ?>">

        <div class="grid">

            <label>
                <input type="radio" name="lau" value="thai" required>
                <div class="card">
                    <img src="../../images/lau-thai-tomyum.jpg">
                    <p>Lẩu Thái</p>
                </div>
            </label>

            <label>
                <input type="radio" name="lau" value="kimchi">
                <div class="card">
                    <img src="../../images/lau-kim-chi.jpg">
                    <p>Lẩu Kimchi</p>
                </div>
            </label>

            <label>
                <input type="radio" name="lau" value="mala">
                <div class="card">
                    <img src="../../images/lau-mala.jpg">
                    <p>Lẩu Mala</p>
                </div>
            </label>

            <label>
                <input type="radio" name="lau" value="nam">
                <div class="card">
                    <img src="../../images/lau-nam.jpg">
                    <p>Lẩu Nấm</p>
                </div>
            </label>

            <label>
                <input type="radio" name="lau" value="2ngan">
                <div class="card">
                    <img src="../../images/lau-2-ngan.jpg">
                    <p>Lẩu 2 ngăn</p>
                </div>
            </label>

        </div>

        <button class="btn">Vào Menu</button>

    </form>

</body>

</html>