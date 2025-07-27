<?php
include('../popupheader.php');
$c_id = $_GET['child_id'];

$family = get_families();
$children = get_children();

//echo "<b>Families=$num_rows</b>";
?>
<div class="container-fluid">
    <h2 class="container text-center">Member List </h2>
</div>
<!-- Main content -->
<div class="col-md-12">	
    <div class="row">	  
        <div class="box table-responsive">
            <div class="box-body">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th width="10%">Name</th>
                                <th width="15%">Parent Name</th>
                                <th width="20%">Address</th>
                                <th width="10%">Link</th>

                            </tr>
                        </thead>
                        <tbody id="tbody">

                            <?php
                            //   $counter = 0;
                            foreach ($family as $k => $v) {
                                echo '<tr>';
                                ?>	      
                        <td> <?php echo $v['id'] ?></td>
                            <td>
                               <?php echo $v['name'] . " (" . $v['h_age'] . ")" ?> <?php echo "<br>" . $v['w_name'] ?> 	
                            </td>
                            <?php
                            echo "<td>" . $v['father_name'] . " <br>" . $v['mother_name'] . "</td>";
                            echo "<td>" . str_replace("\n", "<br />", $v['permanent_address']) . "</td>";
                            ?>
                            <td><a  id="a"  href="selectfamily.php?child_id=<?php echo $c_id ?>&id=<?php echo $v['id'] ?>"> <button>Select</button></a></td>

                            <?php
                            echo "</tr>";
                        }
                        ?>               
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>	
    <!-- /.col -->
</div>
<!-- /.row -->
<div style="clear:both"></div>  	  
<?php
include('../footer.php');
?>