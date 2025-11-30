<?php
include('../popupheader.php');
$username = $_SESSION['username'];
$id = $_GET['id'];
$row = get_horoscope($id);
$row1 = get_attachments($id);

global $kattam;
$kattam = array();
$kattam = get_kattam($id);

$rasi = $kattam['rasi'];
$amsam = $kattam['amsam'];
?>
<style>
.col-sm-5{
    font-size:14px;
}
/*@page {
  size: A4;
  margin: 0;
}
@media print {

 body {
    width: 768px;
  }
  body {
    margin: 0 auto;
  }
}
body {
  background: rgb(204,204,204); 
}
box[size="A4"] {
  background: white;
  width: 21cm;
  height: 29.7cm;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
@media print {
  body, box[size="A4"] {
    margin: 0;
    box-shadow: 0;
  }
}
@page {
  size: A4;
}*/
</style>
 
<div class="container-fluid" style="background: #fbfbfb; color: #151818;width: 1000px;">
    <h4 class="container text-center" style="text-shadow:0 0 0.2em #93ADAD;"><b>காக்காவேரி அண்ணமார் சுவாமி விளையன்குல அறக்கட்டளை </b></h4>
</div>
<div class="box box-primary" style="border-top-color: #333c42;border: 3px solid black;margin: 10px;margin-left: 20px;width:950px;padding: 10px;">
    <div  class="box-body box-profile">
            <div class="col-md-6">  	
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5" ><b>பதிவு எண்</b> </div> <div class="col-sm-7">  <label >: &nbsp;</label><?php echo $row['id'] ?></div></div>
                </li>	
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>பெயர்</b> </div> <div class="col-sm-7">  <label >: &nbsp;</label><?php echo $row['name'] ?></div></div>
                </li>	
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>தந்தை பெயர்</b> </div> <div class="col-sm-7">  <label >: &nbsp;</label><?php echo $row['father_name'] ?></div></div>
                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>தாயார் பெயர்</b> </div> <div class="col-sm-7">  <label >: &nbsp;</label><?php echo $row['mother_name'] ?></div></div>
                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>குலம்</b> </div> <div class="col-sm-7">  <label >: &nbsp;</label><?php echo get_kulam($row['kulam']) ?></div></div>
                </li>			
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>கோவில்</b> </div> <div class="col-sm-7">  <label >: &nbsp;</label><?php echo $row['temple'] ?></div></div>                 
                </li>     
                <li id= "border" class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>உயரம்</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['height'] ?></div></div>
                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>எடை</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['weight'] ?></div></div>
                </li>                 
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>படிப்பு </b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['education_details'] ?></div></div>
                </li>   
            </div>
            <div class="col-md-6"> 
                 <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>பிறந்த தேதி</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['birth_date'] ?></div></div>

                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>பிறந்த நேரம்</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['birth_time'] ?></div></div>

                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>பிறந்த இடம்</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['birth_place'] ?></div></div>
                </li>                           
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>ராசி</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo get_raasi($row['raasi']) ?></div></div>

                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>லக்னம்</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo get_lagnam($row['laknam']) ?></div></div>

                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>நட்சத்திரம்</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo get_star($row['star']) . ' ' . '-' . $row['padham'] ?></div></div>
                </li>				
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>ராகு/கேது</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo ($row['raaghu_kaedhu'] > 0) ? "Yes" : "No" ?></div></div>
                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>செவ்வாய்</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo ($row['sevvai'] > 0) ? "Yes" : "No" ?></div></div>
                </li>        
                  <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>வேலை </b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['occupation_details'] ?></div></div>
                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>வருமானம்</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['income'] ?></div></div>
                </li>                  
            </div>
   
        <br>
        <div id="roww" class="row">
            <style>
                .graham_sh{
                    padding:3px;
                }
            </style>
            <div class="col-md-12">
                <br>
                <div id="katt" class="col-md-5">
                    <div class="table-responsive tab-bor">
                        <table class="kattam">
                            <tbody>
                                <tr>
                                    <td>
                                        <?php display_graham(12, $rasi) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(1, $rasi) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(2, $rasi) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(3, $rasi) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php display_graham(11, $rasi) ?>

                                    </td>
                                    <td colspan="2" rowspan="2" style="text-align: center">
                                        <h3> இராசி</h3>
                                    </td>
                                    <td>
                                        <?php display_graham(4, $rasi) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php display_graham(10, $rasi) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(5, $rasi) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php display_graham(9, $rasi) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(8, $rasi) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(7, $rasi) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(6, $rasi) ?>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="katt"class="col-md-5 ">
                    <div class="table-responsive tab-bor">
                        <table class="kattam">
                            <tbody>
                                <tr>
                                    <td>
                                        <?php display_graham(12, $amsam) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(1, $amsam) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(2, $amsam) ?>

                                    </td>
                                    <td>
                                        <?php display_graham(3, $amsam) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php display_graham(11, $amsam) ?>
                                    </td>
                                    <td colspan="2" rowspan="2" style="text-align: center">
                                        <h3> நவாம்சம்</h3>
                                    </td>
                                    <td>
                                        <?php display_graham(4, $amsam) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php display_graham(10, $amsam) ?>
                                    </td>
                                    <td>
                                        <?php display_graham(5, $amsam) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php display_graham(9, $amsam) ?>
                                    </td>
                                    <td>
                                        <?php display_graham(8, $amsam) ?>
                                    </td>
                                    <td>
                                        <?php display_graham(7, $amsam) ?>
                                    </td>
                                    <td>
                                        <?php display_graham(6, $amsam) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>  </div>
        <br>	
        <div class="col-md-6 ">  
                       <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>உடன்பிறப்பு</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['sibling'] ?></div></div>
                </li>
                <li id= "border"class="list-group-item">
                    <div class="row"> <div class="col-sm-5"><b>சொத்து விவரம்</b> </div> <div class="col-sm-7"> <label >: &nbsp;</label><?php echo $row['asset_details'] ?></div></div>
                </li>         
                <br>
        </div>
    </div>
</div>
<div style="clear:both"></div>  

 <script src="<?php echo $path ?>/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?php echo $path ?>/bootstrap/js/bootstrap.min.js"></script>
        <!-- SlimScroll -->
        <script src="<?php echo $path ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="<?php echo $path ?>/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo $path ?>/dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo $path ?>/dist/js/demo.js"></script>
    </body>
</html>