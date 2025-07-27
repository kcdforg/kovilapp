<?php
include_once('../init.php');

$error_msg = '';
$success_msg = '';

if (count($_POST) && $_POST['username'] != '') {

    $res = add_user($_POST);
    if ($res) {
        header("Location: userlist.php");
        exit();
    } else {
        $error_msg = 'Error: ' . mysqli_error($con);
    }
}

include('../header.php');
?>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add role</h4>
            </div>
            <div class="modal-body">
                <p>Role:</p>
                <form method="POST" action="adduser.php">
                    <input type="text" class="form-control" id="inputusername3" name="role">
                    <br>
                     <button type="submit" name="submit" class="btn btn-primary pull-right">Save </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               
            </div>
        </div>
    </div>
</div>
<!-- /.container -->
<div class="container-fluid">
    <h2 class="container text-center">Add user</h2>
</div>

<?php if ($error_msg) { ?>
    <div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1" style="margin-bottom:0px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $error_msg ?>
    </div>
<?php } ?>

<div  class="col-md-12" >
    <!-- Horizontal Form -->
    <form method="post" class="form-horizontal">
        <div class="box box-info">
            <br>
            <!-- /.box-header -->
            <!-- form start -->

            <div  id="sizze" class="box-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputusername3" name="username" placeholder="Username">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEmail3" name="email" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Role</label>

                    <div class="col-sm-10">
                        <select name="role" class="form-control" >
                            <option data-toggle="modal" data-target="#myModal">Add role</option>
                            <option selected="selected">Select</option>                        
                            <option>manager</option>
                            <option>admin</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ProfileID</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputprofileid3" name="profile_id" placeholder="Profile Id">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">creation date</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEmail3" name="creation_date" placeholder="Creation Date">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">created by</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEmail3" name="created_by" placeholder="Created By">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">               
                <a href="userlist.php" class="btn btn-info pull-right">Cancel</a>
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