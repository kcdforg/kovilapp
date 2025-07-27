<?php
include('../header.php');

$id = $_GET['id'];
$error_msg = '';
$success_msg = '';
if (count($_POST) > 0) {
    $res = update_horoscope($id, $_POST);
    if ($res) {
        $success_msg = "Updated Successfully ";
    } else {
        $error_msg = 'Error: ' . mysql_error();
    }
}

//$result = mysql_query("SELECT * FROM matrimony WHERE`id`=$id");
//$row = mysql_fetch_array($result);
$row = get_horoscope($id);
?>

<div class="container-fluid" id="top">
    <h2 class="container text-center">Update Horoscope</h2>
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
<div id="fill"class="col-md-12">
    <form class="form-horizontal" method="post">
        <!-- form start -->
        <!-- Horizontal Form -->
        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title"><b>Personal Details</b></h3>
            </div>			
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Name</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputusername3" name="name"  value="<?php echo $row['name'] ?>" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Gender</label>

                        <div class="col-sm-3">
                            <?php display_gender("gender", $row['gender']); ?>
                        </div>
                        <label for="inputPassword3" class="col-sm-1 control-label">Age</label>

                        <div class="col-sm-2">
                            <?php display_age("age", $row['age']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Height</label>

                        <div class="col-sm-3">
                            <?php display_height_horo("height", $row['height']); ?>
                        </div>
                        <label for="inputEmail3" class="col-sm-1 control-label">Weight</label>

                        <div class="col-sm-2">
                            <?php display_weight("weight", $row['weight']); ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Bloodgroup</label>
                        <div class="col-sm-3">
                            <?php display_blood_group_list("blood_group", $row['blood_group']); ?>
                        </div>
                        <label for="inputEmail3" class="col-sm-1 control-label">Colour</label>

                        <div class="col-sm-3">
                            <?php display_colour("colour", $row['colour']); ?>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Marital Status</label>

                        <div class="col-sm-7">
                            <?php display_marital_status("marital_status", $row['marital_status']); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Workplace</label>

                        <div class="col-sm-7">
                            <?php display_workplace($name = "country", $row['country']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Address</label>

                        <div class="col-sm-7">
                            <textarea class="form-control" name="address"><?php echo $row['address'] ?></textarea>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education</label>

                        <div class="col-sm-7">
                            <?php display_qualification("qualification", $row['qualification']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Education Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="education_details" value="<?php echo $row['education_details'] ?>">
                        </div>
                    </div>	

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation</label>

                        <div class="col-sm-7">
                            <?php display_occupation($name = "occupation", $row['occupation']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Occupation Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="occupation_details"  value="<?php echo $row['occupation_details'] ?>">
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">College Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="college_details"  value="<?php echo $row['college_details'] ?>">
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Income</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputEmail3" name="income" value="<?php echo $row['income'] ?>"> 
                        </div>
                        <div class="col-sm-3" style="padding-left:0px;">
                            per month
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Asset Details</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="asset_details"  value="<?php echo $row['asset_details'] ?>">
                        </div>
                    </div>	


                </div>
            </div>
        </div>

        <div class="box box-info" >
            <div class="box-header with-border">
                <h3 class="box-title"><b>Contact Details</b></h3>
            </div>			
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Mobile No</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="inputEmail3" name="mobile_no" value="<?php echo $row['mobile_no'] ?>">
                        </div>
                    </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Email</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="email" value="<?php echo $row['email'] ?>">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Contact Person</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="contact_person" value="<?php echo $row['contact_person'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Relationship</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="relationship" value="<?php echo $row['relationship'] ?>">
                            </div>
                        </div> 

                    </div>	
                </div>	
            </div>	



            <div class="box box-info" >
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Horoscope Details</b></h3>
                </div>			
                <!-- /.box-header -->                   
                <div class="box-body">
                    <br>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Birth Date</label>

                            <div class="col-sm-7">
                                <table>
                                    <tr>
                                        <td >
                                            <?php display_date("birth_date[day]", $row['birth_date']) ?>
                                        </td>
                                        <td>
                                            <?php display_month("birth_date[month]", $row['birth_date']) ?>  
                                        </td> 
                                        <td>
                                            <?php display_year("birth_date[year]", $row['birth_date']) ?>   
                                        </td></tr></table>
                            </div>
                        </div>							
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Birth Time</label>

                            <div class="col-sm-7">

                                <div class="col-sm-2" style="padding: 0px;"> <?php display_hour("birth_time[hour]", $row['birth_time']) ?>  </div><div class="col-sm-1" style="padding: 3px 3px;">HH</div>

                                <div class="col-sm-2" style="padding: 0px;"><?php display_minute("birth_time[min]", $row['birth_time']) ?>  </div>         <div class="col-sm-1" style="padding: 3px 3px;">Min </div>  
                                <?php //display_time("birth_time[ampm]", $row['birth_time']) ?>   

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Birth Place</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="birth_place"  value="<?php echo $row['birth_place'] ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Raghu / Kedhu</label>

                            <div class="col-sm-2">
                                <?php display_raghu_kedhu_checkbox('raaghu_kaedhu', $row['raaghu_kaedhu'], "margin-top:12px;") ?>
                            </div>

                            <label for="inputEmail3" class="col-sm-2 control-label text-right">Sevvai</label>

                            <div class="col-sm-1">
                                <?php display_sevvai_checkbox('sevvai', $row['sevvai'], "margin-top:12px;") ?>


                            </div>
                        </div>


                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Raasi</label>

                            <div class="col-sm-7">
                                <?php display_raasi("raasi", $row['raasi']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Laknam</label>

                            <div class="col-sm-7">
                                <?php display_raasi("laknam", $row['laknam']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Star</label>

                            <div class="col-sm-7">
                                <?php display_star("star", $row['star']); ?>
                            </div>
                        </div>						

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Padham</label>

                            <div class="col-sm-2">
                                <?php display_padham("padham", $row['padham']); ?>
                            </div>
                        </div>		

                    </div>
                </div>
            </div>

            <div class="box box-info" >
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Family Details</b></h3>
                </div>			
                <!-- /.box-header -->                   
                <div class="box-body">
                    <br> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Father's name</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="father_name" value="<?php echo $row['father_name'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Father's occupation</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="f_occupation" value="<?php echo $row['f_occupation'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-4 control-label">Mother's name</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputPassword3" name="mother_name"  value="<?php echo $row['mother_name'] ?>">
                            </div>
                        </div>		
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Mother's occupation</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="m_occupation" value="<?php echo $row['m_occupation'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Sibling</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="sibling" value="<?php echo $row['sibling'] ?>">
                            </div>
                        </div>


                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Kulam</label>

                            <div class="col-sm-7">
                                <?php display_kulam_list("kulam", $row['kulam']); ?>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Temple</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="temple" value="<?php echo $row['temple'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Maternal Kulam</label>

                            <div class="col-sm-7">
                                <?php display_kulam_list("m_kulam", $row['m_kulam']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Paternal Mother Kulam</label>

                            <div class="col-sm-7">
                                <?php display_kulam_list("pm_kulam", $row['pm_kulam']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Maternal Mother Kulam</label>
                            <div class="col-sm-7">
                                <?php display_kulam_list("mm_kulam", $row['mm_kulam']); ?>
                            </div>
                        </div>		
                    </div>
                </div>
            </div>

            <div class="box box-info" >
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Other Details</b></h3>
                </div>			
                <!-- /.box-header -->                   
                <div class="box-body">
                    <br> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">About Myself</label>

                            <div class="col-sm-7">
                                <textarea class="form-control"  name="about_myself" style="width:400px; height:150px; background:snow;"><?php echo $row['about_myself'] ?></textarea>

                            </div>				
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php /*
                          <div class="form-group">
                          <label for="inputEmail3" class="col-sm-4 control-label">Status</label>

                          <div class="col-sm-7">
                          <select name="status" class="form-control" >
                          <option selected="selected"> <?php echo $row['status'] ?> </option>
                          <option>Open</option>
                          <option>Closed</option>
                          </select>
                          </div>
                          </div>
                         */ ?>
                    </div>
                </div>
            </div>


            <div class="box box-info" >
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Expectation</b></h3>
                </div>
                <!-- /.box-header -->                   
                <div class="box-body">
                    <br>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Education</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputusername3" name="pp_education"   value="<?php echo $row['pp_salary'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Occupation</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputusername3" name="pp_occupation"  value="<?php echo $row['pp_salary'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Work Location</label>

                            <div class="col-sm-7">
                                <?php display_workplace($name = "pp_work_location", $row['pp_work_location']); ?>
                            </div>
                        </div>				
                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Salary</label>

                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="inputEmail3" name="pp_salary" value="<?php echo $row['pp_salary'] ?>">
                            </div>
                            <div class="col-sm-3" style="padding-left:0px;">
                                per month
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Asset details</label>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputEmail3" name="pp_asset_details" value="<?php echo $row['pp_asset_details'] ?>">
                            </div>
                        </div>	

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Other Expectations</label>

                            <div class="col-sm-7">
                                <textarea class="form-control" style="height:90px;" name="pp_expectation"><?php echo $row['pp_expectation'] ?></textarea>
                            </div>
                        </div>				
                    </div>
                </div>
            </div>  
        </div>
        <script>
            $("#occupation").select2();
            $("#qualification").select2();
            $("#star").select2();
            $("#kulam").select2();
            $("#m_kulam").select2();
            $("#pm_kulam").select2();
            $("#mm_kulam").select2();

        </script>

        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Cancel</button> 
            <button type="submit" class="btn btn-info pull-right">Update</button>
        </div>
        <!-- /.box-footer -->
    </form>
</div>
<div style="clear:both"></div>
<!-- /.box -->
<?php
include('../footer.php');
?>	
