<?php
include('../header.php');

$result = mysql_query("SELECT DISTINCT w_temple FROM $tbl_family  WHERE w_temple!=' 'ORDER BY w_temple ASC");
?>
<div class="container-fluid">
    <h2 class="text-center">List By Temple</h2>
</div>
<div id="list" class="col-md-12">
    <div id="list" class="col-md-3">
        <div class="row1">
            <h3 class="box-title"><b><center> Temple </center></b></h3>

        </div>
<?php
if (!$result) {
    echo 'No records found';
} else {
    echo '<ul class="list-group list-group-unbordered">';
    while ($row = mysql_fetch_array($result)) {
        ?>
                <li  id="listby" class="list-group-item">			
                    <b><a href="twise.php?w_temple=<?php echo $row['w_temple'] ?>"><?php echo $row['w_temple'] ?></b></a>
                </li>

                <?php
            }
            echo '</ul>';
        }
        ?>     

    </div>
        <?php
        $counter = 0;
        $w_temple = '';
        $rec_per_page = isset($_GET['per_page']) && is_numeric($_GET['per_page']) ? (int)$_GET['per_page'] : 30;
        $curr_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($curr_page < 1) $curr_page = 1;
        $offset = ($curr_page - 1) * $rec_per_page;
        if (isset($_GET['w_temple'])) {
            $w_temple = $_GET['w_temple'];
            $count_res = mysqli_query($con, "SELECT count(*) as cnt FROM $tbl_family WHERE w_temple='$w_temple'");
            $count = mysqli_fetch_array($count_res);
            $total_records = $count['cnt'];
            $total_pages = ceil($total_records / $rec_per_page);
            $result = mysqli_query($con, "SELECT * FROM $tbl_family WHERE w_temple='$w_temple' LIMIT $offset, $rec_per_page");
        }
        if (isset($_GET['w_temple'])) {
            $w_temple = $_GET['w_temple'];
            $rec_per_page = isset($_GET['per_page']) && is_numeric($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
            $curr_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($curr_page < 1) $curr_page = 1;
            $offset = ($curr_page - 1) * $rec_per_page;
           
            $count_res = mysqli_query($con, "SELECT count(*) as cnt FROM $tbl_family WHERE w_temple='$w_temple'");
            $count = mysqli_fetch_array($count_res);
            $total_records = $count['cnt'];
            $total_pages = ceil($total_records / $rec_per_page);
            $result = mysqli_query($con, "SELECT * FROM $tbl_family WHERE w_temple='$w_temple' LIMIT $offset, $rec_per_page");
            ?>
        <div id="list" class="col-md-9">
            <div class="row">       
                <div class="box table-responsive ">
                    <div class="box-header">
                        <h3 class="box-title"><b>  <?php echo $w_temple; ?></b></h3>

                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="dataTables_paginate paging_simple_numbers pagination-flex-align" style="margin:10px 0 10px 0; justify-content: flex-end; display: flex; align-items: center; gap: 16px;">
                            <ul class="pagination" style="margin-bottom: 0; margin-right: 0; vertical-align: middle;">
                                <?php $disable_all = ($total_pages <= 1); ?>
                                <li class="paginate_button previous<?php if ($curr_page <= 1 || $disable_all) echo ' disabled'; ?>">
                                    <a href="<?php echo ($curr_page > 1 && !$disable_all) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . ($curr_page-1) . '&per_page=' . $rec_per_page : '#'; ?>">Prev</a>
                                </li>
                                <?php for ($i = 1; $i <= max(1, $total_pages); $i++) { ?>
                                    <li class="paginate_button <?php if ($i == $curr_page) echo 'active'; if ($disable_all) echo ' disabled'; ?>">
                                        <a href="<?php echo (!$disable_all && $i != $curr_page) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . $i . '&per_page=' . $rec_per_page : '#'; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php } ?>
                                <li class="paginate_button next<?php if ($curr_page >= $total_pages || $disable_all) echo ' disabled'; ?>">
                                    <a href="<?php echo ($curr_page < $total_pages && !$disable_all) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . ($curr_page+1) . '&per_page=' . $rec_per_page : '#'; ?>">Next</a>
                                </li>
                            </ul>
                            <form method="get" class="form-inline" style="margin-bottom:0; margin-left:0; vertical-align: middle;">
                                <input type="hidden" name="w_temple" value="<?php echo htmlspecialchars($w_temple); ?>">
                                <select name="per_page" id="per_page" class="form-control input-sm" onchange="this.form.submit()" style="width:auto; height:34px; padding:6px 16px; margin-top:20px; margin-right:16px; font-size:14px; line-height:1.42857143; vertical-align:middle; display:inline-block;">
                                    <?php $options = [10, 20, 50, 100, 200]; foreach ($options as $opt) { ?>
                                        <option value="<?php echo $opt; ?>" <?php if ($rec_per_page == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="page" value="1">
                            </form>
                        </div>
                        <table class="table table-hover">
                            <thead><tr>
                                    <th width="4%">S.no</th>
                                    <th width="15%">Personal Details</th>
                                    <th width="20%">Address</th>
                                    <th width="15%">Other details</th>
                                </tr></thead>
                            <tbody>				<?php
    while ($row = mysqli_fetch_array($result)) {
        ?>
                                    <tr>								
                                        <td><?php echo ++$counter; ?></td>
                                        <td><a id="a" href="viewmember.php?id=<?php echo $row['id'] ?> "> <?php echo $row['name'] ?> </a><br>	S/O <?php echo $row['father_name'] . "<br>" . $row['mother_name'] . "<br>" . $row['mobile_no'] ?></td>
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
                                    <a href="<?php echo ($curr_page > 1 && !$disable_all) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . ($curr_page-1) . '&per_page=' . $rec_per_page : '#'; ?>">Prev</a>
                                </li>
                                <?php for ($i = 1; $i <= max(1, $total_pages); $i++) { ?>
                                    <li class="paginate_button <?php if ($i == $curr_page) echo 'active'; if ($disable_all) echo ' disabled'; ?>">
                                        <a href="<?php echo (!$disable_all && $i != $curr_page) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . $i . '&per_page=' . $rec_per_page : '#'; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php } ?>
                                <li class="paginate_button next<?php if ($curr_page >= $total_pages || $disable_all) echo ' disabled'; ?>">
                                    <a href="<?php echo ($curr_page < $total_pages && !$disable_all) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . ($curr_page+1) . '&per_page=' . $rec_per_page : '#'; ?>">Next</a>
                                </li>
                            </ul>
                            <form method="get" class="form-inline" style="margin-bottom:0; margin-left:0; vertical-align: middle;">
                                <input type="hidden" name="w_temple" value="<?php echo htmlspecialchars($w_temple); ?>">
                                <select name="per_page" id="per_page" class="form-control input-sm" onchange="this.form.submit()" style="width:auto; height:34px; padding:6px 16px; margin-top:20px; margin-right:16px; font-size:14px; line-height:1.42857143; vertical-align:middle; display:inline-block;">
                                    <?php $options = [10, 20, 50, 100, 200]; foreach ($options as $opt) { ?>
                                        <option value="<?php echo $opt; ?>" <?php if ($rec_per_page == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="page" value="1">
                            </form>
                        </div>
                    </div>

    <?php
} else {
    $result = mysqli_query($con, "SELECT * FROM $tbl_family order by w_temple");
    $total_records = mysqli_num_rows($result);
    $total_pages = ceil($total_records / $rec_per_page);
    ?>

                    <div id="list" class="col-md-9">
                        <div class="row">

                            <div class="box">

                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead><tr>
                                                <th width="4%">S.no</th>
                                                <th width="15%">Personal Details</th>
                                                <th width="20%">Address</th>
                                                <th width="15%">Other details</th>
                                            </tr></thead>
                                        <tbody><?php
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                                                <tr>								
                                                    <td><?php echo ++$counter; ?></td>
                                                    <td><a id="a" href="viewmember.php?id=<?php echo $row['id'] ?> "> <?php echo $row['name'] ?> </a><br>	S/O <?php echo $row['father_name'] . "<br>" . $row['mother_name'] . "<br>" . $row['mobile_no'] ?></td>
                                                    <td><?php echo $row['permanent_address'] ?></td>
                                                    <td><?php echo get_qualification($row['qualification']) . "<br>" . get_blood_group($row['blood_group']) . "<br>" . $row['pudavai'] . "<br>" . $row['village'] ?></td>
                                                </tr>
        <?php
    }
}
?>
                                    </tbody></table>
                                <!-- Pagination and per-page dropdown controls below the table -->
                                <div class="dataTables_paginate paging_simple_numbers pagination-flex-align" style="margin:10px 0 10px 0; justify-content: flex-end; display: flex; align-items: center; gap: 16px;">
                                    <ul class="pagination" style="margin-bottom: 0; margin-right: 0; vertical-align: middle;">
                                        <?php $disable_all = ($total_pages <= 1); ?>
                                        <li class="paginate_button previous<?php if ($curr_page <= 1 || $disable_all) echo ' disabled'; ?>">
                                            <a href="<?php echo ($curr_page > 1 && !$disable_all) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . ($curr_page-1) . '&per_page=' . $rec_per_page : '#'; ?>">Prev</a>
                                        </li>
                                        <?php for ($i = 1; $i <= max(1, $total_pages); $i++) { ?>
                                            <li class="paginate_button <?php if ($i == $curr_page) echo 'active'; if ($disable_all) echo ' disabled'; ?>">
                                                <a href="<?php echo (!$disable_all && $i != $curr_page) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . $i . '&per_page=' . $rec_per_page : '#'; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php } ?>
                                        <li class="paginate_button next<?php if ($curr_page >= $total_pages || $disable_all) echo ' disabled'; ?>">
                                            <a href="<?php echo ($curr_page < $total_pages && !$disable_all) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . ($curr_page+1) . '&per_page=' . $rec_per_page : '#'; ?>">Next</a>
                                        </li>
                                    </ul>
                                    <form method="get" class="form-inline" style="margin-bottom:0; margin-left:0; vertical-align: middle;">
                                        <input type="hidden" name="w_temple" value="<?php echo htmlspecialchars($w_temple); ?>">
                                        <select name="per_page" id="per_page" class="form-control input-sm" onchange="this.form.submit()" style="width:auto; height:34px; padding:6px 16px; margin-top:20px; margin-right:16px; font-size:14px; line-height:1.42857143; vertical-align:middle; display:inline-block;">
                                            <?php $options = [10, 20, 50, 100, 200]; foreach ($options as $opt) { ?>
                                                <option value="<?php echo $opt; ?>" <?php if ($rec_per_page == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="page" value="1">
                                    </form>
                                </div>
                            </div>
                        </div></div>                  
                </div>                  
            </div>                  

        </div>
    </div>
</div>
<div class="dataTables_paginate paging_simple_numbers pagination-flex-align" style="margin:10px 0 10px 0; justify-content: flex-end; display: flex; align-items: center; gap: 16px;">
    <ul class="pagination" style="margin-bottom: 0; margin-right: 0; vertical-align: middle;">
        <?php $disable_all = ($total_pages <= 1); ?>
        <li class="paginate_button previous<?php if ($curr_page <= 1 || $disable_all) echo ' disabled'; ?>">
            <a href="<?php echo ($curr_page > 1 && !$disable_all) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . ($curr_page-1) . '&per_page=' . $rec_per_page : '#'; ?>">Prev</a>
        </li>
        <?php for ($i = 1; $i <= max(1, $total_pages); $i++) { ?>
            <li class="paginate_button <?php if ($i == $curr_page) echo 'active'; if ($disable_all) echo ' disabled'; ?>">
                <a href="<?php echo (!$disable_all && $i != $curr_page) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . $i . '&per_page=' . $rec_per_page : '#'; ?>"><?php echo $i; ?></a>
            </li>
        <?php } ?>
        <li class="paginate_button next<?php if ($curr_page >= $total_pages || $disable_all) echo ' disabled'; ?>">
            <a href="<?php echo ($curr_page < $total_pages && !$disable_all) ? 'twise.php?w_temple=' . urlencode($w_temple) . '&page=' . ($curr_page+1) . '&per_page=' . $rec_per_page : '#'; ?>">Next</a>
        </li>
    </ul>
    <form method="get" class="form-inline" style="margin-bottom:0; margin-left:0; vertical-align: middle;">
        <input type="hidden" name="w_temple" value="<?php echo htmlspecialchars($w_temple); ?>">
        <select name="per_page" id="per_page" class="form-control input-sm" onchange="this.form.submit()" style="width:auto; height:34px; padding:6px 16px; margin-top:20px; margin-right:16px; font-size:14px; line-height:1.42857143; vertical-align:middle; display:inline-block;">
            <?php $options = [10, 20, 50, 100, 200]; foreach ($options as $opt) { ?>
                <option value="<?php echo $opt; ?>" <?php if ($rec_per_page == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
            <?php } ?>
        </select>
        <input type="hidden" name="page" value="1">
    </form>
</div>

<?php
include('../footer.php');
?>
