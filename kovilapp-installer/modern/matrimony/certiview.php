<?php
include('../popupheader.php');
if (!isset($_GET['id'])) {
    echo "ERROR THE PAGE CANNOT BE LOADED";
    die;
}
$id = $_GET['id'];

$result = mysql_query("SELECT * FROM `$tbl_attachments` WHERE `m_id`='$id' AND `type`='certificate'");
$row = mysql_fetch_array($result);
?>
<img src="attachments/<?php echo $row['file_name'] ?>" width="630" height="600" class="img-responsive"/>
<br>
<center> 
    <div class="col-sm-12" style="background-color:#3c8dbc;">
        <button onclick="certificate()">Upload </button>
        <script>
            function certificate()
            {
                url = "certiupload.php?id=<?php echo $id ?>";
                title = "popup";
                var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
            }
        </script>
        <script>

            function certidelete()
            {
                url = "certidelete.php?id=<?php echo $row['id'] ?> &certificate=<?php echo $row['file_name'] ?>";
                title = "popup";
                var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
            }
        </script>
        <input type="button" onclick="certidelete()" value="Delete" />
    </div>
</center>	