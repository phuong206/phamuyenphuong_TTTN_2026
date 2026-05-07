<?php

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conn = $db->connect();

/* =========================
   THÊM BÀN
========================= */

if(isset($_POST['them'])) {

    $so_ban = $_POST['so_ban'];

    $ma_qr =
    "QR_" .
    strtoupper(
        str_replace(" ", "_", $so_ban)
    );

    $sql = "
    INSERT INTO ban(
        so_ban,
        ma_qr,
        trang_thai
    )
    VALUES(
        ?, ?, 1
    )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $so_ban,
        $ma_qr
    ]);

    header("Location: ban_an.php");

    exit();
}

/* =========================
   XÓA BÀN
========================= */

if(isset($_GET['xoa'])) {

    $id = $_GET['xoa'];

    $sql = "
    DELETE FROM ban
    WHERE id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([$id]);

    header("Location: ban_an.php");

    exit();
}

/* =========================
   SỬA BÀN
========================= */

if(isset($_POST['sua'])) {

    $id = $_POST['id'];

    $so_ban = $_POST['so_ban'];

    $sql = "
    UPDATE ban

    SET so_ban=?

    WHERE id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $so_ban,
        $id
    ]);

    header("Location: ban_an.php");

    exit();
}

/* =========================
   DANH SÁCH BÀN
========================= */

$sql = "
SELECT 
    b.*,
    (
        SELECT COUNT(*) 
        FROM don_hang d 
        WHERE d.id_ban = b.id 
        AND d.trang_thai != 'da_thanh_toan'
        AND d.trang_thai != 'da_huy'
    ) as co_khach,
    (
        SELECT GROUP_CONCAT(CONCAT(d.so_khach, ' khách') SEPARATOR ', ')
        FROM don_hang d 
        WHERE d.id_ban = b.id 
        AND d.trang_thai != 'da_thanh_toan'
        AND d.trang_thai != 'da_huy'
    ) as danh_sach_khach
FROM ban b
ORDER BY b.id ASC
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$dsBan = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'thanh_phan/header.php'; ?>
<?php include 'thanh_phan/sidebar.php'; ?>

<div class="main">

    <div class="top">

        <div>

            <h1>Bàn ăn</h1>

            <p>
                Quản lý bàn trong nhà hàng
            </p>

        </div>

        <button
            class="btn btn-primary"
            onclick="openModal()"
        >
            + Thêm bàn
        </button>

    </div>

    <!-- MODAL THÊM -->

    <div class="modal" id="modalThem">

        <div class="modal-content">

            <div class="modal-header">

                <h2>Thêm bàn</h2>

                <span
                    class="close"
                    onclick="closeModal()"
                >
                    &times;
                </span>

            </div>

            <form method="POST">

                <div class="form-group">

                    <label>Số bàn</label>

                    <input
                        type="text"
                        name="so_ban"
                        class="form-control"
                        placeholder="Ví dụ: B13"
                        required
                    >

                </div>

                <button
                    type="submit"
                    name="them"
                    class="btn btn-primary"
                >
                    Thêm bàn
                </button>

            </form>

        </div>

    </div>

    <!-- DANH SÁCH -->

    <div class="cards">

        <?php foreach($dsBan as $row) { ?>

        <?php

        $trangThai = "Trống";
        $class = "hoan-thanh";

        if($row['co_khach'] > 0){

            $trangThai = "Có khách";
            $class = "dang-cho";

        }

        if($row['trang_thai'] == 3){

            $trangThai = "Đặt trước";
            $class = "btn-warning";

        }

        ?>

        <div class="card">

            <h3>

                <?= $row['so_ban'] ?>

            </h3>

            <p>

                <span class="badge <?= $class ?>">

                    <?= $trangThai ?>

                </span>

            </p>

            <br>

            <?php if($row['danh_sach_khach']): ?>
                <p style="color: #666; font-size: 14px;">
                    👥 <?= htmlspecialchars($row['danh_sach_khach']) ?>
                </p>
            <?php endif; ?>

            <p>

                QR:
                <?= $row['ma_qr'] ?>

            </p>

            <br>

            <button
                class="btn btn-warning"
                onclick="openEditModal(
                    '<?= $row['id'] ?>',
                    '<?= $row['so_ban'] ?>'
                )"
            >
                Sửa
            </button>

            <a
                href="?xoa=<?= $row['id'] ?>"
                class="btn btn-danger"
                onclick="return confirm('Xóa bàn này?')"
            >
                Xóa
            </a>

        </div>

        <?php } ?>

    </div>

</div>

<!-- MODAL SỬA -->

<div class="modal" id="modalSua">

    <div class="modal-content">

        <div class="modal-header">

            <h2>Sửa bàn</h2>

            <span
                class="close"
                onclick="closeEditModal()"
            >
                &times;
            </span>

        </div>

        <form method="POST">

            <input
                type="hidden"
                name="id"
                id="edit_id"
            >

            <div class="form-group">

                <label>Số bàn</label>

                <input
                    type="text"
                    name="so_ban"
                    id="edit_so_ban"
                    class="form-control"
                    required
                >

            </div>

            <button
                type="submit"
                name="sua"
                class="btn btn-primary"
            >
                Lưu thay đổi
            </button>

        </form>

    </div>

</div>

<script>

/* =========================
   MODAL THÊM
========================= */

function openModal(){

    document
    .getElementById("modalThem")
    .style.display = "block";

}

function closeModal(){

    document
    .getElementById("modalThem")
    .style.display = "none";

}

/* =========================
   MODAL SỬA
========================= */

function openEditModal(id, so_ban){

    document
    .getElementById("modalSua")
    .style.display = "block";

    document
    .getElementById("edit_id")
    .value = id;

    document
    .getElementById("edit_so_ban")
    .value = so_ban;
}

function closeEditModal(){

    document
    .getElementById("modalSua")
    .style.display = "none";

}

/* =========================
   CLICK NGOÀI MODAL
========================= */

window.onclick = function(event){

    let modalThem =
    document.getElementById("modalThem");

    let modalSua =
    document.getElementById("modalSua");

    if(event.target == modalThem){

        modalThem.style.display = "none";

    }

    if(event.target == modalSua){

        modalSua.style.display = "none";

    }
}

</script>

<?php include 'thanh_phan/footer.php'; ?>