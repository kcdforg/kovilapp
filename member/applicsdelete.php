<?php
include('../popupheader.php');

$id=$_POST['id'];
$image = isset($_POST['appfront']) ? $_POST['appfront'] : '';
$image1 = isset($_POST['app_back']) ? $_POST['app_back'] : '';
var_dump($_POST);	
$file ="../images/". $image;
$file1="../images/". $image1;

echo $file . "<br>" . $file1;
error_reporting(E_ALL);
if (!unlink($file))
  { 
	  ?>
	  <div class="callout callout-danger">
          <h4>Error in deleting this frontpage!</h4>
        </div>
	  <?php
	  
	    }
else
  {
	  ?>
	  <div class="callout callout-danger">
          <h4>Deleted successfully!</h4>
        </div>
  <?php
   $sql = "UPDATE `$tbl_family` SET `appfront`='', `app_back`='' where `id`=$id";
    if (!mysql_query($sql, $con)) {
        die('Error: ' . mysql_error());
    }
  
  }
  ?>
 <?php if (!unlink($file1))
  {
	  ?>
	  <div class="callout callout-danger">
          <h4>Error in deleting this backpage!</h4>
        </div>
	  <?php	  
	    }
else
  {
	  ?>
	  <div class="callout callout-danger">
          <h4>Deleted successfully!</h4>
        </div>
  <?php
  }
  ?>