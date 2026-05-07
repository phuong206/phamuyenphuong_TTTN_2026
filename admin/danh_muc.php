<?php

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conn = $db->connect();

/* =========================
   THÊM
========================= */

if(isset($_POST['them'])) {

    $ten_loai = $_POST['ten_loai'];

    $sql = "
    INSERT INTO loai_mon(
        ten_loai
    )
    VALUES(?)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([$ten_loai]);

    header("Location: danh_muc.php");
    exit();
}

/* =========================
   XÓA
========================= */

if(isset($_GET['xoa'])) {

    $id = $_GET['xoa'];

    $sql = "
    DELETE FROM loai_mon
    WHERE id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([$id]);

    header("Location: danh_muc.php");
    exit();
}

/* =========================
   SỬA
========================= */

if(isset($_POST['sua'])) {

    $id = $_POST['id'];

    $ten_loai = $_POST['ten_loai'];

    $sql = "
    UPDATE loai_mon

    SET ten_loai=?

    WHERE id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $ten_loai,
        $id
    ]);

    header("Location: danh_muc.php");
    exit();
}

/* =========================
   DANH SÁCH
========================= */

$sql = "
SELECT 
    loai_mon.*,

    (
        SELECT COUNT(*)
        FROM mon_an
        WHERE mon_an.id_loai = loai_mon.id
    ) as tong_mon

FROM loai_mon

ORDER BY id DESC
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$dsLoai = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'thanh_phan/header.php'; ?>
<?php include 'thanh_phan/sidebar.php'; ?>

<div class="main">

    <div class="top">

        <div>

            <h1>Danh mục</h1>

            <p>Quản lý loại món ăn</p>

        </div>

        <button
            class="btn btn-primary"
            onclick="openModal()"
        >
            + Thêm danh mục
        </button>

    </div>

    <!-- MODAL THÊM -->

    <div class="modal" id="modalThem">

        <div class="modal-content">

            <div class="modal-header">

                <h2>Thêm danh mục</h2>

                <span
                    class="close"
                    onclick="closeModal()"
                >
                    &times;
                </span>

            </div>

            <form method="POST">

                <div class="form-group">

                    <label>Tên danh mục</label>

                    <input
                        type="text"
                        name="ten_loai"
                        class="form-control"
                        required
                    >

                </div>

                <button
                    type="submit"
                    name="them"
                    class="btn btn-primary"
                >
                    Thêm danh mục
                </button>

            </form>

        </div>

    </div>

    <!-- DANH SÁCH -->

    <div class="cards">

        <?php foreach($dsLoai as $row) { ?>

        <div class="card">

            <h3>

                <?= $row['ten_loai'] ?>

            </h3>

            <p>

                <?= $row['tong_mon'] ?> món

            </p>

            <br>

            <!-- NÚT SỬA -->

            <button
                class="btn btn-warning"
                onclick="openEditModal(
                    '<?= $row['id'] ?>',
                    '<?= $row['ten_loai'] ?>'
                )"
            >
                Sửa
            </button>

            <!-- XÓA -->

            <a
                href="?xoa=<?= $row['id'] ?>"
                class="btn btn-danger"
                onclick="return confirm('Xóa danh mục này?')"
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

            <h2>Sửa danh mục</h2>

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

                <label>Tên danh mục</label>

                <input
                    type="text"
                    name="ten_loai"
                    id="edit_ten_loai"
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

function openEditModal(id, ten){

    document
    .getElementById("modalSua")
    .style.display = "block";

    document
    .getElementById("edit_id")
    .value = id;

    document
    .getElementById("edit_ten_loai")
    .value = ten;
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