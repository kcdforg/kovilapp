<?php
include('../popupheader.php');

$event_id = $_GET['event_id'];

if (count($_POST) > 0) {
    $res = update_event($event_id, $_POST);
    if ($res) {
        echo "Successfully 1 record updated";
    } else {
        die('Error: ' . mysql_error());
    }
}

$row = get_event($event_id);
//$result = mysql_query("SELECT * FROM `event` WHERE `id`='$id'");
//$row= mysql_fetch_array($result);
?>
<div class="container-fluid">
    <h2 class="container text-center">Update Event</h2>
</div>
<div class="col-md-12" >
    <!-- Horizontal Form -->
    <form method="post" class="form-horizontal">
        <div class="box box-info">
            <!-- form start -->
            <div id="size" class="box-body">
                <br>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Event name</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputusername3" name="event_name"  value="<?php echo $row['event_name'] ?>" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Date</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputEmail3" name="date" value="<?php echo $row['date'] ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Location</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="location"  value="<?php echo $row['location'] ?>">
                    </div>
                </div>

            </div> 					  
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Cancel</button> 
                <button type="submit" class="btn btn-info pull-right">Update</button>
            </div>
        </div>  			   
    </form>
</div>
<div style="clear:both"></div>
<?php
include('../footer.php');
?>	