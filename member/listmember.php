<?php
include('../header.php');
// Pagination logic
$rec_per_page = isset($_GET['per_page']) && is_numeric($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
$curr_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($curr_page < 1) $curr_page = 1;
$offset = ($curr_page - 1) * $rec_per_page;

//var_dump($_POST);
// Replace the old search logic with new logic based on dropdown
$where = '';
if (isset($_POST['filter'])) {
    $search_field = isset($_POST['search_field']) ? $_POST['search_field'] : '';
    $search_value = isset($_POST['search_value']) ? trim($_POST['search_value']) : '';
    if ($search_field && $search_value !== '') {
        if ($search_field === 'member_id' || $search_field === 'mobile_no') {
            $where .= " and $search_field = '" . mysqli_real_escape_string($con, $search_value) . "'";
        } elseif ($search_field === 'name') {
            $where .= " and name like '%" . mysqli_real_escape_string($con, $search_value) . "%'";
        }
    }
}

// Get total count
$sql_total = "SELECT count(*) as total FROM $tbl_family  where `deleted`=0 $where ";
$result = mysqli_query($con, $sql_total);
$row = mysqli_fetch_assoc($result);
$total_records = $row['total'];
//echo $total_records . "<br>";
$total_pages = ceil($total_records / $rec_per_page);
//echo $total_pages;

// Fetch only the current page's members
$sql_page = "SELECT * FROM $tbl_family WHERE `deleted`=0 $where ORDER BY id DESC LIMIT $offset, $rec_per_page";
$result_page = mysqli_query($con, $sql_page);
$family = array();
while ($row = mysqli_fetch_assoc($result_page)) {
    $family[$row['id']] = $row;
}
$children = get_children();
?>	

<script>
    function print()
    {
        url = "printlist.php";
        title = "Family list";
        var newWindow = window.open(url, title, 'scrollbars=yes, width=1000, height=600');
    }
</script>
<style>
    #img.img-responsive{
        display: block;
        width: 100px;
        height: 100px;
    } 

    .table>thead:first-child>tr:first-child>th {
        border-top: 0;
        background: #e4ece7;
    }
    th{
        border:2px solid #d4d4d4;
    }
    .box-body {
        margin-top: 20px;
        width:90%;
        border-top-left-radius:10px;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        margin-left: 60px;
        margin-right: 40px;
        border: 2px solid #d4d4d4;
    }
    tbody#tbody > tr > td {
        border-top: 2px solid #d4d4d4;
    }
    #pagi{
        padding-left: 250px;  
    }
    #page{
        padding-right:70px;
    }
    #box.box{
        border:0px;
    }
    #filter
    {
        padding-left: 60px;
    }
    .pagination-flex-align {
        display: flex;
        align-items: center;
        gap: 10px;
        width: auto;
    }
    .pagination-flex-align ul.pagination {
        margin-bottom: 0;
        margin-top: 0;
        padding-left: 0;
    }
    .pagination-flex-align form {
        margin-bottom: 0;
        margin-top: 0;
        margin-left: 8px;
        padding: 0;
    }
    .pagination-flex-align select#per_page {
        height: 34px;
        padding: 2px 8px;
        margin: 0;
        font-size: 14px;
        line-height: 1.42857143;
        vertical-align: middle;
    }
</style>
<?php
//echo "<b>Families=$num_rows</b>";
?>
<div class="container-fluid">
    <h2 class="container text-center">Member List
        <button onclick="print()" class="btn btn-info pull-right"> <span class="glyphicon glyphicon-print"></span></button>
        <a href="addmember.php" class="btn btn-info pull-right" style="margin-right: 10px; font-weight: bold;">Add Member</a>
    </h2>
</div>
<!-- Main content -->

<div class="row">
    <div style="width:90%; padding-right: 20px;margin: 0 auto 10px auto; display: flex; flex-direction: row; align-items: flex-start; box-sizing: border-box;">
        <div style="flex: 1 1 0; min-width: 0;">
            <form method='POST' style="display: flex; align-items: center; gap: 10px; margin-bottom: 0;">
                <select name="search_field" class="form-control" style="width: 140px;">
                    <option value="member_id" <?php if(isset($_POST['search_field']) && $_POST['search_field']==='member_id') echo 'selected'; ?>>Member Id</option>
                    <option value="name" <?php if(isset($_POST['search_field']) && $_POST['search_field']==='name') echo 'selected'; ?>>Name</option>
                    <option value="mobile_no" <?php if(isset($_POST['search_field']) && $_POST['search_field']==='mobile_no') echo 'selected'; ?>>Mobile No</option>
                </select>
                <input type="text" name="search_value" class="form-control" placeholder="Search..." value="<?php echo isset($_POST['search_value']) ? htmlspecialchars($_POST['search_value']) : ''; ?>" style="width: 50%; min-width: 100px;">
                <input type="hidden" name="filter">
                <button type="submit" name="submit" class="btn btn-info">Search</button>
            </form>
        </div>
        <div style="flex: 1 1 0; min-width: 0; display: flex; justify-content: flex-end; align-items: center;">
            <div class="dataTables_paginate paging_simple_numbers pagination-flex-align" id="example2_paginate" style="display: flex; align-items: center; gap: 10px; margin: 0;">
                <ul id="pagi" class="pagination" style="margin-bottom: 0;">
                    <?php
                    // Always show pagination controls
                    $disable_all = ($total_pages <= 1);
                    ?>
                    <li class="paginate_button previous<?php if ($curr_page <= 1 || $disable_all) echo ' disabled'; ?>" id="example2_previous">
                        <a href="<?php echo ($curr_page > 1 && !$disable_all) ? 'listmember.php?page=' . ($curr_page-1) . '&per_page=' . $rec_per_page : '#'; ?>" aria-controls="example2" data-dt-idx="0" tabindex="0">Prev</a>
                    </li>
                    <?php
                    for ($i = 1; $i <= max(1, $total_pages); $i++) {
                        ?>
                        <li class="paginate_button <?php if ($i == $curr_page) echo 'active'; if ($disable_all) echo ' disabled'; ?>">
                            <a href="<?php echo (!$disable_all && $i != $curr_page) ? 'listmember.php?page=' . $i . '&per_page=' . $rec_per_page : '#'; ?>" aria-controls="example2" data-dt-idx="1" tabindex="0"><?php echo $i; ?></a>
                        </li>
                    <?php } ?>
                    <li class="paginate_button next<?php if ($curr_page >= $total_pages || $disable_all) echo ' disabled'; ?>" id="example2_next">
                        <a href="<?php echo ($curr_page < $total_pages && !$disable_all) ? 'listmember.php?page=' . ($curr_page+1) . '&per_page=' . $rec_per_page : '#'; ?>" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a>
                    </li>
                </ul>
                <form method="get" class="form-inline" style="margin-bottom:0; margin-left:10px; display: flex; align-items: center; gap: 10px;">
                    <select name="per_page" id="per_page" class="form-control input-sm" onchange="this.form.submit()" style="width:auto; height:30px; padding:2px 6px; display:inline-block; vertical-align:middle;">
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
<div class="row">	  
    <div id="box" class="box table-responsive" style="margin-bottom:0">
        <div class="box-body table-responsive no-padding" style="margin-top: 0px; ">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="3%">S.No</th>
                        <th width="15%">Photo</th>
                        <th width="20%">Husband,Wife& Mobile </th>
                        <th width="16%">Parents</th>
                        <th width="25%">Address</th>
                        <th width="17%">Children</th>

                    </tr>
                </thead>
                <tbody id="tbody">

                    <?php
                    $counter = 0;
                    if ($family) {
                        foreach ($family as $k => $v) {
                            echo '<tr>';
                            echo"<td>";
                            echo ++$counter;
                            echo"</td>";
                            ?>	
                        <td>
                            <!-- <a id="a" href="viewmember.php?id=<?php echo $k ?>">       <?php echo $v['member_id'] ?></a><br> -->
                            <img src="<?php echo !empty($v['image']) ? '../images/member/' . $v['image'] : '../images/default.png'; ?>" id="img" class="img-responsive"/> 
                        </td>
                        <td>
                            <a id="a" href="viewmember.php?id=<?php echo $k ?>"> <?php echo $v['name'] . " (" . $v['h_age'] . ")" ?> </a>	<?php echo "<br>" . $v['w_name'] . " (" . $v['w_age'] . ")" . "<br>" . $v['mobile_no'] ?> 	
                        </td>
                        <?php
                        echo "<td>" . $v['father_name'] . " <br>" . $v['mother_name'] . "</td>";
                        echo "<td>" . str_replace("\n", "<br />", $v['permanent_address']) . "</td>";
                        echo "<td>";
                        if (isset($children[$k])) {
                            $c = $children[$k];
                            foreach ($c as $k1 => $v1) {
                                echo $v1['c_name'] . " ( " . $v1['c_age'] . " ) ";
                                echo "<br>";
                            }
                        } else {
                            echo"-";
                        }
                        echo "</td></tr>";
                    }
                }
                ?>               
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>

<!-- Bottom pagination controls -->
<div style="width:90%; padding-right: 20px; margin: 10px auto 10px auto; display: flex; justify-content: flex-end; align-items: center; box-sizing: border-box;">
    <div class="dataTables_paginate paging_simple_numbers pagination-flex-align" id="example2_paginate_bottom" style="display: flex; align-items: center; gap: 10px; margin: 0;">
        <ul id="pagi_bottom" class="pagination" style="margin-bottom: 0;">
            <?php
            // Always show pagination controls
            $disable_all = ($total_pages <= 1);
            ?>
            <li class="paginate_button previous<?php if ($curr_page <= 1 || $disable_all) echo ' disabled'; ?>" id="example2_previous_bottom">
                <a href="<?php echo ($curr_page > 1 && !$disable_all) ? 'listmember.php?page=' . ($curr_page-1) . '&per_page=' . $rec_per_page : '#'; ?>" aria-controls="example2" data-dt-idx="0" tabindex="0">Prev</a>
            </li>
            <?php
            for ($i = 1; $i <= max(1, $total_pages); $i++) {
                ?>
                <li class="paginate_button <?php if ($i == $curr_page) echo 'active'; if ($disable_all) echo ' disabled'; ?>">
                    <a href="<?php echo (!$disable_all && $i != $curr_page) ? 'listmember.php?page=' . $i . '&per_page=' . $rec_per_page : '#'; ?>" aria-controls="example2" data-dt-idx="1" tabindex="0"><?php echo $i; ?></a>
                </li>
            <?php } ?>
            <li class="paginate_button next<?php if ($curr_page >= $total_pages || $disable_all) echo ' disabled'; ?>" id="example2_next_bottom">
                <a href="<?php echo ($curr_page < $total_pages && !$disable_all) ? 'listmember.php?page=' . ($curr_page+1) . '&per_page=' . $rec_per_page : '#'; ?>" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a>
            </li>
        </ul>
        <form method="get" class="form-inline" style="margin-bottom:0; margin-left:10px; display: flex; align-items: center; gap: 10px;">
            <select name="per_page" id="per_page_bottom" class="form-control input-sm" onchange="this.form.submit()" style="width:auto; height:30px; padding:2px 6px; display:inline-block; vertical-align:middle;">
                <?php $options = [10, 20, 50, 100, 200]; foreach ($options as $opt) { ?>
                    <option value="<?php echo $opt; ?>" <?php if ($rec_per_page == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                <?php } ?>
            </select>
            <input type="hidden" name="page" value="<?php echo $curr_page; ?>">
        </form>
    </div>
</div>
<div style="clear:both"></div>  	  
<?php
include('../footer.php');
?>