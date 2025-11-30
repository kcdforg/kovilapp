<?php
//echo date('Y-m-d H:i:s');
include('../popupheader.php');
//var_dump($_SESSION);
$c_created_date = date('Y-m-d H:i:s');
$c_created_by = $_SESSION['username'];
$m_id = $_GET['m_id'];
//echo count($_POST);
//var_dump($_POST);

$sql1 = "SELECT * FROM `kattam` WHERE `m_id`='$m_id'";
//echo $sql1;
$result1 = mysql_query($sql1);
//var_dump($result1);
$row1 = mysql_fetch_array($result1);

$msg= '';

if (count($_POST) && $_POST['r_suriyan'] != '') {
    $r_sevvai = $_POST['r_sevvai'];
    $r_suriyan = $_POST['r_suriyan'];
    $r_chandran = $_POST['r_chandran'];
    $r_buthan = $_POST['r_buthan'];
    $r_guru = $_POST['r_guru'];
    $r_raaghu = $_POST['r_raaghu'];
    $r_kaedhu = $_POST['r_kaedhu'];
    $r_sani = $_POST['r_sani'];
    $r_sukkiran = $_POST['r_sukkiran'];
    $r_laknam = $_POST['r_laknam'];


//var_dump($row1);
    if ($row1 == false) {
        $sql = "INSERT INTO `$tbl_kattam`(`m_id`,`r_sevvai`, `r_sooriyan`, `r_chandran`, `r_budhan`, `r_guru`, `r_raaghu`,`r_kaedhu`, `r_sani`, `r_sukkiran`, `r_laknam`) 
					VALUES ('$m_id','$r_sevvai', '$r_suriyan', '$r_chandran', '$r_buthan', '$r_guru', '$r_raaghu', '$r_kaedhu', '$r_sani', '$r_sukkiran', '$r_laknam')";
//echo $sql;
        $msg = "Successfully Added";
    } else {
        $sql = "UPDATE `$tbl_kattam` SET `r_sevvai`='$r_sevvai', `r_sooriyan`='$r_suriyan', `r_chandran`='$r_chandran',`r_budhan`='$r_buthan',`r_guru`='$r_guru',`r_raaghu`='$r_raaghu', `r_kaedhu`='$r_kaedhu',`r_sani`='$r_sani',`r_sukkiran`='$r_sukkiran',`r_laknam`='$r_laknam' WHERE `m_id` ='$m_id'";
//echo $sql;
        $msg = "Updated Successfully ";
    }


    if (!mysql_query($sql, $con)) {
        die('Error: ' . mysql_error());
    }
    //var_dump($sql);
    
} else {
    //initialise from db
    if ($row1 == true) {
        $r_sevvai = $row1['r_sevvai'];
        $r_suriyan = $row1['r_sooriyan'];
        $r_chandran = $row1['r_chandran'];
        $r_buthan = $row1['r_budhan'];
        $r_guru = $row1['r_guru'];
        $r_raaghu = $row1['r_raaghu'];
        $r_kaedhu = $row1['r_kaedhu'];
        $r_sani = $row1['r_sani'];
        $r_sukkiran = $row1['r_sukkiran'];
        $r_laknam = $row1['r_laknam'];
    } else {
        $r_sevvai = '';
        $r_suriyan = '';
        $r_chandran = '';
        $r_buthan = '';
        $r_guru = '';
        $r_raaghu = '';
        $r_kaedhu = '';
        $r_sani = '';
        $r_sukkiran = '';
        $r_laknam = '';
    }
}

echo $msg;
?>

<div class="container-fluid">
    <h2 class="container text-center"> Rasinilai </h2>
</div>

<div class="col-sm-12">
    <form class="form-horizontal" method="post">
        <!-- form start -->
        <!-- Horizontal Form -->
        <div class="box box-info" >          
            <!-- /.box-header -->                   
            <div class="box-body">
                <br>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Sevvai</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_sevvai" value="<?php echo $r_sevvai ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Suriyan</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_suriyan" value="<?php echo $r_suriyan ?>" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Chandran</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_chandran" value="<?php echo $r_chandran ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Buthan</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_buthan"value="<?php echo $r_buthan ?>" >
                        </div>
                    </div>				
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Guru</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_guru" value="<?php echo $r_guru ?>" >
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">	
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Raaghu</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_raaghu" value="<?php echo $r_raaghu ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Kaedhu</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_kaedhu" value="<?php echo $r_kaedhu ?>" >
                        </div>
                    </div>			
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Sani</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_sani" value="<?php echo $r_sani ?>">
                        </div>
                    </div>				
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Sukkiran</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_sukkiran" value="<?php echo $r_sukkiran ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Laknam</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="r_laknam" value="<?php echo $r_laknam ?>" >
                        </div>
                    </div>
                </div>	
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Cancel</button> 
            <button type="submit" class="btn btn-info pull-right">submit</button>
        </div>
</div>

<input type="hidden" class="form-control" id="inputusername3" name="m_id" value="<?php echo $m_id; ?>" >

</form>
</div>
<div style="clear:both"></div>
</div>


