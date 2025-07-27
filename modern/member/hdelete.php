<?php
include('../popupheader.php');
$image = isset($_POST['image']) ? $_POST['image'] : '';
$file = "../images/". $image;
if (!unlink($file))
  {
	  ?>
	  <div class="callout callout-danger">
          <h4>Error in deleting this image!</h4>
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
 include('../footer.php');
?>