<?php
include('../popupheader.php');
$horo = isset($_POST['horo']) ? $_POST['horo'] : '';
$file = "../attachments/" . $horo;
if (!unlink($file)) {
    ?>
    <div class="callout callout-danger">
        <h4>Error in deleting this horoscope!</h4>
    </div>
    <?php
} else {
    ?>
    <div class="callout callout-danger">
        <h4>Deleted successfully!</h4>
    </div>
    <?php
}
?>