<?php
include('../init.php');
check_login();

if (!isset($_POST['id'])) {
    echo "<script>alert('ERROR: THE PAGE CANNOT BE CONNECTED'); window.close();</script>";
    die;
}

$id = $_POST['id'];
$result = mysqli_query($con, "DELETE FROM $tbl_labels WHERE id=" . $id);

if (!$result) {
    echo "<script>alert('Error: " . mysqli_error($con) . "'); window.close();</script>";
} else {
    echo "<script>alert('Label deleted successfully!'); window.close(); window.opener.location.reload();</script>";
}

mysqli_close($con);
?>