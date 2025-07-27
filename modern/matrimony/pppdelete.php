<?php
include('../popupheader.php');
$photo = isset($_POST['photo']) ? $_POST['photo'] : '';
$file = "../attachments/" . $photo;
if (!unlink($file)) {
    ?>
    <div id="error" class="callout callout-danger">
        <h4>Error in deleting this image!</h4>
    </div>
    <?php
} else {
    ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-thumbs-o-up"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><h4>Deleted successfully!</h4></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>

    <?php
}
include('../footer.php');
?>