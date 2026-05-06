<?php
require_once __DIR__ . "/../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id_ban = $_GET['id_ban'] ?? 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm khách</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: radial-gradient(circle at top, #1e3a8a, #0f172a 70%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.box {
    width: 420px;
    background: #f8fafc;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}

.title {
    font-weight: 600;
    font-size: 18px;
    color: #0f172a;
}

.subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 20px;
}

label {
    font-size: 14px;
    color: #334155;
}

input {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
}

.goi {
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: 0.2s;
}

.goi:hover {
    border: 1px solid #2563eb;
}

.goi.active {
    border: 2px solid #1e3a8a;
    background: #eff6ff;
}

.price {
    font-size: 13px;
    color: #64748b;
}

.tong {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.btn-group {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn {
    flex: 1;
    padding: 12px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #0f172a;
    color: white;
}

.btn-outline {
    border: 1px solid #cbd5e1;
    background: white;
}
</style>
</head>

<body>

<div class="box">
<div class="title">Thêm khách mới</div>
<div class="subtitle">Bàn <?= htmlspecialchars($id_ban) ?></div>

<form action="../xu_ly/tao_don.php" method="POST">

<input type="hidden" name="id_ban" value="<?= $id_ban ?>">

<label>Số khách</label>
<input type="number" name="so_khach" id="so_khach" value="2" min="1">

<!-- 🔥 LOẠI -->
<label>Loại phục vụ</label>

<div class="goi loai active" data-loai="buffet">
    <input type="radio" name="loai" value="buffet" checked hidden>
    <b>Buffet</b>
    <div class="price">Ăn theo gói</div>
</div>

<div class="goi loai" data-loai="le">
    <input type="radio" name="loai" value="le" hidden>
    <b>Món lẻ</b>
    <div class="price">Gọi từng món</div>
</div>

<!-- 🔥 TRÁNG MIỆNG -->
<div class="goi trangmieng">
    <input type="checkbox" name="trang_mieng" hidden>
    <b>Buffet tráng miệng</b>
    <div class="price">49,000đ/người</div>
</div>

<!-- ===== ĐẶT TRƯỚC ===== -->
<label>Hình thức</label>

<div class="goi dat_truoc">
    <input type="checkbox" name="dat_truoc" hidden>
    <b>Đặt trước</b>
    <div class="price">Khách đến sau</div>
</div>

<div id="thoi_gian_box" style="display:none;">
    <label>Thời gian đến</label>
    <input type="datetime-local" name="thoi_gian_den">
</div>

<!-- 🔥 BUFFET -->
<div id="buffet_section">

<label>Chọn gói buffet</label>

<div class="goi goi-buffet active" data-gia="299000">
    <input type="radio" name="goi_buffet" value="1" checked hidden>
    <b>Gói tiêu chuẩn</b>
    <div class="price">299,000đ/người</div>
</div>

<div class="goi goi-buffet" data-gia="399000">
    <input type="radio" name="goi_buffet" value="2" hidden>
    <b>Gói cao cấp</b>
    <div class="price">399,000đ/người</div>
</div>

<div class="goi goi-buffet" data-gia="499000">
    <input type="radio" name="goi_buffet" value="3" hidden>
    <b>Gói VIP</b>
    <div class="price">499,000đ/người</div>
</div>

</div>

<div class="tong">
    <span>Tạm tính:</span>
    <b id="tong">0đ</b>
</div>

<div class="btn-group">
    <button class="btn btn-primary">Xác nhận</button>
    <button type="button" class="btn btn-outline" onclick="history.back()">Hủy</button>
</div>

</form>
</div>

<script>
const soKhach = document.getElementById("so_khach");
const tongEl = document.getElementById("tong");
const buffetSection = document.getElementById("buffet_section");

const goiBuffet = document.querySelectorAll(".goi-buffet");
const loaiList = document.querySelectorAll(".loai");
const trangMieng = document.querySelector(".trangmieng");
const datTruoc = document.querySelector(".dat_truoc");
const timeBox = document.getElementById("thoi_gian_box");

let loai = "buffet";
let giaBuffet = 299000;
let giaTrang = 49000;

function format(n){
    return n.toLocaleString('vi-VN') + "đ";
}

function tinhTien(){
    let sk = parseInt(soKhach.value) || 1;
    let tong = 0;

    if(loai === "buffet"){
        tong += sk * giaBuffet;
    }

    if(trangMieng.classList.contains("active")){
        tong += sk * giaTrang;
    }

    tongEl.innerText = format(tong);
}

/* chọn loại */
loaiList.forEach(el=>{
    el.onclick = ()=>{
        loaiList.forEach(x=>x.classList.remove("active"));
        el.classList.add("active");

        el.querySelector("input").checked = true;
        loai = el.dataset.loai;

        buffetSection.style.display = (loai==="buffet")?"block":"none";

        tinhTien();
    }
});

/* chọn buffet */
goiBuffet.forEach(el=>{
    el.onclick = ()=>{
        goiBuffet.forEach(x=>x.classList.remove("active"));
        el.classList.add("active");

        el.querySelector("input").checked = true;
        giaBuffet = parseInt(el.dataset.gia);

        tinhTien();
    }
});

/* chọn tráng miệng */
trangMieng.onclick = ()=>{
    trangMieng.classList.toggle("active");
    trangMieng.querySelector("input").checked =
        trangMieng.classList.contains("active");

    tinhTien();
};

/* đặt trước */
datTruoc.onclick = ()=>{
    datTruoc.classList.toggle("active");

    let checkbox = datTruoc.querySelector("input");
    checkbox.checked = !checkbox.checked;

    timeBox.style.display = checkbox.checked ? "block" : "none";
};
soKhach.oninput = tinhTien;

tinhTien();
</script>

</body>
</html>