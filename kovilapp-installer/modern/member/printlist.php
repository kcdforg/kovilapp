<?php
    include('../function.php');
        include '../init.php';

        check_login();

?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo $path ?>/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $path ?>/bootstrap/css/custom.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $path ?>/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo $path ?>/dist/css/skins/_all-skins.min.css">
    </head>
    <body class="print hold-transition skin-blue layout-top-nav">
        <?php
    

        $sql = "SELECT * from $tbl_family";
        $result = mysql_query($sql);
//$row = mysql_fetch_array($result)
//$i=0;
        while ($row = mysql_fetch_array($result)) {
            $fam ['name'] = ($row['name']);
            $fam ['mobile_no'] = ($row['mobile_no']);
            $fam ['w_name'] = ($row['w_name']);
            $fam ['father_name'] = ($row['father_name']);
            $fam ['mother_name'] = ($row['mother_name']);
//$fam ['block']=($row['block']);
            $fam ['permanent_address'] = ($row['permanent_address']);

            $family1[$row['id']] = $fam;
            //$i++;
            //echo count($fam);
        }
//var_dump($family1[10]);	
        $sql1 = "SELECT * from $tbl_child ";
//echo "$sql";
        $result1 = mysql_query($sql1);
        while ($row = mysql_fetch_array($result1)) {
            $children['c_name'] = $row['c_name'];
            $children['c_mobile_no'] = $row['c_mobile_no'];
            $children1[$row['father_id']][] = $children;
//$children[$row['father_id']][] ['c_name']=$row['c_name'];	
//$children[$row['father_id']][] ['c_mobile_no']=$row['c_mobile_no'];	
        }
//echo count($children);
//var_dump($children1[11]);
        ?>	
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="container-fluid">
                <h4 class="container text-center"><b>காக்காவேரி அண்ணமார் சுவாமி விளையன் குல கொங்கு வெள்ளாளக் கவுண்டர்கள் அறக்கட்டளை</b></h4>
            </div>
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name,Wife name& mobile </th>
                                            <th>Parent name</th>
                                            <th>Permanent address</th>
                                            <th>Child name</th>		
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 0;
                                        foreach ($family1 as $k => $v) {
                                            echo"<td>";
                                            echo ++$counter;
                                            echo"</td>";
                                            echo "<td>";
                                            echo $v['name'];
                                            echo "<br>";
                                            echo $v['w_name'];
                                            echo "<br>";
                                            echo $v['mobile_no'];
                                            echo "</td>";
                                            echo "<td>";
                                            echo $v['father_name'];
                                            echo " <br>";
                                            echo $v['mother_name'];
                                            echo "</td>";
                                            echo "<td>";
                                            echo $v['permanent_address'];
                                            echo "</td>";
                                            //echo "<td>";
                                            // echo $v['block']  ;
                                            // echo "</td>";
                                            if (isset($children1[$k])) {
                                                $c = $children1[$k];
                                                echo "<td>";
                                                foreach ($c as $k1 => $v1) {
                                                    echo $v1['c_name'];
                                                    echo "<br>";
                                                }
                                                echo "</td>";
                                            } else {
                                                echo "<td>";
                                                echo"No children";
                                            }
                                            echo "</td>";
                                            ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>               
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>

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