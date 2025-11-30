<?php
include('../popupheader.php');

if(!isset($_POST['id']))
{
echo "ERROR THE PAGE CANNOT BE CONNECT";
die;
}
$id=$_POST['id'];
$result = mysql_query("DELETE FROM event WHERE id=".$id);
if(!$con)
{
die('could not connect:' . mysql_error());
}
else
{
?> <div class="callout callout-danger">
          <h4>Deleted successfully!</h4>
       </div>
<?php
}
mysql_close($con);
include('../footer.php');
?>