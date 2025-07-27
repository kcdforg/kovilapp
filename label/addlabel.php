<?php
include('../popupheader.php');
if (count($_POST) && $_POST['display_name'] != '') {

    $res = add_label($_POST);
    if ($res) {
        echo "Successfully 1 record added";
    } else {
        die('Error: ' . mysqli_error($con));
    }
}
?>

<!-- /.container -->
<div class="container-fluid">
    <h2 class="container text-center">Add Label</h2>
</div>
<div  class="col-md-12" >
    <!-- Horizontal Form -->
    <form method="post" class="form-horizontal">
        <div class="box box-info">
            <br>
            <!-- /.box-header -->
            <!-- form start -->
 <div id="size" class="box-body">
                <br>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Display Name</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputusername3" name="display_name" placeholder="Display Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Slug</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputEmail3" name="slug" placeholder="Slug">
                    </div>
                </div>
               <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Type</label>

                        <div class="col-sm-7">
                            <?php display_type("type"); ?>
                        </div>
                    </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Category</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="category" placeholder="Category">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Parent id</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="parent_id" placeholder="Parent id">
                    </div>
                </div>
                 <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Order</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="order" placeholder="Order">
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