<?php
include('../popupheader.php');
$id = $_GET['id'];
if (!isset($_GET['id'])) {
    echo "ERROR THE PAGE CANNOT BE LOADED";
    die;
}

//$horoscope=$_GET['horoscope'];
//$result = mysql_query("SELECT * FROM `attachments` WHERE `m_id`='$id' AND `type`='horoscope'");
$result = mysql_query("SELECT * FROM `$tbl_matrimony` WHERE `id`='$id'");
$row = mysql_fetch_array($result);
?>
<img src="../images/horo/<?php echo $row['horo'] ?>" width="650px"  class="img-responsive"/>
<br>
<center> 
    <div class="col-sm-12" style="background-color:#3c8dbc;">
        <?php if ($row['horo']!== '') { ?>
                      <input type="button" onclick="horodelete()" value="Delete" />
        <?php } ?>
    </div>
</center>	

<script>
    function horodelete(id,horo)
    {
        url = "hhdelete.php?id=<?php echo $row['id'] ?> &horo=<?php echo $row['horo'] ?>";
        title = "popup";
        var newWindow = window.open(url, title, 'scrollbars=yes, width=500, height=400');
    }
</script>

<script>
    /* function horoscope()
     {
     url = "horoupload.php?id=<?php echo $id ?>";
     title = "popup";
     var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
     }
</script>
     <script>
     
     function horodelete()
     {
     url = "horodelete.php?id=<?php echo $row['id'] ?> &horoscope=<?php echo $row1['file_name'] ?>";
     title = "popup";
     var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
     }*/
</script>