<?php
include('header.php');
include('function.php');
if (!isset($_SESSION['username'])) {
    echo 'You are not logged in<a href="index.php">Visit Login Page</a>';
    die;
}
if (isset($_GET['mobile_no'])) {
    $mobile_no = $_GET['mobile_no'];
    $con = connectdb();
    mysql_set_charset('utf8', $con);
    mysql_select_db("unicode", $con);
    $result = mysql_query("SELECT * FROM $family WHERE mobile_no='$mobile_no'");
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="container-fluid">
        <h2 class="container text-center"><?php echo "Family in " . $_GET['mobile_no']; ?> </h2>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Parent name</th>   
                                    <th>Wife name</th>
                                    <th>Qualification</th>
                                    <th>Occupation</th>
                                    <th>Mobile No.</th>
                                    <th>Permanent address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
while ($row = mysql_fetch_array($result)) {
    ?>
                                <tbody>
                                    <tr>						
                                        <td><?php echo $row['name'] ?></td>
                                        <td><?php echo $row['father_name'] ?> ,
    <?php echo $row['mother_name'] ?></td>
                                        <td><?php echo $row['w_name'] ?></td>
                                        <td><?php echo $row['qualification'] ?></td>
                                        <td><?php echo $row['occupation'] ?></td>
                                        <td><?php echo $row['mobile_no'] ?></td>
                                        <td><?php echo $row['permanent_address'] ?></td>
                                        <td>  <a href="viewmember.php? id=<?php echo $row['id'] ?>">View</a> <br><a href="updatemember.php?id=<?php echo $row['id'] ?>">Update</a> <br><a href="deletemember.php?id=<?php echo $row['id'] ?>">Delete</a> </td>
                                    </tr>
    <?php
}
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
include('footer.php');
?>  