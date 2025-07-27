<?php
include('../header.php');

$height_from = '';
$height_to = '';
$age_from='';
$age_to='';
$star='';

$where = " AND status = 'open'  ";
if (count($_POST) > 1) {
    if (isset($_POST['marital_status'])) {
        $where .= '  AND (';
        foreach ($_POST['marital_status'] as $k => $v) {
            $where .= " marital_status = '$k' OR ";
        }
        $where .= " marital_status = '$k'   ) ";
    }
    
    if (isset($_POST['age_from']) && $_POST['age_from'] != '' ) {
        $where .= " AND  age  >=  " . $_POST['age_from'] ;
        $age_from=$_POST['age_from'];
    }
    
    if (isset($_POST['age_to']) && $_POST['age_to'] != '' ) {
        $where .=  " AND age  <= " . $_POST['age_to'];
             $age_to=$_POST['age_to'];
    }
    
    if (isset($_POST['height_from']) && $_POST['height_from'] != '') {
        $where .= " AND height > " . $_POST['height_from'];
        $height_from = $_POST['height_from'];
    }

    if (isset($_POST['height_to']) && $_POST['height_to'] != '') {
        $where .= " AND height  <= " . $_POST['height_to'];
            $height_to = $_POST['height_to'];
                }

    if (isset($_POST['kulam']) && $_POST['kulam'] != '') {
        $where .= ' AND kulam = " ' . $_POST['kulam'] . '"';
    }

    if (isset($_POST['star'])) {
        $where .= '  AND star in ( ';
        foreach ($_POST['star'] as $k => $v) {
            //$where .= "  raasi = $k OR  ";
            $where .= "$k,";
            $star[$k]= $k;
        }
        //$where .= " raasi = $k  ) ";   
        $where .= " $k ) ";   
  
      /*  $star = $_POST['star'];
        $star_list = implode(",",$star);
        $where .= '  AND star in ('. $star_list . ")";
        */
    
    }
	
    if (isset($_POST['raghu_kedhu']) ) {
        $where .= ' AND raaghu_kaedhu > 0  ';
    }
    if (isset($_POST['sevvai']) ) {
        $where .= ' AND sevvai > 0 ';
    }
}
/* if (isset($_POST['colour'])) {
  $where .= 'colour = "$colour"';
  } */
?>
<div class="container-fluid">
    <h2 class="container text-center">Search Horoscope</h2>
</div>
<div class="col-sm-12 ">
    <div class="col-sm-3 " style="padding-left:0px;">
        <div id="filter" class="box box-primary">
            <div id="clr" class="box-header with-border">
                <h3 class="box-title"><b>Filter</b></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form action="searchhoroscope.php" method="POST">			 
                    <ul class="list-group list-group-unbordered">
                        <li id="item" class="list-group item">      
                            <button type="submit" id="sub" class="btn btn-info pull-right">Submit</button>	
                            <br>
                        </li>
                        <li id="item" class="list-group item">     

                            <table>
                                <tr>
                                    <td style="width:100px"><label class="control-label span3">Height:</label></td>
                                    <td ><?php display_height_horo("height_from",$height_from)   ?>
                                       <!-- <input type="text" class="form-control" id="inputusername3" name="height_from" placeholder="from">       -->
                                    </td>
                                    <td style="width:30px;text-align: center;">  to  </td>
                                    <td >
                                        <?php display_height_horo("height_to",$height_to) ?>
                                        <!-- <input type="text" class="form-control" id="inputusername3" name="height_to" placeholder="to">       -->
                                    </td>
                                    <td> cms </td>
                                </tr>
                            </table>	
                        </li>
                        <!-- /.item -->
                        <li id="item" class="list-group item">

                            <table>
                                <tr>

                                    <td style="width:100px">
                                        <label class="control-label span3">Age: </label>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="inputusername3" name="age_from" value="<?php echo $age_from?>" placeholder="from">
                                    </td>
                                    <td style="width:30px;text-align: center">  to  </td>
                                    <td>
                                        <input type="text" class="form-control" id="inputusername3" name="age_to"  value="<?php echo $age_to?>"  placeholder="to">
                                    </td>
                                    <td>&nbsp;Yrs</td></tr> </table>  
                        </li>


                        <li id="item" class="list-group item"> 

                            <label class="control-label span3">Star: </label>
                            <div style="height:300px;overflow: scroll">
                                <?php display_star_checkbox('star', $star, 'width:170px;') ?>
                            </div>    

                        </li>
                        <style>
                            input[type=checkbox] {

                                -ms-transform: scale(1.2); /* IE */
                                -moz-transform: scale(1.2); /* FF */
                                -webkit-transform: scale(1.2); /* Safari and Chrome */
                                -o-transform: scale(1.2); /* Opera */

                            }
                        </style>
                        <li id="item" class="list-group item"> 
                            <table>
                                <tr>
                                    <td style="width:100px"> <label class="control-label span3">Ragu/Kedhu: </label>	</td>
                                    <td>
                                        <?php 
                                        if(isset($_POST['raghu_kedhu'])) $checked = " checked ";
                                        else $checked = '';
                                    ?>
                                        <input type="checkbox" name="raghu_kedhu" style="margin-left:20px;" <?php echo $checked ?> >
                                    </td>
                                </tr>
                            </table>     
                        </li>
                        <li id="item" class="list-group item"> 
                            <table>
                                <tr>
                                    <td style="width:100px"> <label class="control-label span3">Sevvai: </label></td>
                                    <td>			
                                        <?php 
                                        if(isset($_POST['sevvai'])) $checked = " checked ";
                                        else $checked = '';
                                    ?>
                                        <input type="checkbox" name="sevvai" style="margin-left:20px;" <?php echo $checked ?> >
                                    </td>
                                </tr>
                            </table> 
                        </li>
                        <!-- /.item -->

                        <li id="item" class="list-group item"> 
                            <label class="control-label span3">
                                Marital status: </label>	
                            <?php 
                            if(!isset($_POST['marital_status']))
                                    $_POST['marital_status']['unmarried'] = 'on';
                            ?>
                            <?php display_marital_checkbox('marital_status',$_POST['marital_status'] ) ?>

                        </li>

                        <li id="item" class="list-group item"> 
                            <table>
                                <tr><td style="width:100px"><label class="control-label span3">Kulam: </label></td>
                                    <td><?php display_kulam_list($name = "kulam") ?></td>
                                </tr>
                            </table>
                        </li>

                        <!-- /.item -->
                    </ul>
                    <button type="submit" id="sub" class="btn btn-info pull-right">submit</button>	
                </form>
            </div>
        </div>
    </div>

<?php 
                $result = get_horo_list($where);
$num_rows = mysqli_num_rows($result);
?>
    <div class="col-sm-9" style="padding-left:0px;">
        <div id="searchresult" class="box box-primary">
            <div id="clr" class="box-header with-border">
                <h3 class="box-title"><b>Search Results ( <?php echo $num_rows ?>)</b></h3>
            </div>
            <div class="box-body">
                <?php
                while ($row = mysqli_fetch_array($result)) {
                    ?>		  
                    <div id="border" class="col-sm-12 box box-primary">	
                        <div id="clr" class="box-header ">
                            <b><?php echo $row['name'] ?></b>
                        </div>
                        <div class="box-body hororesult">
                            <div id="img" class="col-sm-3">

                                <a><img src="../attachments/<?php echo $row['photo'] ?>" class="img-thumbnail img-responsive" style="width: 140px; height: 150px;" alt="img"> </a>
                            </div>
                            <div  class="col-sm-9">

                                <div class="col-md-6">
                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> Age</b> </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo $row['age'] ?></span>
                                        </div>
                                    </div>
                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> Marital Status</b> <span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo get_marital_status($row['marital_status']) ?></span>
                                        </div>
                                    </div>


                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b>  Education </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo $row['qualification'] ?></span>
                                        </div>
                                    </div>
                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> Occupation </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo $row['occupation'] ?></span>
                                        </div>
                                    </div>
                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> Ragu / Kedhu </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo $row['raaghu_kaedhu'] ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> Raasi </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo get_raasi($row['raasi']) ?></span>
                                        </div>
                                    </div>

                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> laknam </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo get_lagnam($row['laknam']) ?></span>
                                        </div>
                                    </div>
                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> Star </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo get_star($row['star']) ?></span>
                                        </div>
                                    </div>
                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> Padham</b> <span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo $row['padham'] ?></span>
                                        </div>
                                    </div>

                                    <div   class="row">
                                        <div class="col-sm-5 left-text">
                                            <b> Sevvai </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-7 left-text ">
                                            <span id="cont"><?php echo $row['sevvai'] ?></span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div   class="row">
                                        <div class="col-sm-3 left-text">
                                            <b> Kulam </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-9 left-text ">
                                            <span id="cont"><?php echo get_kulam($row['kulam']) ?></span>
                                        </div>
                                    </div>

                                    <div   class="row">
                                        <div class="col-sm-3 left-text">
                                            <b> Kovil </b><span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-9 left-text ">
                                            <span id="cont"><?php echo $row['temple'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="viewhoroscope.php?id=<?php echo $row['id'] ?>" <button type="submit" id="full" class="btn btn-info pull-right">View full profile</button></a>			  
                        </div>
                    </div>
                    <?php
                }
                ?> 
            </div>
        </div>
    </div>
</div>
<div style="clear:both"></div>
<?php
include('../footer.php');
?>			  
