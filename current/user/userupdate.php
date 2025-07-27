<?php
include('../header.php');
if (($_SESSION['username']) != 'admin') {
    echo "<br>" . "<br>" . "<br>" . 'You are not authorized to visit this page!';
    die;
}
$username = $_SESSION['username'];
$id = $_GET['id'];

if (count($_POST) > 0) {
    $res = update_users($id, $_POST);
    if ($res) {
        echo "Successfully 1 record updated";
    } else {
        die('Error: ' . mysqli_error($con));
    }
}
$row = get_user($id);
//$result = mysqli_query($con, "SELECT * FROM `$users` WHERE `id`='$id'");
//$row= mysqli_fetch_array($result);
?>
<div class="container-fluid">
    <h2 class="container text-center">Update User</h2>
</div>
<div  class="col-md-12" >
    <!-- Horizontal Form -->
    <form method="post" class="form-horizontal">
        <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <br>			
            <div id="sizee" class="box-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Username</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputusername3" name="username"  value="<?php echo $row['username'] ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">E-mail</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputEmail3" name="email" value="<?php echo $row['email'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Role</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputPassword3" name="role"  value="<?php echo $row['role'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Profile Id</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputrole3" name="profile_id"   value="<?php echo $row['profile_id'] ?>">
                    </div>
                </div>							
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Creation Date</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputprofileid3" name="creation_date" value="<?php echo $row['creation_date'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Created by</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputprofileid3" name="created_by" value="<?php echo $row['created_by'] ?>">
                    </div>
                </div>
            </div>				  

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