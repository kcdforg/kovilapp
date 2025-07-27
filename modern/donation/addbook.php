<?php
include('../popupheader.php');

if (count($_POST) && $_POST['book_no'] != '') {

    $res = add_book($_POST);
    if ($res) {
        echo "Successfully 1 record added";
    } else {
        die('Error: ' . mysql_error());
    }
}
?>

<div class="container-fluid">
    <h2 class="container text-center">Add Book</h2>
</div>
<div  class="col-md-12" >
    <!-- Horizontal Form -->
    <form method="post" class="form-horizontal">
        <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <div id="size" class="box-body">
                <br>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Book no</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEmail3" name="book_no" placeholder="Book no">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Rec start no</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPassword3" name="book_sno" placeholder="Book starting no">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Rec end no</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputrole3" name="book_eno" placeholder="Book ending no">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Issued to</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputprofileid3" name="issued_to" placeholder="Issued to">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Denom</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputprofileid3" name="denom" placeholder="Denomination">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Collected Amount</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputprofileid3" name="collected_amount" placeholder="Collected amount">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">    
                <button type="submit" onclick="window.close()" class="btn btn-info pull-right">Cancel</button>		
                <button type="submit"  class="btn btn-info pull-right">Submit</button>
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