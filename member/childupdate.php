<?php
include('../popupheader.php');

$id = $_GET['id'];
$error_msg = '';
$msg = '';
if (count($_POST) > 0) {
    // Get the father_id before updating to regenerate family tree
    global $con, $tbl_child;
    $sql = "SELECT father_id FROM $tbl_child WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result_child = mysqli_stmt_get_result($stmt);
    $child_record = mysqli_fetch_assoc($result_child);
    mysqli_stmt_close($stmt);
    
    $res = update_child($id, $_POST);
    if ($res) {
        // Regenerate family tree if child belongs to a family
        if ($child_record && !empty($child_record['father_id'])) {
            regenerateFamilyTree($child_record['father_id']);
        }
        
        $msg = "Successfully updated";
    } else {
        $error_msg = 'Error: ' . mysql_error();
    }
}

$rows = get_child($id);

//$result = mysql_query("SELECT * FROM $tbl_child WHERE`id`='$id'");
//while ($rows = mysql_fetch_array($result)) {
?>
<div class="container-fluid">
    <h2 class="container text-center">Update Child</h2>
</div>
<?php if ($msg != '') { ?>
    <div  class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $msg ?>
    </div>
    <?php
}
if ($error_msg != '') {
    ?>
    <div  class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $error_msg ?>
    </div>
<?php } ?>
<div class="col-md-12">
    <form class="form-horizontal" method="post">
        <!-- form start -->
        <!-- Horizontal Form -->
        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title">CHILDREN</h3>
            </div>
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputusername3" name="c_name"  value="<?php echo $rows['c_name'] ?>" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">DOB</label>

                        <div class="col-sm-7">
                            <table>
                                <tr>
                                    <td >
                                        <?php display_date("dob[date]", $rows['c_dob']) ?>
                                    </td>
                                    <td>
                                        <?php display_month("dob[month]", $rows['c_dob']) ?>  
                                    </td> 
                                    <td>
                                        <?php display_year("dob[year]", $rows['c_dob']) ?>   
                                    </td></tr></table>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Gender</label>
                        <div class="col-sm-7" style="padding-top:7px;">
                            <?php echo $rows['c_gender']; ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Bloodgroup</label>

                        <div class="col-sm-7">
                            <?php display_blood_group_list("c_blood_group", $rows['c_blood_group']); ?>
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Marital status</label>
                        <div class="col-sm-7" >
                            <?php
                            $c_gender = $rows['c_gender'];
                            // echo $c_gender;
                            if ($c_gender == 'female' || $c_gender == 'Female') {
                                ?>
                                <select name="c_marital_status" class="form-control">
                                    <option <?php echo ($rows['c_marital_status'] == "No") ? "selected" : '' ?>>No</option>
                                    <option <?php echo ($rows['c_marital_status'] == "Yes") ? "selected" : '' ?> >Yes</option>
                                </select>
                                <?php
                            } else {
                                echo $rows['c_marital_status'];
                                ?> 

                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education</label>
                        <div class="col-sm-7">
                            <?php display_qualification("c_qualification", $rows['c_qualification']); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education Details</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="c_education_details" value="<?php echo $rows['c_education_details'] ?>">
                        </div>

                    </div>	

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation</label>

                        <div class="col-sm-7">
                            <?php display_occupation($name = "c_occupation", $rows['c_occupation']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="c_occupation_details"  value="<?php echo $rows['c_occupation_details'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Email</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="c_email" value="<?php echo $rows['c_email'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Mobile No</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="c_mobile_no" value="<?php echo $rows['c_mobile_no'] ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="c_gender" value="<?php echo $rows['c_gender']; ?>">   
        <input type="hidden"  class="form-control" id="inputEmail3"  name="c_marital_status" value="Yes">    
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Cancel</button> 
            <button type="submit" class="btn btn-info pull-right">Update</button>
        </div>
    </form>
</div>
<div style="clear:both"></div>
<?php
include('../footer.php');
?>				
