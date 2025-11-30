<?php
include('../popupheader.php');
include_once(dirname(__FILE__) . "/../function.php");
$id = $_GET['id'];
$image = $_GET['image'];
$image1 = $_GET['image1'];
$msg = '';
//echo $image . "<br>" . $image1;
//$s= $id."_appfront.jpg";
//$s1= $id."_appback.jpg";
?>
<section class="content-header">
    <h1>
        Delete your application
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">

            <?php
            if (isset($_POST['id'])) {
                $image = isset($_POST['app_front']) ? $_POST['app_front'] : '';
                $image1 = isset($_POST['app_back']) ? $_POST['app_back'] : '';
                $file = $base_dir . "/images/member/" . $image;
                $file1 = $base_dir . "/images/member/" . $image1;
                error_reporting(E_ALL);
                //echo $file;
                if (!unlink($file)) {
                    echo "Error in deleting frontpage!";
                } 
                  if (!unlink($file1)) {
                    echo "Error in deleting backpage!";
                } else {
                    $sql = "UPDATE `$tbl_family` SET `app_front`='', `app_back`='' where `id`=$id";

                    if (!mysql_query($sql, $con)) {
                        die('Error: ' . mysql_error());
                    }
                    $msg .= "Successfully Deleted! ";
                }            
            }

            if ($msg != '') {
                ?>

                <h4><?php echo $msg ?></h4>
                <center>    <button type="button" onclick="window.close()">Ok</button></center>
                <?php
            } else {
                ?>            

                <label>Are you sure want to delete this application?</label>
                <br>
                <br>
                <form action="" method="POST">
                    <input type="hidden" name="app_front" value="<?php echo $image ?>">
                    <input type="hidden" name="app_back" value="<?php echo $image1 ?>">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <button type="submit" >Yes</button>
                    <button type="submit" onclick="window.close()">No</button>
                </form>
            <?php } ?>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>