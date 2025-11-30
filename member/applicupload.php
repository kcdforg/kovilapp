<?php
include('../popupheader.php');
$id = $_GET['id'];
$s = $id . "_appfront" . ".jpg";
$s1 = $id . "_appback" . ".jpg";
$upload_dir = $base_dir . "/images/member/";
//echo $upload_dir;
$msg = '';
if (isset($_FILES["app_front"]) && ($_FILES["app_back"])) {
    if ($_FILES["app_front"]["error"] && $_FILES["app_back"]["error"] > 0) {
        echo "Return Code: " . $_FILES["app_front"]["error"] . $_FILES["app_back"]["error"] . "<br />";
    } else {
        if ($_FILES["app_front"]["type"] && $_FILES["app_back"]["type"] != 'image/jpeg') {
            echo "Image should be in jpeg format";
            die;
        } else {

            if (file_exists("../images/member/" . $s)) {
                //$msg .= $s . " already exists. ";
                unlink("../images/member/" . $s);
            }
            //else {               
            move_uploaded_file($_FILES["app_front"]["tmp_name"], "../images/member/" . $s);
            chmod("../images/member/" . $s, 0664);



            if (file_exists("../images/member/" . $s1)) {
                //$msg .= $s . " already exists. ";
                unlink("../images/member/" . $s1);
            }
            //else {               
            move_uploaded_file($_FILES["app_back"]["tmp_name"], "../images/member/" . $s1);
            chmod("../images/member/" . $s, 0664);

            $sql = "UPDATE `$tbl_family` SET `app_front`='$s', `app_back`='$s1' where `id`=$id";

            if (!mysql_query($sql, $con)) {
                die('Error: ' . mysql_error());
            }

            $msg .= "Successfully uploaded! ";
            //"Stored in:" . "images/" . $s; 
            //  }
        }
    }
}
?>
<section class="content-header">
    <h1>
        Upload application form
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">


<?php
if (!(is_dir($upload_dir) && is_writable($upload_dir))) {
    echo 'Upload directory is not writable, or does not exist.';
} else {
    if ($msg != '') {
        ?>
                    <h3><?php echo $msg ?></h3>
                    <center> <button type="button" onclick="window.close()">Ok</button> </center>
                    <?php
                } else {
                    ?>

                    <form action="" method="post" enctype="multipart/form-data">
                        <br><label for="image">select the front page of application form:</label><br>
                        <input type="file" name="app_front"  /><br>
                        <label for="image">select the back page of application form:</label><br>
                        <input type="file" name="app_back"  />
                        <br/>
                        <br>
                        <input type="submit" name="submit" value="Submit" />
                        <button type="button" onclick="window.close()">Cancel</button>
                    </form>
        <?php
    }
}
?>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>
