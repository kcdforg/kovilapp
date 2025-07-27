<?php
include('../popupheader.php');
if(!isset($_POST['book_id']))
{
echo "ERROR THE PAGE CANNOT BE CONNECT";
die;
}
$book_id=$_POST['book_id'];
$result = mysql_query("DELETE FROM $tbl_book WHERE book_id=".$book_id);
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