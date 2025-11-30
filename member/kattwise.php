<?php
include('../header.php');
global $con;

$result = mysqli_query($con, "SELECT DISTINCT kattalai, count(*) as cnt  FROM $tbl_family  WHERE kattalai <> ' ' group by kattalai  ORDER BY kattalai ASC");
$kattalai = '';
if (isset($_GET['kattalai']))
$kattalai = $_GET['kattalai'];
?>
<style>
    .table>thead:first-child>tr:first-child>th {
        border-top: 0;
        background: #e4ece7;
    }
    th{
        border:2px solid #d4d4d4;
    }
    .box{
        border-top:0px;
    }
    #bb.box-body,td {
        margin-top: 20px;
        border-radius: 8px;
        border: 2px solid #d4d4d4;
    }
    #list.col-md-2
    {
        padding-left: 8px;
        margin-top: 20px;
        border-radius:10px;
        border: 2px solid #d4d4d4;
        padding: 0px;
    }
    #listby.list-group,ul  {
        padding : 0px; 
        margin:0px; 
    }
    .table > tbody > tr > td {
        border-top: 2px solid #3d8dbc;
        word-break: break-all;
    }
    #list table{
        width:100%;
    }

    #list table td { 
        word-break:  break-all;
        word-wrap:  break-word;
    }
    #list table td .data{
        width:350px;
        word-break:  break-all;
        word-wrap:  break-word;
    }
    #listby  li:hover {
        background-color: #6aa761;
        color:yellow;
    }
    #listby .cls_active {
        background-color: #49d4c4;
    }
</style>
<div class="container-fluid">
    <h2 class="text-center">List By Katttalai</h2>
</div>
<div id="list" class="col-md-12">
    <div id="list" class="col-md-2">
        <div class="row1">
            <h3 class="box-title"><b><center> Kattalai </center></b></h3>
        </div>
        <?php
        if (!$result) {
        echo 'No records found';
        } else {
        echo '<ul id="listby"  class="list-group list-group-unbordered">';
        ?>
        <li id="listby" class="list-group-item">			
            <b><a href="kattwise.php">All</a></b>
        </li>
        <?php
        $active = '';
        while ($row = mysqli_fetch_array($result)) {
        if($row['kattalai'] == $kattalai )
        $active = ' cls_active ';
        else
        $active = '';
        ?>
        <li  id="listby"class="list-group-item <?php echo $active ?>">	
        
            <b><a href="kattwise.php?kattalai=<?php echo $row['kattalai'] ?>"><?php echo get_kattalai($row['kattalai']) ?> (<?php echo $row['cnt'] ?>)</a></b>
        </li>

        <?php
        }
        echo '</ul>';
        }
        ?>     

    </div>
    <?php
    $counter = 0;
    $kattalai = '';
    $rec_per_page = isset($_GET['per_page']) && is_numeric($_GET['per_page']) ? (int)$_GET['per_page'] : 30;
    $curr_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($curr_page < 1) $curr_page = 1;
    $offset = ($curr_page - 1) * $rec_per_page;
    if (isset($_GET['kattalai'])) {
        $kattalai = $_GET['kattalai'];
        $count_res = mysqli_query($con, "SELECT count(*) as cnt FROM $tbl_family WHERE kattalai='$kattalai'");
        $count = mysqli_fetch_array($count_res);
        $total_records = $count['cnt'];
        $total_pages = ceil($total_records / $rec_per_page);
        $result = mysqli_query($con, "SELECT * FROM $tbl_family WHERE kattalai='$kattalai' LIMIT $offset, $rec_per_page");
    }
    ?>
    <div  id="list" class="col-md-10">
        <div class="row">

            <div class="box table-responsive">
                <div class="box-header">
                    <h3 class="box-title"><b> Search Results for <?php echo get_kattalai($kattalai); ?> (<?php echo $count['cnt']; ?>)</b></h3>

                </div>
                
                <!-- Pagination and per-page dropdown controls below the table -->
                <div class="dataTables_paginate paging_simple_numbers pagination-flex-align" style="margin:10px 0 10px 0; justify-content: flex-end; display: flex; align-items: center; gap: 16px;">
                    <ul class="pagination" style="margin-bottom: 0; margin-right: 0; vertical-align: middle;">
                        <?php $disable_all = ($total_pages <= 1); ?>
                        <li class="paginate_button previous<?php if ($curr_page <= 1 || $disable_all) echo ' disabled'; ?>">
                            <a href="<?php echo ($curr_page > 1 && !$disable_all) ? 'kattwise.php?kattalai=' . urlencode($kattalai) . '&page=' . ($curr_page-1) . '&per_page=' . $rec_per_page : '#'; ?>">Prev</a>
                        </li>
                        <?php for ($i = 1; $i <= max(1, $total_pages); $i++) { ?>
                            <li class="paginate_button <?php if ($i == $curr_page) echo 'active'; if ($disable_all) echo ' disabled'; ?>">
                                <a href="<?php echo (!$disable_all && $i != $curr_page) ? 'kattwise.php?kattalai=' . urlencode($kattalai) . '&page=' . $i . '&per_page=' . $rec_per_page : '#'; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php } ?>
                        <li class="paginate_button next<?php if ($curr_page >= $total_pages || $disable_all) echo ' disabled'; ?>">
                            <a href="<?php echo ($curr_page < $total_pages && !$disable_all) ? 'kattwise.php?kattalai=' . urlencode($kattalai) . '&page=' . ($curr_page+1) . '&per_page=' . $rec_per_page : '#'; ?>">Next</a>
                        </li>
                    </ul>
                    <form method="get" class="form-inline" style="margin-bottom:0; margin-left:0; vertical-align: middle;">
                        <input type="hidden" name="kattalai" value="<?php echo htmlspecialchars($kattalai); ?>">
                        <select name="per_page" id="per_page" class="form-control input-sm" onchange="this.form.submit()" style="width:auto; height:34px; padding:6px 16px; margin-top:20px; margin-right:16px; font-size:14px; line-height:1.42857143; vertical-align:middle; display:inline-block;">
                            <?php $options = [10, 20, 50, 100, 200]; foreach ($options as $opt) { ?>
                                <option value="<?php echo $opt; ?>" <?php if ($rec_per_page == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="page" value="1">
                    </form>
                </div>

                <?php
                //} else {
                   // $result = mysqli_query($con, "SELECT * FROM $tbl_family order by kattalai");
                    $total_records = mysqli_num_rows($result);
                    $total_pages = ceil($total_records / $rec_per_page);
                    ?>

                    <div id="list" class="col-md-12">
                        <div class="row">
                            <div class="box">
                                <div  id="bb" class="box-body no-padding">
                                    <table class="table table-hover">
                                        <thead><tr>
                                                <th width="4%">S.no</th>
                                                <th width="15%">Personal Details</th>
                                                <th width="20%" >Address</th>
                                                <th width="15%">Other details</th>
                                            </tr></thead>
                                        <tbody><?php
                                            while ($row = mysqli_fetch_array($result)) {
                                                ?>
                                                <tr>    
                                                    <td><?php echo ++$counter; ?></td>
                                                    <td><a id="a" href="viewmember.php?id=<?php echo $row['id'] ?> "> <?php echo $row['name'] ?> </a><br>    S/O <?php echo $row['father_name'] . "<br>" . $row['mother_name'] . "<br>" . $row['mobile_no'] ?></td>
                                                    <td><?php echo $row['permanent_address'] ?></td>
                                                    <td><?php echo get_qualification($row['qualification']) . "<br>" . get_blood_group($row['blood_group']) . "<br>" . $row['pudavai'] . "<br>" . $row['village'] ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody></table>
                                    <!-- Pagination and per-page dropdown controls below the table -->
                                    <div class="dataTables_paginate paging_simple_numbers pagination-flex-align" style="margin:10px 0 10px 0; justify-content: flex-end; display: flex; align-items: center; gap: 16px;">
                                        <ul class="pagination" style="margin-bottom: 0; margin-right: 0; vertical-align: middle;">
                                            <?php $disable_all = ($total_pages <= 1); ?>
                                            <li class="paginate_button previous<?php if ($curr_page <= 1 || $disable_all) echo ' disabled'; ?>">
                                                <a href="<?php echo ($curr_page > 1 && !$disable_all) ? 'kattwise.php?kattalai=' . urlencode($kattalai) . '&page=' . ($curr_page-1) . '&per_page=' . $rec_per_page : '#'; ?>">Prev</a>
                                            </li>
                                            <?php for ($i = 1; $i <= max(1, $total_pages); $i++) { ?>
                                                <li class="paginate_button <?php if ($i == $curr_page) echo 'active'; if ($disable_all) echo ' disabled'; ?>">
                                                    <a href="<?php echo (!$disable_all && $i != $curr_page) ? 'kattwise.php?kattalai=' . urlencode($kattalai) . '&page=' . $i . '&per_page=' . $rec_per_page : '#'; ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php } ?>
                                            <li class="paginate_button next<?php if ($curr_page >= $total_pages || $disable_all) echo ' disabled'; ?>">
                                                <a href="<?php echo ($curr_page < $total_pages && !$disable_all) ? 'kattwise.php?kattalai=' . urlencode($kattalai) . '&page=' . ($curr_page+1) . '&per_page=' . $rec_per_page : '#'; ?>">Next</a>
                                            </li>
                                        </ul>
                                        <form method="get" class="form-inline" style="margin-bottom:0; margin-left:0; vertical-align: middle;">
                                            <input type="hidden" name="kattalai" value="<?php echo htmlspecialchars($kattalai); ?>">
                                            <select name="per_page" id="per_page" class="form-control input-sm" onchange="this.form.submit()" style="width:auto; height:34px; padding:6px 16px; margin-top:20px; margin-right:16px; font-size:14px; line-height:1.42857143; vertical-align:middle; display:inline-block;">
                                                <?php $options = [10, 20, 50, 100, 200]; foreach ($options as $opt) { ?>
                                                    <option value="<?php echo $opt; ?>" <?php if ($rec_per_page == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" name="page" value="1">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                    </div>                  
                </div>                  
            </div>                  
        </div>                                   
    </div>
</div>

<?php
include('../footer.php');
?>
