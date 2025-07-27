<?php
include('../popupheader.php');
if (($_SESSION['username']) != 'admin') {
    echo "<br>" . "<br>" . "<br>" . 'You are not authorized to visit this page!';
    die;
}
$username = $_SESSION['username'];
$id = $_GET['id'];

if (count($_POST) > 0) {
    $res = update_labels($id, $_POST);
    if ($res) {
        echo "Successfully 1 record updated";
    } else {
        die('Error: ' . mysqli_error($con));
    }
}
$row = get_label($id);
?>
<div class="container-fluid">
    <h2 class="container text-center">Update Label</h2>
</div>
<div  class="col-md-12" >
    <!-- Horizontal Form -->
    <form method="post" class="form-horizontal">
        <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <br>
            <div id="size" class="box-body">
                <br>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Display Name</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputusername3" name="display_name" value="<?php echo $row['display_name'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Slug</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputEmail3" name="slug" value="<?php echo $row['slug'] ?>">
                    </div>
                </div>
                <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Type</label>

                        <div class="col-sm-7">
                            <?php display_type("type", $row['type']); ?>
                        </div>
                    </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Category</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="category" value="<?php echo $row['category'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Parent id</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="parent_id " value="<?php echo $row['parent_id'] ?>">
                    </div>
                </div>
                 <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Order</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="order" value="<?php echo $row['order'] ?>">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
              
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Cancel</button> 
                <button type="submit" class="btn btn-info pull-right">update</button>
            </div>	
        </div>   							  

    </form>
</div>
<div style="clear:both"></div>
<?php
include('../footer.php');
?>	