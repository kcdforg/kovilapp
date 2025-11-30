<?php
include('../popupheader.php');

if (count($_POST) && $_POST['event_name'] != '') {

    $res = add_event($_POST);
    if ($res) {
        echo "Successfully 1 record added";
    } else {
        die('Error: ' . mysql_error());
    }
}
?>
<!-- /.container -->
<div class="container-fluid">
    <h2 class="container text-center">Add Event</h2>
</div>
<div  class="col-md-12" >
    <!-- Horizontal Form -->
    <form method="post" class="form-horizontal">
        <div class="box box-info">
            <!-- form start -->
            <div id="size" class="box-body">
                <br>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Event name</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputusername3" name="event_name" placeholder="Event name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Date</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputEmail3" name="date" placeholder="Date">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Location</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="location" placeholder="Location">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">No of books</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="no_of_books" placeholder="Number of books">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Amount</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="amount" placeholder="Amount">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">    
                <button type="submit" class="btn btn-info pull-right">Cancel</button>			  
                <button type="submit" class="btn btn-info pull-right">Submit</button>
            </div>
            <!-- /.box-footer -->
        </div>
    </form>
    <!-- /.box -->
    <!-- general form elements disabled -->

    <!-- /.box -->
</div>
<div style="clear:both"></div>

<?php
include('../footer.php');
?>