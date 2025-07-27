<?php
include_once('../init.php');
// Enable mysqli exception mode
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$created_date = date('Y-m-d H:i:s');
$created_by = $_SESSION['ID'];
$error_msg = '';
$success_msg = '';

$child_id = isset($_GET['child_id']) ? $_GET['child_id'] : 0;
$row = get_child($child_id);

// Initialize all variables to empty string
// Only 'name' is required
$fields = [
    'name'
];
foreach (
    $fields as $f) {
    if (isset($_POST[$f])) {
        if (is_array($_POST[$f])) {
            $$f = array_map('trim', $_POST[$f]);
        } else {
            $$f = trim($_POST[$f]);
        }
    } else {
        $$f = '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate all fields are present and not empty
    $missing = [];
    foreach ($fields as $f) {
        if (empty($_POST[$f])) {
            $missing[] = $f;
        }
    }
    // Remove DOB and w_dob required validation
    // (No $dob_valid or $w_dob_valid checks)

    if (count($missing) > 0) {
        $error_msg = 'Please fill all required fields: ' . implode(', ', $missing);
    } else {
        try {
            // Convert dob and w_dob arrays to YYYY-MM-DD strings before passing to add_member
            if (isset($_POST['dob']) && is_array($_POST['dob'])) {
                $_POST['dob'] = sprintf('%04d-%02d-%02d', $_POST['dob']['year'], $_POST['dob']['month'], $_POST['dob']['date']);
            }
            if (isset($_POST['w_dob']) && is_array($_POST['w_dob'])) {
                $_POST['w_dob'] = sprintf('%04d-%02d-%02d', $_POST['w_dob']['year'], $_POST['w_dob']['month'], $_POST['w_dob']['date']);
            }
            $res = add_member($_POST);
            if ($res) {
                // $success_msg = "Added Successfully ";
                //  $fam_id='';
                //if child id is passed, link child with the family created..
                $c_marital_status = 'Yes';
                if ($child_id) {
                    $sql = " UPDATE $tbl_child SET fam_id='$res',c_marital_status='$c_marital_status'  WHERE id=$child_id  ";
                    echo $sql;
                    mysql_query($sql, $con);
                }
                header('Location: listmember.php');
                exit;
            } else {
                $error_msg = 'Error: Unknown error occurred while adding member.';
            }
        } catch (mysqli_sql_exception $e) {
            $error_msg = 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    }
}

include('../header.php');
?>



<div class="container-fluid">
    <h2 class="container text-center">Add Member</h2>
</div>
<?php if ($success_msg) { ?>
    <div class="alert alert-success col-sm-10  col-sm-offset-1" style="margin-top:5px;margin-bottom: 5px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php echo $success_msg ?>
    </div>
<?php } ?>

<?php if ($error_msg) { ?>
    <div class="alert alert-danger alert-dismissable col-sm-10  col-sm-offset-1" style="margin-bottom:0px;">
        <button type="button" class="close" data-dismiss="alert"  aria-hidden="true">×</button>
        <?php echo $error_msg ?>
    </div>
<?php } ?>

<div class="col-md-12">
    <form class="form-horizontal" method="post">
        <!-- form start -->
        <!-- Horizontal Form -->
        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title">HUSBAND </h3>
            </div>
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputusername3" name="name" value="<?php echo $name ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Father's name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="father_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">Mother's name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputPassword3" name="mother_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">DOB</label>

                        <div class="col-sm-7">
                            <table>
                                <tr>
                                    <td >
                                        <?php display_date("dob[date]", isset($row['c_dob']) ? $row['c_dob'] : '') ?>
                                    </td>
                                    <td>
                                        <?php display_month("dob[month]", isset($row['c_dob']) ? $row['c_dob'] : '') ?>  
                                    </td> 
                                    <td>
                                        <?php display_year("dob[year]", isset($row['c_dob']) ? $row['c_dob'] : '') ?>   
                                    </td></tr></table>
                        </div>
                    </div>	
                    <?php /* <table>
                      <tr>
                      <td >
                      <?php display_date("dob[date]", $row['c_dob']) ?>
                      </td>
                      <td>
                      <?php display_month("dob[month]", $row['c_dob']) ?>
                      </td>
                      <td>
                      <?php display_year("dob[year]", $row['c_dob']) ?>
                      </td></tr></table>
                      <div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Age</label>

                      <div class="col-sm-7">
                      <input type="text" class="form-control" id="inputprofileid3" name="age" value="<?php echo $row['age'] ?>" style="width:70px">
                      </div>
                      </div>
                     * 
                     */ ?>
                    <style>
                        /*      select[name=blood_group]{
                                  width:100px;
                              }*/
                    </style>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Blood group</label>
                        <div class="col-sm-7">                       
                            <?php display_blood_group_list("blood_group", isset($row['c_blood_group']) ? $row['c_blood_group'] : '') ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education</label>
                        <div class="col-sm-7">
                            <?php display_qualification("qualification", isset($row['c_qualification']) ? $row['c_qualification'] : '') ?>
                        </div>
                    </div>
                    <script>
                        $("#qualification").select2();
                    </script>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="education_details" value="<?php echo $education_details ?>">
                        </div>
                    </div>					
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation</label>

                        <div class="col-sm-7">
                            <?php display_occupation("occupation", isset($row['c_occupation']) ? $row['c_occupation'] : '') ?>
                        </div>
                    </div>
                    <script>
                        $("#occupation").select2();
                    </script>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="occupation_details"  value="<?php echo $occupation_details ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Email</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="email"  value="<?php echo $email ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Mobile No</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="mobile_no"  value="<?php echo $mobile_no ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title" >WIFE</h3>
            </div>
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputusername3" name="w_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">DOB</label>

                        <div class="col-sm-7">
                            <table>
                                <tr>
                                    <td >
                                        <?php display_date("w_dob[date]", '') ?>
                                    </td>
                                    <td>
                                        <?php display_month("w_dob[month]", '') ?>  
                                    </td> 
                                    <td>
                                        <?php display_year("w_dob[year]", '') ?>   
                                    </td></tr></table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Blood group</label>

                        <div class="col-sm-7">
                            <?php display_blood_group_list("w_blood_group") ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education</label>

                        <div class="col-sm-7">

                            <?php display_qualification("w_qualification") ?>
                        </div>
                    </div>
                    <script>
                        $("#w_qualification").select2();                                       //custom select javascript
                    </script>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="w_education_details">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation</label>
                        <div class="col-sm-7">

                            <?php display_occupation("w_occupation"); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="w_occupation_details">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Email</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="w_email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Kootam</label>

                        <div class="col-sm-7">
                            <?php display_kulam_list("w_kootam") ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Temple</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="w_temple">
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title" >PERMANENT ADDRESS</h3>
                    </div>
                    <style>
                        textarea.form-control{
                            width:320px;
                            height:130px;
                        }
                    </style>
                    <div class="box-body"><br>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Permanent Address</label>

                            <div class="col-sm-7">
                                <textarea type="text" class="form-control" id="inputrole3" name="permanent_address"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Village</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="p_village"name="village">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Taluk</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="p_taluk" name="taluk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">District</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="p_district" name="district">
                            </div>
                        </div>   
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">State</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                        </div>	
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Country</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="p_country" name="country">
                            </div>
                        </div>	                       
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Pincode</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="pincode" name="pincode">
                            </div>
                        </div>	
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-info" >
                    <div class="box-header with-border">
                        <h3 class="box-title" >CURRENT ADDRESS</h3>
                    </div>
                    <div class="box-body"><br>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Current Address</label>

                            <div class="col-sm-7">
                                <textarea type="text" class="form-control" id="inputrole3" name="current_address"> </textarea> 
                            </div> 
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label"> Village</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="c_village"name="c_village">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label"> Taluk</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="c_taluk" name="c_taluk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label"> District</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="c_district" name="c_district">
                            </div>
                        </div>    

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">State</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="c_state" name="c_state">
                            </div>
                        </div>	
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Country</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="c_country" name="c_country">
                            </div>
                        </div>	
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Pincode</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="c_pincode" name="c_pincode">
                            </div>
                        </div>	

                    </div>
                </div>
            </div>
        </div>

        <!-- /.box-body -->
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"> OTHER DETAILS</h3>
                </div>
                <div class="box-body"><br>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Kattalai</label>

                            <div class="col-sm-7">
                                <?php display_kattalai("kattalai"); ?>
                            </div>
                        </div>
                        <script>
                            $("#kattalai").select2();
                        </script>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Kattalai Village</label>

                            <div class="col-sm-7">
                                <?php //display_kattalai_village("k_village"); ?>
                                <input type="text" class="form-control" id="k_village" name="k_village" value="<?php echo $row['k_village'] ?>">
                            </div>
                        </div>
                        <script>
                            $("#k_village").select2();
                        </script>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Pudavai</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="pudavai" name="pudavai">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">IC</label>

                            <div class="col-sm-7">
                                <select name="ic"  class="form-control">
                                    <option selected="selected"> -Select- </option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Remarks</label>
                            <div class="col-sm-7">
                                <textarea   class="form-control" id="inputEmail3" name="remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <button type="button" onclick="window.close()" class="btn btn-info pull-right">Cancel</button> 
            <button type="submit" class="btn btn-info pull-right">Submit</button>
        </div>
        <!-- /.box-footer -->
    </form>
</div>
<!-- /.box -->
<script>
    $("#blood_group").select2();
    $("#w_blood_group").select2();
    $("#w_occupation").select2();
    $("#w_kootam").select2();




    var district = [
<?php
$loc = get_location('district');
echo '"' . implode('","', $loc) . '"';
?>
    ];

    $("#c_district").autocomplete({
        source: district

    });
    $("#p_district").autocomplete({
        source: district

    });


    var village = [
<?php
$loc_p = get_location('village');
$loc_c = get_location('c_village');

$loc = array_unique(array_merge($loc_p, $loc_c));

echo '"' . implode('","', $loc) . '"';
?>
    ];

    $("#c_village").autocomplete({
        source: village

    });

    $("#p_village").autocomplete({
        source: village

    });


    var k_village = [
<?php
$loc = get_location('k_village');


echo '"' . implode('","', $loc) . '"';
?>
    ];

    $("#k_village").autocomplete({
        source: k_village

    });

    var pudavai = [
<?php
$loc = get_autosuggest('pudavai');


echo '"' . implode('","', $loc) . '"';
?>
    ];

    $("#pudavai").autocomplete({
        source: pudavai

    });
</script>
<div style="clear:both"></div>
</div>
<?php
include('../footer.php');
?>