<?php
include('../popupheader.php');

$id = $_GET['id'];
if (count($_POST) > 0) {
    $res = update_receipt($id, $_POST);
    if ($res) {
        echo "Successfully 1 record updated";
    } else {
        die('Error: ' . mysql_error());
    }
}

$row = get_receipt($id);

//$result = mysql_query("SELECT * FROM `receipt` WHERE`id`=$id");
//$row = mysql_fetch_array($result);
?>

<div class="container-fluid">
    <h2 class="container text-center">Update Receipt</h2>
</div>
<br>
<div class="col-md-12">
    <form class="form-horizontal" method="post">
        <!-- form start -->
        <!-- Horizontal Form -->
        <div class="box box-info" >
            <!-- /.box-header -->                   
            <div id="size" class="box-body">
                <br>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Name</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputusername3" name="name"  value="<?php echo $row['name'] ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Receipt no</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputEmail3" name="rec_no" value="<?php echo $row['rec_no'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Mobile no</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="mobile_no"  value="<?php echo $row['mobile_no'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Date</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputrole3" name="date"   value="<?php echo $row['date'] ?>">
                    </div>
                </div>							
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Amount</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputprofileid3" name="amount" value="<?php echo $row['amount'] ?>">
                    </div>
                </div>
            </div>			

            <div class="box-footer">
                <button type="submit" onclick="window.close()"class="btn btn-info pull-right">Cancel</button> 
                <button type="submit" class="btn btn-info pull-right">Update</button>
            </div>
        </div>	
        <!-- /.box-footer -->
    </form>
</div>
<div style="clear:both"></div>
<!-- /.box -->
<?php
include('../footer.php');
?>	
