<?php
include('../popupheader.php');
$w_image = isset($_POST['w_image']) ? $_POST['w_image'] : '';
$file = "../images/" . $w_image;
if (!unlink($file)) {
    ?>
    <div class="callout callout-danger">
        <h4>Error in deleting this image!</h4>
    </div>
    <?php
} else {
    //echo ("Deleted $file");
    //}
    ?>
    <div class="callout callout-danger">
        <h4>Deleted successfully!</h4>
    </div>
    <?php
}
include('../footer.php');
?>