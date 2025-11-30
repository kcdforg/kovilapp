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
//vaa_dump($result1);
$row1 = mysql_fetch_array($result1);

$msg ='';

if (count($_POST) && $_POST['a_suriyan'] != '') {
    $a_sevvai = $_POST['a_sevvai'];
    $a_suriyan = $_POST['a_sooriyan'];
    $a_chandran = $_POST['a_chandran'];
    $a_buthan = $_POST['a_buthan'];
    $a_guru = $_POST['a_guru'];
    $a_raaghu = $_POST['a_raaghu'];
    $a_kaedhu = $_POST['a_kaedhu'];
    $a_sani = $_POST['a_sani'];
    $a_sukkiran = $_POST['a_sukkiran'];
    $a_laknam = $_POST['a_laknam'];


//vaa_dump($row1);
    if ($row1 == false) {
        $sql = "INSERT INTO `$tbl_kattam`(`m_id`,`a_sevvai`, `a_sooriyan`, `a_chandran`, `a_budhan`, `a_guru`, `a_raaghu`,`a_kaedhu`, `a_sani`, `a_sukkiran`, `a_laknam`) 
					VALUES ('$m_id','$a_sevvai', '$a_suriyan', '$a_chandran', '$a_buthan', '$a_guru', '$a_raaghu', '$a_kaedhu', '$a_sani', '$a_sukkiran', '$a_laknam')";
//echo $sql;
        $msg = "Successfully Added";
    } else {
        $sql = "UPDATE `$tbl_kattam` SET `a_sevvai`='$a_sevvai', `a_sooriyan`='$a_suriyan', `a_chandran`='$a_chandran',`a_buthan`='$a_buthan',`a_guru`='$a_guru',`a_raaghu`='$a_raaghu', `a_kaedhu`='$a_kaedhu',`a_sani`='$a_sani',`a_sukkiran`='$a_sukkiran',`a_laknam`='$a_laknam' WHERE `m_id` ='$m_id'";
//echo $sql;
        $msg = "Updated Successfully ";
    }


    if (!mysql_query($sql, $con)) {
        die('Error: ' . mysql_error());
    }
    //vaa_dump($sql);
    
} else {
    //initialise from db
    if ($row1 == true) {
        $a_sevvai = $row1['a_sevvai'];
        $a_suriyan = $row1['a_sooriyan'];
        $a_chandran = $row1['a_chandran'];
        $a_buthan = $row1['a_buthan'];
        $a_guru = $row1['a_guru'];
        $a_raaghu = $row1['a_raaghu'];
        $a_kaedhu = $row1['a_kaedhu'];
        $a_sani = $row1['a_sani'];
        $a_sukkiran = $row1['a_sukkiran'];
        $a_laknam = $row1['a_laknam'];
    } else {
        $a_sevvai = '';
        $a_suriyan = '';
        $a_chandran = '';
        $a_buthan = '';
        $a_guru = '';
        $a_raaghu = '';
        $a_kaedhu = '';
        $a_sani = '';
        $a_sukkiran = '';
        $a_laknam = '';
    }
}
echo $msg;

?>

<div class="container-fluid">
    <h2 class="container text-center">Navamsam </h2>
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
                            <input type="text" class="form-control" id="inputusername3" name="a_sevvai" value="<?php echo $a_sevvai ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Suriyan</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_suriyan" value="<?php echo $a_suriyan ?>" >
                        </div>
                    </div>                               
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Chandran</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_chandran" value="<?php echo $a_chandran ?>"  >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Buthan</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_buthan"value="<?php echo $a_buthan ?>">
                        </div>
                    </div>				
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Guru</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_guru" value="<?php echo $a_guru ?>"  >
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">	
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Raaghu</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_raaghu" value="<?php echo $a_raaghu ?>"  >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Kaedhu</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_kaedhu" value="<?php echo $a_kaedhu ?>"  >
                        </div>
                    </div>			
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Sani</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_sani" value="<?php echo $a_sani ?>" >
                        </div>
                    </div>				
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Sukkiran</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_sukkiran" value="<?php echo $a_sukkiran ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Laknam</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputusername3" name="a_laknam" value="<?php echo $a_laknam ?>"  >
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

<input type="hidden" class="form-control" id="inputusername3" name="m_id" value="<?php echo $m_id; ?>">

</form>
</div>
<div style="clear:both"></div>
</div>


