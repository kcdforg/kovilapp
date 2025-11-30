<?php
$id = $_GET['id'];
include('../popupheader.php');
include_once(dirname(__FILE__) . "/../function.php");
$msg = '';
?>
<section class="content-header">
    <h1>
        Delete children
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">

            <?php
            if (!isset($_GET['id'])) {
                echo "ERROR THE PAGE CANNOT BE CONNECT";
            } else {
                if (isset($_POST['id'])) {

                    $sql = "DELETE FROM $tbl_child WHERE id=" . $id;
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

                <label>Are you sure you want to delete this child?</label>
                <br>
                <br>
                <form action="" method="POST">
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