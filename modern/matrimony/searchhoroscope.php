<?php
include('../init.php');
check_login();
include('../includes/header.php');

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
            $where .= "$k,";
            $star[$k]= $k;
        }
        $where .= " $k ) ";   
    }
	
    if (isset($_POST['raghu_kedhu']) ) {
        $where .= ' AND raaghu_kaedhu > 0  ';
    }
    if (isset($_POST['sevvai']) ) {
        $where .= ' AND sevvai > 0 ';
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">Search Horoscope</h2>
            
            <div class="row">
                <!-- Search Filters Sidebar -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-funnel"></i> Filter
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="searchhoroscope.php" method="POST">
                                <ul class="list-group list-group-flush">
                                    <!-- Submit Button -->
                                    <li class="list-group-item border-0">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-search"></i> Submit
                                        </button>
                                    </li>
                                    
                                    <!-- Height Filter -->
                                    <li class="list-group-item border-0">
                                        <div class="d-flex align-items-center gap-2 filter-row">
                                            <label class="form-label mb-0 me-2" style="min-width:60px;">Height:</label>
                                            <?php display_height_horo("height_from", $height_from) ?>
                                            <span class="mx-2">to</span>
                                            <?php display_height_horo("height_to", $height_to) ?>
                                            <span class="ms-2 unit-label">cms</span>
                                        </div>
                                    </li>
                                    
                                    <li class="list-group-item border-0">
                                        <hr class="my-2">
                                    </li>
                                    
                                    <!-- Age Filter -->
                                    <li class="list-group-item border-0">
                                        <div class="d-flex align-items-center gap-2 filter-row">
                                            <label class="form-label mb-0 me-2" style="min-width:60px;">Age:</label>
                                            <input type="text" class="form-control form-control-sm" name="age_from" value="<?php echo $age_from?>" placeholder="from" style="max-width:70px;">
                                            <span class="mx-2">to</span>
                                            <input type="text" class="form-control form-control-sm" name="age_to" value="<?php echo $age_to?>" placeholder="to" style="max-width:70px;">
                                            <span class="ms-2 unit-label">Yrs</span>
                                        </div>
                                    </li>
                                    
                                    <li class="list-group-item border-0">
                                        <hr class="my-2">
                                    </li>
                                    
                                    <!-- Star Filter -->
                                    <li class="list-group-item border-0">
                                        <label class="form-label">Star:</label>
                                        <div style="height:300px; overflow-y: scroll; border: 1px solid #dee2e6; border-radius: 5px; padding: 10px;">
                                            <?php display_star_checkbox('star', $star, 'width:170px;') ?>
                                        </div>
                                    </li>
                                    
                                    <li class="list-group-item border-0">
                                        <hr class="my-2">
                                    </li>
                                    
                                    <!-- Raghu/Kedhu Filter -->
                                    <li class="list-group-item border-0">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <label class="form-label mb-0">Raghu/Kedhu:</label>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-check">
                                                    <?php 
                                                    if(isset($_POST['raghu_kedhu'])) $checked = " checked ";
                                                    else $checked = '';
                                                    ?>
                                                    <input class="form-check-input" type="checkbox" name="raghu_kedhu" <?php echo $checked ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <li class="list-group-item border-0">
                                        <hr class="my-2">
                                    </li>
                                    
                                    <!-- Sevvai Filter -->
                                    <li class="list-group-item border-0">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <label class="form-label mb-0">Sevvai:</label>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-check">
                                                    <?php 
                                                    if(isset($_POST['sevvai'])) $checked = " checked ";
                                                    else $checked = '';
                                                    ?>
                                                    <input class="form-check-input" type="checkbox" name="sevvai" <?php echo $checked ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <li class="list-group-item border-0">
                                        <hr class="my-2">
                                    </li>
                                    
                                    <!-- Marital Status Filter -->
                                    <li class="list-group-item border-0">
                                        <label class="form-label">Marital Status:</label>
                                        <?php 
                                        if(!isset($_POST['marital_status']))
                                            $_POST['marital_status']['No'] = 'on';
                                        ?>
                                        <?php display_marital_checkbox('marital_status', $_POST['marital_status']) ?>
                                    </li>
                                    
                                    <li class="list-group-item border-0">
                                        <hr class="my-2">
                                    </li>
                                    
                                    <!-- Kulam Filter -->
                                    <li class="list-group-item border-0">
                                        <div class="row align-items-center">
                                            <div class="col-3">
                                                <label class="form-label mb-0">Kulam:</label>
                                            </div>
                                            <div class="col-9">
                                                <?php display_kulam_list("kulam") ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                
                                <button type="submit" class="btn btn-primary w-100 mt-3">
                                    <i class="bi bi-search"></i> Submit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Search Results -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-ul"></i> Search Results
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php
                            if (count($_POST) > 1) {
                                $sql = "SELECT * FROM $tbl_matrimony WHERE `deleted`=0 $where ORDER BY id DESC";
                                $result = mysqli_query($con, $sql);
                                $count = mysqli_num_rows($result);
                                
                                if ($count > 0) {
                                    echo '<div class="alert alert-info">Found ' . $count . ' matching profiles</div>';
                                    ?>
                                    <div class="row">
                                        <?php
                                        while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <div class="col-md-6 mb-4">
                                                <div class="card h-100">
                                                    <div class="card-header">
                                                        <h6 class="card-title mb-0">
                                                            <strong><?php echo $row['name']; ?></strong>
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4 text-center">
                                                                <img src="../images/horo/<?php echo $row['photo'] ?: 'default.jpg' ?>" 
                                                                     class="img-thumbnail" 
                                                                     style="width: 120px; height: 140px; object-fit: cover;"
                                                                     alt="Profile Photo">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Age:</strong></div>
                                                                    <div class="col-7"><?php echo $row['age']; ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Marital Status:</strong></div>
                                                                    <div class="col-7"><?php echo get_marital_status($row['marital_status']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Education:</strong></div>
                                                                    <div class="col-7"><?php echo $row['qualification']; ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Occupation:</strong></div>
                                                                    <div class="col-7"><?php echo $row['occupation']; ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Raasi:</strong></div>
                                                                    <div class="col-7"><?php echo get_raasi($row['raasi']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Star:</strong></div>
                                                                    <div class="col-7"><?php echo get_star($row['star']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Raghu/Kedhu:</strong></div>
                                                                    <div class="col-7"><?php echo ($row['raaghu_kaedhu'] > 0) ? "Yes" : "No"; ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Sevvai:</strong></div>
                                                                    <div class="col-7"><?php echo ($row['sevvai'] > 0) ? "Yes" : "No"; ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Kulam:</strong></div>
                                                                    <div class="col-7"><?php echo get_kulam($row['kulam']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5"><strong>Temple:</strong></div>
                                                                    <div class="col-7"><?php echo $row['temple']; ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <div class="row mb-2">
                                                                <div class="col-12">
                                                                    <strong>Contact:</strong><br>
                                                                    <small><?php echo $row['mobile_no']; ?><br><?php echo $row['email']; ?><br><?php echo $row['address']; ?></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="btn-group w-100" role="group">
                                                            <a href="viewhoroscope.php?id=<?php echo $row['id']; ?>" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye"></i> View
                                                            </a>
                                                            <a href="updatehoroscope.php?id=<?php echo $row['id']; ?>" 
                                                               class="btn btn-sm btn-outline-warning">
                                                                <i class="bi bi-pencil"></i> Edit
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                } else {
                                    echo '<div class="alert alert-warning">No matching profiles found for your search criteria.</div>';
                                }
                            } else {
                                echo '<div class="alert alert-info">Use the filters on the left to search for horoscope profiles.</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-check-input {
        transform: scale(1.2);
    }
    
    .list-group-item {
        padding: 0.75rem 0;
    }
    
    .list-group-item hr {
        margin: 0.5rem 0;
    }

.filter-row label {
  white-space: nowrap;
}
.unit-label {
  white-space: nowrap;
  min-width: 28px;
  text-align: left;
}
.filter-row input,
.filter-row select {
  min-width: 70px;
  max-width: 90px;
}
/* Increase max-width for height select boxes */
.filter-row select[name^='height_'] {
  max-width: 130px;
}
</style>

<?php include('../includes/footer.php'); ?>			  
