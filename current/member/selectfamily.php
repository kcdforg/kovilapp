<?php
include('../popupheader.php');
$id = $_GET['id'];
//echo $id;
$c_id=$_GET['child_id'];

  $error_msg = '';
$success_msg = '';
    
   if ($c_id) {
        $sql = "UPDATE `$tbl_child` SET `fam_id`='$id' ,`c_marital_status`='Yes' WHERE `id` ='$c_id'";
        //    echo $sql;
            mysql_query($sql, $con);
                  $success_msg ;
        }
    else 
        {
        $error_msg = 'Error: ' . mysql_error();
    }
 
?>
<?php if ($success_msg) { ?>
<div class="callout callout-danger">
                <h4>Upload success!</h4>
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">close</button>
                  <?php echo $success_msg ?>
            </div>

<?php } ?>

<?php if ($error_msg) { ?>
    <div class="alert alert-danger alert-dismissable col-sm-10  col-sm-offset-1" style="margin-bottom:0px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $error_msg ?>
    </div>
<?php }
 
include('../footer.php');?>