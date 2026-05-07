<?php

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conn = $db->connect();

/* =========================
   THÊM NHÂN VIÊN
========================= */

if (isset($_POST['them'])) {

    $ho_ten = $_POST['ho_ten'];

    $ten_dang_nhap = $_POST['ten_dang_nhap'];

    $mat_khau = password_hash(
        $_POST['mat_khau'],
        PASSWORD_DEFAULT
    );

    $so_dien_thoai = $_POST['so_dien_thoai'];

    $id_vai_tro = $_POST['id_vai_tro'];

    $trang_thai = $_POST['trang_thai'];

    $sql = "
    INSERT INTO tai_khoan(
        ho_ten,
        ten_dang_nhap,
        mat_khau,
        so_dien_thoai,
        id_vai_tro,
        trang_thai
    )
    VALUES(
        ?, ?, ?, ?, ?, ?
    )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([

        $ho_ten,
        $ten_dang_nhap,
        $mat_khau,
        $so_dien_thoai,
        $id_vai_tro,
        $trang_thai

    ]);

    header("Location: nhan_vien.php");

    exit();
}

/* =========================
   XÓA
========================= */

if (isset($_GET['xoa'])) {

    $id = $_GET['xoa'];

    $sql = "
    DELETE FROM tai_khoan
    WHERE id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([$id]);

    header("Location: nhan_vien.php");

    exit();
}

/* =========================
   SỬA
========================= */

if (isset($_POST['sua'])) {

    $id = $_POST['id'];

    $ho_ten = $_POST['ho_ten'];

    $so_dien_thoai =
        $_POST['so_dien_thoai'];

    $id_vai_tro = $_POST['id_vai_tro'];

    $trang_thai =
        $_POST['trang_thai'];

    $sql = "
    UPDATE tai_khoan

    SET
        ho_ten=?,
        so_dien_thoai=?,
        id_vai_tro=?,
        trang_thai=?

    WHERE id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([

        $ho_ten,
        $so_dien_thoai,
        $id_vai_tro,
        $trang_thai,
        $id

    ]);

    header("Location: nhan_vien.php");

    exit();
}
/* =========================
   DANH SÁCH VAI TRÒ
========================= */

$sql = "SELECT * FROM vai_tro";

$stmt = $conn->prepare($sql);

$stmt->execute();

$dsVaiTro =
    $stmt->fetchAll(PDO::FETCH_ASSOC);
/* =========================
   DANH SÁCH
========================= */

$sql = "
SELECT
    tai_khoan.*,
    vai_tro.ten_vai_tro

FROM tai_khoan

LEFT JOIN vai_tro
ON tai_khoan.id_vai_tro = vai_tro.id

ORDER BY tai_khoan.id DESC
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$dsNV = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'thanh_phan/header.php'; ?>
<?php include 'thanh_phan/sidebar.php'; ?>

<div class="main">

    <div class="top">

        <div>

            <h1>Nhân viên</h1>

            <p>
                Quản lý tài khoản hệ thống
            </p>

        </div>

        <button
            class="btn btn-primary"
            onclick="openModal()">
            + Thêm nhân viên
        </button>

    </div>

    <div class="table-box">

        <table class="order-table">

            <tr>

                <th>Họ tên</th>
                <th>Tài khoản</th>
                <th>Vai trò</th>
                <th>SĐT</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>

            </tr>

            <?php foreach ($dsNV as $row) { ?>

                <tr>

                    <td>

                        <?= $row['ho_ten'] ?>

                    </td>

                    <td>

                        <?= $row['ten_dang_nhap'] ?>

                    </td>

                    <td>

                        <?= $row['ten_vai_tro'] ?>

                    </td>

                    <td>

                        <?= $row['so_dien_thoai'] ?>

                    </td>

                    <td>

                        <?php

                        $class = "hoan-thanh";
                        $text = "Hoạt động";

                        if (
                            $row['trang_thai']
                            == 'khoa'
                        ) {

                            $class = "da-huy";
                            $text = "Đã khóa";
                        }

                        ?>

                        <span class="badge <?= $class ?>">

                            <?= $text ?>

                        </span>

                    </td>

                    <td>

                        <button
                            class="btn btn-warning"
                            onclick="openEditModal(
                            '<?= $row['id'] ?>',
                            '<?= $row['ho_ten'] ?>',
                            '<?= $row['so_dien_thoai'] ?>',
                            '<?= $row['ten_vai_tro'] ?>',
                            '<?= $row['trang_thai'] ?>'
                        )">
                            Sửa
                        </button>

                        <a
                            href="?xoa=<?= $row['id'] ?>"
                            class="btn btn-danger"
                            onclick="
                        return confirm(
                            'Xóa nhân viên này?'
                        )
                        ">
                            Xóa
                        </a>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

<!-- MODAL THÊM -->

<div class="modal" id="modalThem">

    <div class="modal-content">

        <div class="modal-header">

            <h2>Thêm nhân viên</h2>

            <span
                class="close"
                onclick="closeModal()">
                &times;
            </span>

        </div>

        <form method="POST">

            <div class="form-group">

                <label>Họ tên</label>

                <input
                    type="text"
                    name="ho_ten"
                    class="form-control"
                    required>

            </div>

            <div class="form-group">

                <label>Tên đăng nhập</label>

                <input
                    type="text"
                    name="ten_dang_nhap"
                    class="form-control"
                    required>

            </div>

            <div class="form-group">

                <label>Mật khẩu</label>

                <input
                    type="password"
                    name="mat_khau"
                    class="form-control"
                    required>

            </div>

            <div class="form-group">

                <label>Số điện thoại</label>

                <input
                    type="text"
                    name="so_dien_thoai"
                    class="form-control">

            </div>

            <div class="form-group">

                <label>Vai trò</label>

                <select
                    name="id_vai_tro"
                    class="form-control">

                    <?php foreach ($dsVaiTro as $vt) { ?>

                        <option value="<?= $vt['id'] ?>">

                            <?= $vt['ten_vai_tro'] ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="form-group">

                <label>Trạng thái</label>

                <select
                    name="trang_thai"
                    class="form-control">

                    <option value="hoat_dong">
                        Hoạt động
                    </option>

                    <option value="khoa">
                        Khóa
                    </option>

                </select>

            </div>

            <button
                type="submit"
                name="them"
                class="btn btn-primary">
                Thêm nhân viên
            </button>

        </form>

    </div>

</div>

<!-- MODAL SỬA -->

<div class="modal" id="modalSua">

    <div class="modal-content">

        <div class="modal-header">

            <h2>Sửa nhân viên</h2>

            <span
                class="close"
                onclick="closeEditModal()">
                &times;
            </span>

        </div>

        <form method="POST">

            <input
                type="hidden"
                name="id"
                id="edit_id">

            <div class="form-group">

                <label>Họ tên</label>

                <input
                    type="text"
                    name="ho_ten"
                    id="edit_ho_ten"
                    class="form-control">

            </div>

            <div class="form-group">

                <label>SĐT</label>

                <input
                    type="text"
                    name="so_dien_thoai"
                    id="edit_sdt"
                    class="form-control">

            </div>

            <div class="form-group">

                <label>Vai trò</label>

                <select
                    name="id_vai_tro"
                    id="edit_vai_tro"
                    class="form-control">

                    <?php foreach ($dsVaiTro as $vt) { ?>

                        <option value="<?= $vt['id'] ?>">

                            <?= $vt['ten_vai_tro'] ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="form-group">

                <label>Trạng thái</label>

                <select
                    name="trang_thai"
                    id="edit_trang_thai"
                    class="form-control">

                    <option value="hoat_dong">
                        Hoạt động
                    </option>

                    <option value="khoa">
                        Khóa
                    </option>

                </select>

            </div>

            <button
                type="submit"
                name="sua"
                class="btn btn-primary">
                Lưu thay đổi
            </button>

        </form>

    </div>

</div>

<script>
    function openModal() {

        document
            .getElementById("modalThem")
            .style.display = "block";

    }

    function closeModal() {

        document
            .getElementById("modalThem")
            .style.display = "none";

    }

    function openEditModal(
        id,
        ho_ten,
        sdt,
        id_vai_tro,
        trang_thai
    ) {

        document
            .getElementById("modalSua")
            .style.display = "block";

        document
            .getElementById("edit_id")
            .value = id;

        document
            .getElementById("edit_ho_ten")
            .value = ho_ten;

        document
            .getElementById("edit_sdt")
            .value = sdt;

        document
            .getElementById("edit_vai_tro")
            .value = vai_tro;

        document
            .getElementById("edit_trang_thai")
            .value = trang_thai;
    }

    function closeEditModal() {

        document
            .getElementById("modalSua")
            .style.display = "none";

    }
</script>

<?php include 'thanh_phan/footer.php'; ?>