<?php
include('../header.php');
//var_dump($_POST);

$rec_per_page = 25;
$curr_page = 1;
$offset = ($curr_page - 1) * ($rec_per_page);

$where = '';
if (isset($_POST['filter'])) {
    if ($_POST['reg_no'] != '') {
        $where .= " AND reg_no = " . $_POST['reg_no'];
    }
    if ($_POST['keyword'] != '') {
        $where .= " AND (name = '" . $_POST['keyword'] . "' OR mobile_no = '" . $_POST['keyword'] . "')";
    }
}

$sql_total = "SELECT count(*) as total FROM $tbl_matrimony where `deleted`=0 $where";
$result = mysqli_query($con, $sql_total);
$row = mysqli_fetch_array($result);
$total_records = $row['total'];
//echo $total_records . "<br>";
$total_pages = ceil($total_records / $rec_per_page);
//echo $total_pages;
?> 	

<style>
    .table>thead:first-child>tr:first-child>th {
        border-top: 0;
        background: #e4ece7;
    }
    .box-body {
        margin-top: 20px;  
        border-radius:8px;
        margin-left: 10px;
        border: 2px solid #d4d4d4;
    }
    tbody#tbody > tr > td {
        border-top: 2px solid #d4d4d4;
    }
    #pagi{
        padding-left: 300px;  
    }
    #page{
        padding-right:0px;
    }
    #box.box{
        border:0px;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <h2 class="container text-center">Horoscope List<a href="addhoroscope.php"  class="btn btn-info pull-right"> <span class="glyphicon glyphicon-plus"></span></a>&nbsp;<a href="searchhoroscope.php"  class="btn btn-info pull-right"><span class="glyphicon glyphicon-search"></span></a> </h2>
    </div>
</section>
<div class="col-md-12">
    <div  class="col-sm-6">
        <br>
        <form method='POST'>
            <div  class="col-sm-4"><input type="text" name="reg_no" class="form-control" placeholder="Reg no" > </div>
            <div  class="col-sm-5"><input type="text" name = 'keyword' class="form-control" placeholder="Name/Mobile No" ></div>

            <input type="hidden" name="filter" >
            <div  class="col-sm-3">  <button type="submit" name="submit" class="btn btn-info">Search</button></div>
        </form>
    </div>

    <div class="col-sm-6 dataTables_paginate paging_simple_numbers" id="example2_paginate">
        <ul id="pagi" class="pagination">
            <?php
            if ($total_records > $rec_per_page) {
                ?>
                <li class="paginate_button previous" id="example2_previous">
                    <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0">Prev</a>
                </li>
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    ?>
                    <li class="paginate_button ">
                        <a href="listhoroscope.php?page=<?php echo $i ?>" aria-controls="example2" data-dt-idx="1" tabindex="0"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
                <li class="paginate_button next" id="example2_next">
                    <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a>
                </li>
            <?php } ?>
        </ul></div>
</div>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box table-responsive">
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr> 
                                <th width="3%">S.No</th>
                                <th width="7%">Photo</th>           
                                <th width="13%">Name & Reg.No</th>
                                <th width="13%">Personal details</th>
                                <th width="11%">Horo details</th>
                                <th class="col-sm-1">status</th>
                                <th class="col-sm-2">Address</th>				 
                                <th class="col-sm-2">Other Details</th>				 

                            </tr>		
                        </thead>
                        <tbody id="tbody">
                            <?php
                            $result = get_horo_list($where);
                            $counter = 0;
                            if ($result) {
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr> 
                                        <td><?php echo ++$counter; ?></td>
                                        <td><img src="../images/horo/<?php echo $row['photo'] ?>" width="100" height="100" class="img-responsive"/></td>   
                                        <td><a id="a" href="viewhoroscope.php?id=<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a><?php echo "<br>" . ($row['reg_no']); ?></td>
                                        <td><?php echo $row['qualification'] . "<br>" . $row['occupation'] ?></td>
                                        <td><?php
                                            //var_dump($row); 
                                            //echo 'test';
                                            echo get_raasi($row['raasi']);
                                            echo "<br>";
                                            echo get_star($row['star']);
                                            echo "<br>";
                                            echo ($row['raaghu_kaedhu'] > 0) ? "Yes" : "No";
                                            echo "<br>";
                                            echo ($row['sevvai'] > 0) ? "Yes" : "No";
                                            ?></td>
                                        <td><?php echo $row['status'] ?></td>
                                        <td><?php echo $row['mobile_no'] . "<br>" . $row['email'] . "<br>" . $row['address'] ?></td>
                                        <td><?php echo get_kulam($row['kulam']) . "<br>" . $row['temple'] . "<br>" . $row['height'] ?> Cms<br><?php echo $row['weight'] ?> Kgs</td>
                                    </tr>

                                    <?php
                                }
                            }
                            mysqli_close($con);
                            ?>

                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->


                <div class="col-sm-12 dataTables_paginate paging_simple_numbers pull-right" id="example2_paginate">
                    <ul id="page" class="pagination pull-right">
                        <?php
                        if ($total_records > $rec_per_page) {
                            ?>
                            <li class="paginate_button previous" id="example2_previous">
                                <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0">Prev</a>
                            </li>
                            <?php
                            for ($i = 1; $i <= $total_pages; $i++) {
                                ?>
                                <li class="paginate_button ">
                                    <a href="listhoroscope.php?page=<?php echo $i ?>" aria-controls="example2" data-dt-idx="1" tabindex="0"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <li class="paginate_button next" id="example2_next">
                                <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a>
                            </li>
                        <?php } ?>
                    </ul></div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
<?php
include('../footer.php');
?>