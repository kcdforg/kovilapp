<?php
include('../header.php');

$id = $_GET['id'];
$row = get_user($id);
?>
<div class="container-fluid">
    <h2 class="container text-center">User Details</h2>
</div>
<div class="userview">

    <div class="box box-primary">
        <div class="box-body box-profile">
          
            <img class="profile-user-img img-responsive" src="../images/<?php echo $row['u_image'] ?>" /><br>
              <div style="margin-left:200px;">  <button class="btn btn-info " onclick="uimageupload()">Upload</button>
            <button class="btn btn-info" onclick="uimagedelete()">Delete</button>
              </div><br>
            <script>
                function uimageupload()
                {
                    url = "uimageupload.php?id=<?php echo $id ?>";
                    title = "popup";
                    var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=400');
                }
            </script>
            <script>
                function uimagedelete()
                {
                    url = "uimagedelete.php?id=<?php echo $row['id'] ?> &u_image=<?php echo $row['u_image'] ?>";
                    title = "popup";
                    var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=400');
                }
            </script>

                  <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b>Username</b> <label style= "font-weight:normal;" class="pull-right"><?php echo $row['username'] ?></label>
                </li>
                <li class="list-group-item">
                    <b>Email</b> <label style= "font-weight:normal;"  class="pull-right"><?php echo $row['email'] ?></label>
                </li>
                <li class="list-group-item">
                    <b>Role</b> <label style= "font-weight:normal;" class="pull-right"><?php echo $row['role'] ?></label>
                </li>
                <li class="list-group-item">
                    <b>Profile id</b> <label style= "font-weight:normal;" class="pull-right"><?php echo $row['profile_id'] ?></label>
                </li>
                <li class="list-group-item">
                    <b>Created date</b> <label style= "font-weight:normal;" class="pull-right"><?php echo $row['creation_date'] ?></label>
                </li>
                <li class="list-group-item">
                    <b>Createdby</b> <label style= "font-weight:normal;" class="pull-right"><?php echo $row['created_by'] ?></label>
                </li>
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
</div>

<div style="clear:both"></div>  
<?php
include('../footer.php');
?>