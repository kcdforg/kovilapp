<?php
include('../popupheader.php');

$book_id = $_GET['book_id'];
if (count($_POST) > 0) {
    $res = update_book($id, $_POST);
    if ($res) {
        echo "Successfully 1 record updated";
    } else {
        die('Error: ' . mysql_error());
    }
}

$rowbook = get_book($book_id);

//$result1 = mysql_query("SELECT * FROM `book` WHERE `id`='$id'");
//$rowbook= mysql_fetch_array($result1);
?>
<div class="container-fluid">
    <h2 class="container text-center">Update Book</h2>
</div><br>
<div  class="col-md-12" >
    <!-- Horizontal Form -->
    <form method="post" class="form-horizontal">
        <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->           
            <div id="size" class="box-body">
                <br>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Bookno</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputusername3" name="book_no"  value="<?php echo $rowbook['book_no'] ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Book sno</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputEmail3" name="book_sno" value="<?php echo $rowbook['rec_start_no'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Book eno</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="book_eno"  value="<?php echo $rowbook['rec_end_no'] ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Denom</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="denom"  value="<?php echo $rowbook['denom'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Collected Amount</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="collected_amount"  value="<?php echo $rowbook['collected_amount'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Issued to</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="issued_to"  value="<?php echo $rowbook['issued_to'] ?>">
                    </div>
                </div>
            </div> 					  
            <div class="box-footer">
                <button type="submit" onclick="window.close()" class="btn btn-info pull-right">Cancel</button> 
                <button type="submit" class="btn btn-info pull-right">update</button>
            </div>
        </div>  			   
    </form>
</div>
<div style="clear:both"></div>
<?php
include('../footer.php');
?>	