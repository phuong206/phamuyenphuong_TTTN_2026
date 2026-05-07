<?php

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conn = $db->connect();

/* =========================
   THÊM MÓN
========================= */

if (isset($_POST['them'])) {

    $ten_mon = $_POST['ten_mon'];
    $gia = $_POST['gia'];
    $mo_ta = $_POST['mo_ta'];
    $id_loai = $_POST['id_loai'];
    $goi_buffet = $_POST['goi_buffet'];

    $hinh_anh = "";

    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['name'] != "") {

        $hinh_anh =
            time() . "_" . $_FILES['hinh_anh']['name'];

        move_uploaded_file(
            $_FILES['hinh_anh']['tmp_name'],
            "../images/" . $hinh_anh
        );
    }

    $sql = "
    INSERT INTO mon_an(
        ten_mon,
        gia,
        mo_ta,
        hinh_anh,
        id_loai,
        trang_thai,
        goi_buffet
    )
    VALUES(
        ?, ?, ?, ?, ?, 1, ?
    )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $ten_mon,
        $gia,
        $mo_ta,
        $hinh_anh,
        $id_loai,
        $goi_buffet
    ]);

    header("Location: mon_an.php");
    exit();
}
/* =========================
   SỬA MÓN
========================= */

if (isset($_POST['sua'])) {

    $id = $_POST['id'];

    $ten_mon = $_POST['ten_mon'];

    $gia = $_POST['gia'];

    $mo_ta = $_POST['mo_ta'];

    $id_loai = $_POST['id_loai'];

    $goi_buffet = $_POST['goi_buffet'];

    /* GIỮ ẢNH CŨ */

    $hinh_anh = $_POST['hinh_anh_cu'];

    /* NẾU UP ẢNH MỚI */

    if (
        isset($_FILES['hinh_anh'])
        &&
        $_FILES['hinh_anh']['name'] != ""
    ) {

        $hinh_anh =
            time() . "_" .
            $_FILES['hinh_anh']['name'];

        move_uploaded_file(

            $_FILES['hinh_anh']['tmp_name'],

            "../images/" . $hinh_anh

        );
    }

    $sql = "
    UPDATE mon_an

    SET
        ten_mon=?,
        gia=?,
        mo_ta=?,
        hinh_anh=?,
        id_loai=?,
        goi_buffet=?

    WHERE id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([

        $ten_mon,
        $gia,
        $mo_ta,
        $hinh_anh,
        $id_loai,
        $goi_buffet,
        $id

    ]);

    header("Location: mon_an.php");

    exit();
}
/* =========================
   XÓA
========================= */

if (isset($_GET['xoa'])) {

    $id = $_GET['xoa'];

    $sql = "DELETE FROM mon_an WHERE id=?";

    $stmt = $conn->prepare($sql);

    $stmt->execute([$id]);

    header("Location: mon_an.php");
    exit();
}

/* =========================
   DANH SÁCH MÓN
========================= */

$sql = "
SELECT 
    mon_an.*,
    loai_mon.ten_loai

FROM mon_an

JOIN loai_mon
ON mon_an.id_loai = loai_mon.id

ORDER BY mon_an.id DESC
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$dsMon = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   LOẠI MÓN
========================= */

$sqlLoai = "SELECT * FROM loai_mon";

$stmtLoai = $conn->prepare($sqlLoai);

$stmtLoai->execute();

$dsLoai = $stmtLoai->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'thanh_phan/header.php'; ?>
<?php include 'thanh_phan/sidebar.php'; ?>

<div class="main">

    <div class="top">

        <div>

            <h1>Quản lý món ăn</h1>

            <p>Danh sách món ăn trong hệ thống</p>

        </div>

        <button
            class="btn btn-primary"
            onclick="openModal()">
            + Thêm món mới
        </button>

    </div>

    <!-- FORM -->

    <!-- MODAL -->

    <div class="modal" id="modalThem">

        <div class="modal-content">

            <div class="modal-header">

                <h2>Thêm món ăn</h2>

                <span
                    class="close"
                    onclick="closeModal()">
                    &times;
                </span>

            </div>

            <form
                method="POST"
                enctype="multipart/form-data">

                <div class="form-group">

                    <label>Tên món</label>

                    <input
                        type="text"
                        name="ten_mon"
                        class="form-control"
                        required>

                </div>

                <div class="form-group">

                    <label>Loại món</label>

                    <select
                        name="id_loai"
                        class="form-control">

                        <?php foreach ($dsLoai as $loai) { ?>

                            <option value="<?= $loai['id'] ?>">

                                <?= $loai['ten_loai'] ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="form-group">

                    <label>Giá</label>

                    <input
                        type="number"
                        name="gia"
                        class="form-control"
                        required>

                </div>

                <div class="form-group">

                    <label>Mô tả</label>

                    <textarea
                        name="mo_ta"
                        class="form-control"></textarea>

                </div>

                <div class="form-group">

                    <label>Gói buffet</label>

                    <select
                        name="goi_buffet"
                        class="form-control">

                        <option value="0">
                            Món lẻ
                        </option>

                        <option value="299">
                            Buffet 299
                        </option>

                        <option value="399">
                            Buffet 399
                        </option>

                        <option value="499">
                            Buffet 499
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label>Hình ảnh</label>

                    <input
                        type="file"
                        name="hinh_anh"
                        class="form-control">

                </div>

                <button
                    type="submit"
                    name="them"
                    class="btn btn-primary">
                    Thêm món
                </button>

            </form>

        </div>

    </div>

    <br>

    <!-- TABLE -->

    <div class="table-box">

        <table>

            <tr>

                <th>Hình</th>
                <th>Tên món</th>
                <th>Loại</th>
                <th>Giá</th>
                <th>Buffet</th>
                <th></th>

            </tr>

            <?php foreach ($dsMon as $row) { ?>

                <tr>

                    <td>

                        <img
                            src="../images/<?= $row['hinh_anh'] ?>"
                            class="food-image">

                    </td>

                    <td>
                        <?= $row['ten_mon'] ?>
                    </td>

                    <td>
                        <?= $row['ten_loai'] ?>
                    </td>

                    <td>
                        <?= number_format($row['gia']) ?>đ
                    </td>

                    <td>

                        <?php

                        if ($row['goi_buffet'] == 0) {

                            echo "Món lẻ";
                        } else {

                            echo $row['goi_buffet'];
                        }

                        ?>

                    </td>

                    <td>

                        <button
                            class="btn btn-warning"
                            onclick="openEditModal(
            '<?= $row['id'] ?>',
            '<?= $row['ten_mon'] ?>',
            '<?= $row['gia'] ?>',
            '<?= $row['mo_ta'] ?>',
            '<?= $row['id_loai'] ?>',
            '<?= $row['goi_buffet'] ?>',
            '<?= $row['hinh_anh'] ?>'
        )">
                            Sửa
                        </button>

                        <a
                            href="?xoa=<?= $row['id'] ?>"
                            class="btn btn-danger"
                            onclick="return confirm('Xóa món này?')">
                            Xóa
                        </a>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>
<!-- MODAL SỬA -->

<div class="modal" id="modalSua">

    <div class="modal-content">

        <div class="modal-header">

            <h2>Sửa món ăn</h2>

            <span
                class="close"
                onclick="closeEditModal()"
            >
                &times;
            </span>

        </div>

        <form
            method="POST"
            enctype="multipart/form-data"
        >

            <input
                type="hidden"
                name="id"
                id="edit_id"
            >

            <input
                type="hidden"
                name="hinh_anh_cu"
                id="edit_hinh_cu"
            >

            <div class="form-group">

                <label>Tên món</label>

                <input
                    type="text"
                    name="ten_mon"
                    id="edit_ten_mon"
                    class="form-control"
                >

            </div>

            <div class="form-group">

                <label>Loại món</label>

                <select
                    name="id_loai"
                    id="edit_id_loai"
                    class="form-control"
                >

                    <?php foreach($dsLoai as $loai){ ?>

                    <option value="<?= $loai['id'] ?>">

                        <?= $loai['ten_loai'] ?>

                    </option>

                    <?php } ?>

                </select>

            </div>

            <div class="form-group">

                <label>Giá</label>

                <input
                    type="number"
                    name="gia"
                    id="edit_gia"
                    class="form-control"
                >

            </div>

            <div class="form-group">

                <label>Mô tả</label>

                <textarea
                    name="mo_ta"
                    id="edit_mo_ta"
                    class="form-control"
                ></textarea>

            </div>

            <div class="form-group">

                <label>Gói buffet</label>

                <select
                    name="goi_buffet"
                    id="edit_goi"
                    class="form-control"
                >

                    <option value="0">
                        Món lẻ
                    </option>

                    <option value="299">
                        Buffet 299
                    </option>

                    <option value="399">
                        Buffet 399
                    </option>

                    <option value="499">
                        Buffet 499
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Đổi ảnh</label>

                <input
                    type="file"
                    name="hinh_anh"
                    class="form-control"
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

function openEditModal(
    id,
    ten,
    gia,
    mo_ta,
    id_loai,
    goi,
    hinh
){

    document
    .getElementById("modalSua")
    .style.display = "block";

    document
    .getElementById("edit_id")
    .value = id;

    document
    .getElementById("edit_ten_mon")
    .value = ten;

    document
    .getElementById("edit_gia")
    .value = gia;

    document
    .getElementById("edit_mo_ta")
    .value = mo_ta;

    document
    .getElementById("edit_id_loai")
    .value = id_loai;

    document
    .getElementById("edit_goi")
    .value = goi;

    document
    .getElementById("edit_hinh_cu")
    .value = hinh;
}

function closeEditModal(){

    document
    .getElementById("modalSua")
    .style.display = "none";

}

</script>
<?php include 'thanh_phan/footer.php'; ?>