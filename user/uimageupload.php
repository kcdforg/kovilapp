<?php
include('../popupheader.php');

$id = $_GET['id'];
$s = $id . "_user.jpg";
$upload_dir = $base_dir . "/websites/sangam/images/";
//echo $upload_dir;

$msg = '';

if (isset($_FILES["u_image"])) {
    if ($_FILES["u_image"]["error"] > 0) {
        echo "Return Code: " . $_FILES["u_image"]["error"] . "<br />";
    } else {
        if ($_FILES["u_image"]["type"] != 'image/jpeg') {
            echo "Image should be in jpeg format";
            die;
        } else {
            
            if (file_exists("../images/" . $s)) {
                //$msg .= $s . " already exists. ";
                unlink("../images/" . $s);
            } 
            //else {

                move_uploaded_file($_FILES["u_image"]["tmp_name"], "../images/" . $s);
                chmod("../images/" . $s, 0664);
                
                $sql = "UPDATE `$tbl_users` SET `u_image`='$s' where `id`=$id";

                if (!mysqli_query($con, $sql)) {
                    die('Error: ' . mysqli_error($con));
                }
                $msg .= "upload Succes";
                //"Stored in:" . "images/" . $s; 
                ?>

                <?php
          //  }
        }
    }
}
?>
<section class="content-header">
    <h1>
        Upload user image 
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

                        <h4><?php echo $msg ?></h4>
                                        <?php
                } else {
                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <label for="image">select user image:</label>
                        <input type="file" name="u_image" id="u_image" />
                        <br>
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
