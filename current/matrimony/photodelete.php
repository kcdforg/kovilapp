<?php
include('../popupheader.php');

$file_name = isset($_POST['photo']) ? $_POST['photo'] : '';
$file = "../attachments/" . $file_name;
if (!unlink($file)) {
    ?>
    <div class="callout callout-danger">
        <h4>Error in deleting this image!</h4>
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