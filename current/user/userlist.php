<?php
include('../header.php');

// Display success message if set
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            ' . $_SESSION['success_message'] . '
          </div>';
    unset($_SESSION['success_message']);
}

//$start_from = ($page-1) * $num_rec_per_page; 
$result = get_users();
/* $query = "SELECT count(*) AS total FROM $users"; 
  $count = mysql_query($query);
  $values = mysql_fetch_assoc($count);
  $num_rows = $values['total'];
  echo "<b>Total no.of users=$num_rows</b>"; */
?> 			
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <h2 class="container text-center">User List <a href="adduser.php" class="btn btn-info pull-right"> <span class="glyphicon glyphicon-plus"></span> Add User</a></h2>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box table-responsive">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>                 
                                    <th class="col-sm-1">Username </th>
                                    <th class="col-sm-1">Password</th>
                                    <th class="col-sm-1">Role</th>
                                    <th class="col-sm-1">Profile id</th>
                                    <th class="col-sm-2">Email</th>
                                    <th width="10%">Action</th>					
                                </tr>		
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>          
                                        <td><?php echo $row['username'] ?></td>
                                        <td><?php echo $row['password'] ?></td>
                                        <td><?php echo $row['role'] ?></td>
                                        <td><?php echo $row['profile_id'] ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                        <td>  <a id="a" href="userview.php?id=<?php echo $row['id'] ?>"><span class="glyphicon glyphicon-eye-open"></span></a><a id="a" href="userupdate.php?id=<?php echo $row['id'] ?>"><span class="glyphicon glyphicon-edit"></span></a>  <a id="a" onclick="deleteuser()"> <span class="glyphicon glyphicon-trash"></span></a>
                                    </tr>
                                <script>
                                    function deleteuser()
                                    {
                                        url = "usrdelete.php?id=<?php echo $row['id'] ?>";
                                        title = "popup";
                                        var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
                                    }
                                </script> 
    <?php
}
mysqli_close($con);
?>



                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<?php
include('../footer.php');
?>


