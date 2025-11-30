<?php
include('../popupheader.php');
$c_created_date = date('Y-m-d H:i:s');
$c_created_by = $_SESSION['username'];
$father_id = $_GET['id'];
$error_msg = '';
$success_msg = '';
if (count($_POST) && $_POST['c_name'] != '') {

    $res = add_child($_POST);
    if ($res) {
        $success_msg = "Added Successfully ";
    } else {
        $error_msg = 'Error: ' . mysql_error();
    }
}
?>

<div class="container-fluid">
    <h2 class="container text-center">Add Daughter  </h2>
</div>
<?php if ($success_msg) { ?>
    <div class="alert alert-success col-sm-10  col-sm-offset-1" style="margin-bottom:0px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $success_msg ?>
    </div>
<?php } ?>

<?php if ($error_msg) { ?>
    <div class="alert alert-danger alert-dismissable col-sm-10  col-sm-offset-1" style="margin-bottom:0px;">
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
                <h3 class="box-title">CHILD</h3>
            </div>
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Name <span class="text-danger">*</span></label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputusername3" name="c_name" placeholder="Children Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">DOB</label>

                        <div class="col-sm-7">
                            <table>
                                <tr>
                                    <td >
                                        <?php display_date($name = "dob[date]") ?>
                                    </td>
                                    <td>
                                        <?php display_month($name = "dob[month]") ?>  
                                    </td> 
                                    <td>
                                        <?php display_year($name = "dob[year]") ?>   
                                    </td></tr></table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Bloodgroup</label>
                        <div class="col-sm-7">
                            <?php display_blood_group_list($name = "c_blood_group"); ?>
                        </div>
                    </div>				
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Marital status</label>

                        <div class="col-sm-7">
                            <select name="c_marital_status" class="form-control">
                                <option>-Select-</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education</label>

                        <div class="col-sm-7">
                            <?php display_qualification($name = "c_qualification"); ?>
                        </div>
                    </div>                   					
                </div>				
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="c_education_details" placeholder="Education Details">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation</label>

                        <div class="col-sm-7">
                            <?php display_occupation($name = "c_occupation"); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="c_occupation_details"  placeholder="Occupation Details">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Email</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="c_email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">MobileNo</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="c_mobile_no" placeholder="Mobile No">
                        </div>
                    </div>
                </div>	
            </div>
        </div>
        <input type="hidden" name="c_gender" value="female">
        <div class="box-footer">
            <button type="button" onclick="window.close()" class="btn btn-info pull-right">Cancel</button> 
            <button type="submit" class="btn btn-info pull-right">submit</button>
        </div>
        <input type="hidden" class="form-control" id="inputusername3" name="father_id" value="<?php echo $father_id; ?>" placeholder="childrenname">
    </form>
</div>
<div style="clear:both"></div>