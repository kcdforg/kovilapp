<?php
include('../popupheader.php');
if (!isset($_POST['id'])) {
    echo "ERROR THE PAGE CANNOT BE CONNECT";
    die;
}
$id = $_POST['id'];
$result = mysqli_query($con, "DELETE FROM $tbl_users WHERE id=" . $id);
if (!$con) {
    die('could not connect:' . mysqli_error($con));
} else {
    ?>
    <div class="callout callout-danger">
        <h4>Deleted successfully!</h4>
    </div>
    <?php
}
mysqli_close($con);
include('../footer.php');
?>