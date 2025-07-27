<?php
include('../popupheader.php');
if (!isset($_GET['ref_id'])) {
    echo "Horoscope can be uploaded only from Member Page";
    exit();
}

$error_msg = '';
$success_msg = '';
$ref_id = $_GET['ref_id'];
//var_dump($_POST);
if (count($_POST) && $_POST['name'] != '') {
    $res = add_horoscope($_POST);
    if ($res) {
        $success_msg = "Added Successfully ";
    } else {
        $error_msg = 'Error: ' . mysql_error();
    }
}
?>

<div class="container-fluid">
    <h2 class="container text-center">Add Horoscope</h2>
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
<div id="fill" class="col-md-12">
    <form class="form-horizontal" method="post">
        <!-- form start -->
        <!-- Horizontal Form -->
        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title"><b>PERSONAL DETAILS</b></h3>
            </div>
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputusername3" name="name"  value="" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Gender</label>

                        <div class="col-sm-3">
                            <?php display_gender("gender"); ?>
                        </div>
                        <label for="inputPassword3" class="col-sm-1 control-label">Age</label>

                        <div class="col-sm-3">
                            <?php display_age("age"); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Height</label>

                        <div class="col-sm-3">
                            <?php display_height_horo("height"); ?>
                        </div>
                        <label for="inputEmail3" style="width:9%" class="col-sm-2 control-label">Weight</label>

                        <div class="col-sm-3">
                            <?php display_weight("weight"); ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Bloodgroup</label>
                        <div class="col-sm-3">
                            <?php display_blood_group_list("blood_group"); ?>
                        </div>
                        <label for="inputEmail3" style="width:9%" class="col-sm-2 control-label">Colour</label>

                        <div class="col-sm-3">
                            <?php display_colour("colour"); ?>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Marital Status</label>

                        <div class="col-sm-7">
                            <?php display_marital_status("marital_status"); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Asset details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="asset_details" value="">
                        </div>
                    </div>	
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education</label>

                        <div class="col-sm-7">
                            <?php display_qualification("qualification") ?>
                        </div>
                    </div>
                    <script>
                        $("#qualification").select2();
                    </script>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="education_details" value="">
                        </div>
                    </div>	

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation</label>
                        <script>
                            $("#occupation").select2();
                        </script>
                        <div class="col-sm-7">
                            <?php display_occupation("occupation"); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="occupation_details"  value="">
                        </div>
                    </div>		
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">College details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="college_details" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Income</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputEmail3" name="income" value=""> 
                        </div>
                        <div class="col-sm-3" style="padding-left:0px;">
                            per month
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Workplace</label>

                        <div class="col-sm-7">
                            <?php display_workplace("country"); ?>
                        </div>
                    </div>				
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Address</label>

                        <div class="col-sm-7">
                            <textarea class="form-control" style="height:90px;" name="address"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title"> <b>CONTACT DETAILS</b></h3>
            </div>
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">MobileNo</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="mobile_no" value="">
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Email</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="email" value="">
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Contact Person</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="contact_person" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Relationship</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="relationship" value="">
                        </div>
                    </div> 
                </div>	
            </div>
        </div>
        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title"> <b>FAMILY DETAILS</b></h3>
            </div>
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Father's name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="father_name"  placeholder="Father's name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">Mother's name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputPassword3" name="mother_name"  placeholder="Mother's name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Father's Occupation</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="f_occupation" placeholder="Father's Occupation">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Mother's Occupation</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="m_occupation" placeholder="Mother's Occupation">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Sibling</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="sibling" placeholder="Sibling">
                        </div>
                    </div>				
                </div>
                <div class="col-md-6">             
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Kulam</label>

                        <div class="col-sm-7">
                            <?php display_kulam_list("kulam"); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Temple</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="temple" placeholder="Temple">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Mother's Kulam</label>

                        <div class="col-sm-7">
                            <?php display_kulam_list("m_kulam"); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Maternal Mother's Kulam</label>

                        <div class="col-sm-7">
                            <?php display_kulam_list("mm_kulam"); ?>
                        </div>
                    </div>			
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Paternal Mother's Kulam</label>

                        <div class="col-sm-7">
                            <?php display_kulam_list("pm_kulam"); ?>
                        </div>
                    </div>				
                </div>
            </div>
        </div>

        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title"> <b>HOROSCOPE DETAILS</b></h3>
            </div>
            <div class="box-body">
                <br>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Birth Date</label>

                        <div class="col-sm-7">
                            <table>
                                <tr>
                                    <td >
                                        <?php display_date("birth_date[day]") ?>
                                    </td>
                                    <td>
                                        <?php display_month("birth_date[month]") ?>  
                                    </td> 
                                    <td>
                                        <?php display_year("birth_date[year]") ?>   
                                    </td></tr></table>
                        </div>
                    </div>							
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Birth Time</label>

                        <div class="col-sm-7">

                            <div class="col-sm-3" style="padding: 0px;"><?php display_hour("birth_time[hour]") ?>  </div><div class="col-sm-1" style="padding: 3px 3px;">HH</div>

                            <div class="col-sm-3" style="padding: 0px;"><?php display_minute("birth_time[min]") ?>  </div>         <div class="col-sm-1" style="padding: 3px 3px;">Min </div>  
                            <?php //display_time("birth_time[ampm]", $row['birth_time']) ?>   

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Birth Place</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="birth_place"  value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Raghu / Kedhu</label>

                        <div class="col-sm-2">
                            <?php display_raghu_kedhu_checkbox('raaghu_kaedhu', '', "margin-top:12px;") ?>



                        </div>


                        <label for="inputEmail3" class="col-sm-2 control-label text-right">Sevvai</label>

                        <div class="col-sm-1">
                            <?php display_sevvai_checkbox('sevvai', '', "margin-top:12px;") ?>


                        </div>
                    </div>


                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Raasi</label>

                        <div class="col-sm-7">
                            <?php display_raasi("raasi"); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Laknam</label>

                        <div class="col-sm-7">
                            <?php display_raasi("laknam"); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Star</label>

                        <div class="col-sm-7">
                            <?php display_star("star"); ?>
                        </div>
                    </div>						

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Padham</label>

                        <div class="col-sm-3">
                            <?php display_padham("padham"); ?>
                        </div>
                    </div>		

                </div>
            </div>
        </div>

        <!-- /.box-body -->
        <?php /*
          <div class="box box-info">
          <div class="box-header with-border">
          <h3 class="box-title"> <b>OTHER DETAILS</b></h3>
          </div>
          <div class="box-body">	<br>
          <div class="col-md-6">
          <div class="form-group">
          <label for="inputEmail3" class="col-sm-4 control-label">Registered Date</label>

          <div class="col-sm-7">
          <table>
          <tr>
          <td >
          <?php display_date($name = "registered_date") ?>
          </td>
          <td>
          <?php display_month($name = "registered_date") ?>
          </td>
          <td>
          <?php display_year($name = "registered_date") ?>
          </td></tr></table>
          </div>
          </div>


          </div>

          <div class="col-md-6">
          <div class="form-group">
          <label for="inputEmail3" class="col-sm-4 control-label">About Myself</label>

          <div class="col-sm-7">
          <input type="text" class="form-control" id="inputrole3" name="about_myself" placeholder="About Myself">
          </div>
          </div>

          </div>
          </div>
          </div>
         */
        ?>

        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title"><b>REFERRER DETAILS</b></h3>
            </div>			
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Referrer name :</label>

                            <div class="col-sm-7">
                                <?php echo $_GET['ref_name'] ?>
                                <input type="hidden" name="ref_id" value="<?php echo $_GET['ref_id'] ?>">
                            </div>
                        </div>	
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Ref mobile no :</label>

                            <div class="col-sm-7">
                                <?php echo $_GET['mbl_no'] ?>
                            </div>
                        </div>	
                    </div>
                </div>
                <div class="row" >
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Referrer Address :</label>
                            <div class="col-sm-7">
                                <?php echo $_GET['ref_address'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title"><b>Expectation </b></h3>
            </div>
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputusername3" name="pp_education"   >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputusername3" name="pp_occupation"   >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Work Location</label>

                        <div class="col-sm-7">
                            <?php display_workplace("pp_work_location"); ?>
                        </div>
                    </div>				
                </div>

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Salary</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputEmail3" name="pp_salary" >
                        </div>
                        <div class="col-sm-3" style="padding-left:0px;">
                            per month
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Asset details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="pp_asset_details" >
                        </div>
                    </div>	

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Other Expectations</label>

                        <div class="col-sm-7">
                            <textarea class="form-control" style="height:90px;" name="pp_expectation"></textarea>
                        </div>
                    </div>				
                </div>
            </div>
        </div>
</div>

<div class="box-footer">
    <button type="button" onclick="window.close()" class="btn btn-info pull-right">Cancel</button> 
    <button type="submit" class="btn btn-info pull-right">submit</button>
</div>
<!-- /.box-footer -->
</form>
</div>
<div style="clear:both"></div>
</div>
<?php
include('../footer.php');
?>