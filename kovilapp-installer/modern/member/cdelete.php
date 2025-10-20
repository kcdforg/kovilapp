<?php
include('../popupheader.php');
$c_image = isset($_POST['c_image']) ? $_POST['c_image'] : '';
$file = "../images/". $c_image;
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