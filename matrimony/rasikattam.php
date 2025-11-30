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

$msg = '';

if (count($_POST) && $_POST['r_sooriyan'] != '') {
    $r_sevvai = $_POST['r_sevvai'];
    $r_sooriyan = $_POST['r_sooriyan'];
    $r_chandran = $_POST['r_chandran'];
    $r_budhan = $_POST['r_budhan'];
    $r_guru = $_POST['r_guru'];
    $r_raaghu = $_POST['r_raaghu'];
    $r_kaedhu = $_POST['r_kaedhu'];
    $r_sani = $_POST['r_sani'];
    $r_sukkiran = $_POST['r_sukkiran'];
    $r_laknam = $_POST['r_laknam'];


//var_dump($row1);
    if ($row1 == false) {
        $sql = "INSERT INTO `$tbl_kattam`(`m_id`,`r_sevvai`, `r_sooriyan`, `r_chandran`, `r_budhan`, `r_guru`, `r_raaghu`,`r_kaedhu`, `r_sani`, `r_sukkiran`, `r_laknam`) 
					VALUES ('$m_id','$r_sevvai', '$r_sooriyan', '$r_chandran', '$r_budhan', '$r_guru', '$r_raaghu', '$r_kaedhu', '$r_sani', '$r_sukkiran', '$r_laknam')";
//echo $sql;
        $msg = "Successfully Added";
    } else {
        $sql = "UPDATE `$tbl_kattam` SET `r_sevvai`='$r_sevvai', `r_sooriyan`='$r_sooriyan', `r_chandran`='$r_chandran',`r_budhan`='$r_budhan',`r_guru`='$r_guru',`r_raaghu`='$r_raaghu', `r_kaedhu`='$r_kaedhu',`r_sani`='$r_sani',`r_sukkiran`='$r_sukkiran',`r_laknam`='$r_laknam' WHERE `m_id` ='$m_id'";
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
        $r_sooriyan = $row1['r_sooriyan'];
        $r_chandran = $row1['r_chandran'];
        $r_budhan = $row1['r_budhan'];
        $r_guru = $row1['r_guru'];
        $r_raaghu = $row1['r_raaghu'];
        $r_kaedhu = $row1['r_kaedhu'];
        $r_sani = $row1['r_sani'];
        $r_sukkiran = $row1['r_sukkiran'];
        $r_laknam = $row1['r_laknam'];
    } else {
        $r_sevvai = '';
        $r_sooriyan = '';
        $r_chandran = '';
        $r_budhan = '';
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
                <style>


                    input[type="radio"] {

                        border:1px solid red;
                        /*-moz-opacity:0;
                      filter:alpha(opacity:0);
                      opacity:0;
                      outline:none;
                        */
                        position:relative;
                        top: 0px;
                        left:0px;
                        display:none;

                    }

                    input[type="radio"] + label {

                        background-color: lightblue;
                        cursor: pointer; 
                        display: block;
                        text-align: center;
                        margin:0px;
                        padding:5px;

                    }
                    input[type="radio"]:checked + label {

                        background-color: #0a243e;
                        color:white;
                    }

                </style>

                <script>
                    $("label").click(function (e) {
                        e.preventDefault();
                        $("#" + $(this).attr("for")).click().change();
                    });
                </script>

                <div class="box">
                    <br>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tbody>
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i ?> </td>
                                        <td><input   id="r_sooriyan_<?php echo $i ?>"     type="radio" name="r_sooriyan"    value="<?php echo $i ?>"  <?php // if($kattam->r_sooriyan == $i) echo " checked "  ?> > <label for="r_sooriyan_<?php echo $i ?>"   >சூ</label></td>
                                        <td><input   id="r_chandran_<?php echo $i ?>"   type="radio" name="r_chandran"   value="<?php echo $i ?>" <?php //  if($kattam->r_chandran == $i) echo " checked "   ?>> <label for="r_chandran_<?php echo $i ?>" > சந்</label></td>
                                        <td><input   id="r_sevvai_<?php echo $i ?>"         type="radio" name="r_sevvai"        value="<?php echo $i ?>" <?php //if($kattam->r_sevvai == $i) echo " checked "   ?>> <label for="r_sevvai_<?php echo $i ?>"      >செவ்</label></td>
                                        <td><input   id="r_guru_<?php echo $i ?>"            type="radio" name="r_guru"            value="<?php echo $i ?>" <?php // if($kattam->r_guru == $i) echo " checked "   ?>> <label for="r_guru_<?php echo $i ?>"         >குரு</label></td>
                                        <td><input   id="r_budhan_<?php echo $i ?>"        type="radio" name="r_budhan"       value="<?php echo $i ?>" <?php // if($kattam->r_budhan == $i) echo " checked "   ?>> <label for="r_budhan_<?php echo $i ?>"     >புத</label></td>
                                        <td><input   id="r_sukkiran_<?php echo $i ?>"      type="radio" name="r_sukkiran"    value="<?php echo $i ?>" <?php // if($kattam->r_sukkiran == $i) echo " checked "   ?>> <label for="r_sukkiran_<?php echo $i ?>"  >சுக்</label></td>
                                        <td><input   id="r_sani_<?php echo $i ?>"              type="radio" name="r_sani"           value="<?php echo $i ?>" <?php //  if($kattam->r_sani == $i) echo " checked "   ?>> <label for="r_sani_<?php echo $i ?>"          >சனி</label></td>
                                        <td><input   id="r_raaghu_<?php echo $i ?>"        type="radio" name="r_raaghu"       value="<?php echo $i ?>" <?php //  if($kattam->r_raaghu == $i) echo " checked "   ?>> <label for="r_raaghu_<?php echo $i ?>"    >ராகு</label></td>
                                        <td><input   id="r_kaedhu_<?php echo $i ?>"        type="radio" name="r_kaedhu"     value="<?php echo $i ?>" <?php // if($kattam->r_kaedhu == $i) echo " checked "   ?>> <label for="r_kaedhu_<?php echo $i ?>"    >கேது</label></td>
                                        <td><input   id="r_laknam_<?php echo $i ?>"        type="radio" name="r_laknam"      value="<?php echo $i ?>" <?php //  if($kattam->r_laknam == $i) echo " checked "   ?>> <label for="r_laknam_<?php echo $i ?>"     >லக்</label></td>
                                    </tr>

                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="hidden" name="editrasi" >
            <input type="hidden" name="p_id" value="<?php echo $p_id; ?>">
            <button type="button" onclick="window.close()" class="btn btn-info pull-right">Cancel</button> 
            <button   type="submit"  id="submit" class="btn btn-info pull-right">Submit</button>
        </div>    
    </form>


</div>

<input type="hidden" class="form-control" id="inputusername3" name="m_id" value="<?php echo $m_id; ?>" >

</form>
</div>
<div style="clear:both"></div>
</div>
