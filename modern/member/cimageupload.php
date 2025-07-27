<?php
include('../popupheader.php');

$id = $_GET['id'];
$father_id = $_GET['father_id'];
$s = $father_id . "_child_" . $id . ".jpg";

$upload_dir = $base_dir . "/images/member/";
//echo $upload_dir;
$msg = '';

if (isset($_FILES["c_image"])) {
    if ($_FILES["c_image"]["error"] > 0) {
        echo "Return Code: " . $_FILES["c_image"]["error"] . "<br />";
    } else {
        if ($_FILES["c_image"]["type"] != 'image/jpeg') {
            echo "Image should be in jpeg format";
            die;
        } else {
            
            if (file_exists("../images/member/" . $s)) {
           //     $msg .= $s . " already exists. ";
                unlink("../images/member/" . $s);
            }
            // else {

                move_uploaded_file($_FILES["c_image"]["tmp_name"], "../images/member/" . $s);
                
                 chmod("../images/member/" . $s, 0664);
                
                $sql = "UPDATE `$tbl_child` SET `c_image`='$s' where `id`=$id";

                if (!mysql_query($sql, $con)) {
                    die('Error: ' . mysql_error());
                }
                    $msg .= "Successfully uploaded! ";
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
        Upload Children Photo 
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
                       <center>    <button type="button" onclick="window.close()">Ok</button></center>

                    <?php
                } else {
                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <label for="image">select  image:</label>
                        <input type="file" name="c_image" id="c_image" />
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
