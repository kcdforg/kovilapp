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

$msg = '';

if (count($_POST) && $_POST['a_sooriyan'] != '') {
    $a_sevvai = $_POST['a_sevvai'];
    $a_sooriyan = $_POST['a_sooriyan'];
    $a_chandran = $_POST['a_chandran'];
    $a_budhan = $_POST['a_budhan'];
    $a_guru = $_POST['a_guru'];
    $a_raaghu = $_POST['a_raaghu'];
    $a_kaedhu = $_POST['a_kaedhu'];
    $a_sani = $_POST['a_sani'];
    $a_sukkiran = $_POST['a_sukkiran'];
    $a_laknam = $_POST['a_laknam'];


//vaa_dump($row1);
    if ($row1 == false) {
        $sql = "INSERT INTO `$tbl_kattam`(`m_id`,`a_sevvai`, `a_sooriyan`, `a_chandran`, `a_budhan`, `a_guru`, `a_raaghu`,`a_kaedhu`, `a_sani`, `a_sukkiran`, `a_laknam`) 
                                                 VALUES ('$m_id','$a_sevvai', '$a_sooriyan', '$a_chandran', '$a_budhan', '$a_guru', '$a_raaghu', '$a_kaedhu', '$a_sani', '$a_sukkiran', '$a_laknam')";
//echo $sql;
        $msg = "Successfully Added";
    } else {
        $sql = "UPDATE `$tbl_kattam` SET `a_sevvai`='$a_sevvai', `a_sooriyan`='$a_sooriyan', `a_chandran`='$a_chandran',`a_budhan`='$a_budhan',`a_guru`='$a_guru',`a_raaghu`='$a_raaghu', `a_kaedhu`='$a_kaedhu',`a_sani`='$a_sani',`a_sukkiran`='$a_sukkiran',`a_laknam`='$a_laknam' WHERE `m_id` ='$m_id'";
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
        $a_sooriyan = $row1['a_sooriyan'];
        $a_chandran = $row1['a_chandran'];
        $a_budhan = $row1['a_budhan'];
        $a_guru = $row1['a_guru'];
        $a_raaghu = $row1['a_raaghu'];
        $a_kaedhu = $row1['a_kaedhu'];
        $a_sani = $row1['a_sani'];
        $a_sukkiran = $row1['a_sukkiran'];
        $a_laknam = $row1['a_laknam'];
    } else {
        $a_sevvai = '';
        $a_sooriyan = '';
        $a_chandran = '';
        $a_budhan = '';
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
                <table class="table table-bordered">
                    <tbody>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            ?>
                            <tr>
                                <td><?php echo $i ?> </td>
                                <td><input   id="a_sooriyan_<?php echo $i ?>"     type="radio" name="a_sooriyan"    value="<?php echo $i ?>"  <?php //f ($kattam->a_sooriyan == $i) echo " checked "  ?> > <label for="a_sooriyan_<?php echo $i ?>"   >சூ</label></td>
                                <td><input   id="a_chandran_<?php echo $i ?>"   type="radio" name="a_chandran"   value="<?php echo $i ?>" <?php //if ($kattam->a_chandran == $i) echo " checked "  ?>> <label for="a_chandran_<?php echo $i ?>" > சந்</label></td>
                                <td><input   id="a_sevvai_<?php echo $i ?>"         type="radio" name="a_sevvai"        value="<?php echo $i ?>" <?php //if ($kattam->a_sevvai == $i) echo " checked "  ?>> <label for="a_sevvai_<?php echo $i ?>"      >செவ்</label></td>
                                <td><input   id="a_guru_<?php echo $i ?>"            type="radio" name="a_guru"            value="<?php echo $i ?>" <?php //if ($kattam->a_guru == $i) echo " checked "  ?>> <label for="a_guru_<?php echo $i ?>"         >குரு</label></td>
                                <td><input   id="a_budhan_<?php echo $i ?>"        type="radio" name="a_budhan"       value="<?php echo $i ?>" <?php //if ($kattam->a_budhan == $i) echo " checked "  ?>> <label for="a_budhan_<?php echo $i ?>"     >புத</label></td>
                                <td><input   id="a_sukkiran_<?php echo $i ?>"      type="radio" name="a_sukkiran"    value="<?php echo $i ?>" <?php //if ($kattam->a_sukkiran == $i) echo " checked "  ?>> <label for="a_sukkiran_<?php echo $i ?>"  >சுக்</label></td>
                                <td><input   id="a_sani_<?php echo $i ?>"              type="radio" name="a_sani"           value="<?php echo $i ?>" <?php //if ($kattam->a_sani == $i) echo " checked "  ?>> <label for="a_sani_<?php echo $i ?>"          >சனி</label></td>
                                <td><input   id="a_raaghu_<?php echo $i ?>"        type="radio" name="a_raaghu"       value="<?php echo $i ?>" <?php //if ($kattam->a_raaghu == $i) echo " checked "  ?>> <label for="a_raaghu_<?php echo $i ?>"    >ராகு</label></td>
                                <td><input   id="a_kaedhu_<?php echo $i ?>"        type="radio" name="a_kaedhu"     value="<?php echo $i ?>" <?php //if ($kattam->a_kaedhu == $i) echo " checked "  ?>> <label for="a_kaedhu_<?php echo $i ?>"    >கேது</label></td>
                                <td><input   id="a_laknam_<?php echo $i ?>"        type="radio" name="a_laknam"      value="<?php echo $i ?>" <?php //if ($kattam->a_laknam == $i) echo " checked "  ?>> <label for="a_laknam_<?php echo $i ?>"     >லக்</label></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-footer">
            <input type="hidden" name="p_id" value="<?php echo $p_id; ?>">
            <button  type="button" onclick="window.close()" class="btn btn-info pull-right">Cancel</button> 
            <button   type="submit"  id="submit" class="btn btn-info pull-right">submit</button>
        </div>    
    </form>        
</div>

<input type="hidden" class="form-control" id="inputusername3" name="m_id" value="<?php echo $m_id; ?>">

</form>
</div>
<div style="clear:both"></div>
</div>