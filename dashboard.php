<?php
include('header.php');

  $sql = "SELECT COUNT(*) as total FROM `$tbl_family` where `deleted`=0";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);

$sql1 = "SELECT COUNT(*) as total FROM `$tbl_child`";
$result1 = mysqli_query($con, $sql1);
$row1 = mysqli_fetch_array($result1);

$sql2 = "SELECT COUNT(*) as total FROM `$tbl_child` where `c_marital_status`='No'";
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_array($result2);

$sql3 = "SELECT COUNT(*) as total FROM `$tbl_child` where `c_marital_status`='No' AND `c_gender`='male'";
$result3 = mysqli_query($con, $sql3);
$row3 = mysqli_fetch_array($result3);

$sql4 = "SELECT COUNT(*) as total FROM `$tbl_child` where `c_marital_status`='No' AND `c_gender`='female'";
$result4 = mysqli_query($con, $sql4);
$row4 = mysqli_fetch_array($result4);

$sql5 = "SELECT COUNT(*) as total FROM `$tbl_matrimony` where `deleted`=0";
$result5 = mysqli_query($con, $sql5);
$row5 = mysqli_fetch_array($result5);

$sql6 = "SELECT COUNT(*) as total FROM `$tbl_matrimony` where `status`='closed'";
$result6 = mysqli_query($con, $sql6);
$row6 = mysqli_fetch_array($result6);

$sql7 = "SELECT COUNT(*) as total FROM `$tbl_matrimony` where `gender`='male'";
$result7 = mysqli_query($con, $sql7);
$row7 = mysqli_fetch_array($result7);

$sql8 = "SELECT COUNT(*) as total FROM `$tbl_matrimony` where  `gender`='female'";
$result8 = mysqli_query($con, $sql8);
$row8 = mysqli_fetch_array($result8);

?>
<style>
  #box.box.box-primary {
   border-color: #ebf0f3;
    border-radius: 10px;
    }
</style>

<br>	
<div class="col-sm-12">

    <div  class="col-sm-4">
        <div id="box" class="box box-primary">
            <div class="box-body box-profile">
                <div class="box-header with-border">
                    <h3 class="box-title" ><b>Families</b></h3>
                </div><br>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b> Total  members :</b> <span class="pull-right"><?php echo $row['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>  Total Children :</b> <span class="pull-right"><?php echo $row1['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b> Total Unmarried :</b> <span class="pull-right"><?php echo $row2['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>  Unmarried Male:</b> <span class="pull-right"><?php echo $row3['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>  Unmarried Female:</b> <span class="pull-right"><?php echo $row4['total']; ?></span>
                    </li>
                </ul>
            </div>
        </div>         
    </div>
    <div  class="col-sm-4">
        <div  id="box" class="box box-primary">
            <div class="box-body box-profile">
                <div class="box-header with-border">
                    <h3 class="box-title" ><b>Matrimony</b></h3>
                </div><br>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>  Total Profiles :</b> <span class="pull-right"><?php echo $row5['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>  Closed Profiles :</b> <span class="pull-right"><?php echo $row6['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>  Male profiles:</b> <span class="pull-right"><?php echo $row7['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>  Female profiles:</b> <span class="pull-right"><?php echo $row8['total']; ?></span>
                    </li>
                </ul>
            </div>
        </div>         
    </div>

    <div  class="col-sm-4">
        <div  id="box" class="box box-primary">
            <div class="box-body box-profile">
                <div class="box-header with-border">
                    <h3 class="box-title" ><b>Donation</b></h3>
                </div><br>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>  Total Entry fee :</b> <span class="pull-right">ferfesr<?php // echo $row9['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>  Total Subscription fee:</b> <span class="pull-right">vbgf<?php // echo $row10['total']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>  Total Donation :</b> <span class="pull-right">tytry<?php // echo $row11['total']; ?></span>
                    </li>
                </ul>
            </div>
        </div>         
    </div>
</div>
<div style="clear:both"></div>	
<?php
include('footer.php');
?>