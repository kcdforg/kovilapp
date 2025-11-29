<?php
//require_once(dirname(__FILE__) . "/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_login() {
    global $path;
    //Check whether the session variable username is present or not
    if (!isset($_SESSION['username']) || (($_SESSION['username']) == '')) {
        redirect('index.php');
        exit; // Ensure script stops after redirect
    }
}

function is_logged_in() {
    return isset($_SESSION['username']) && !empty($_SESSION['username']);
}

function connectdb() {
    global $db_host, $db_name, $db_user, $db_pass, $con;
    //connect only if the $con variable is false..  if it is true, it means already connection established
    if ($con == false) {
        $con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (!$con) {
            die('Could not connect ' . mysqli_connect_error());
        }
        mysqli_set_charset($con, 'utf8');
    }
    return($con);
}

function getdata($sql) {
    global $con;
    $result = mysqli_query($con, $sql);
    return $result;
}

function display($field, $row) {
    echo isset($row[$field]) ? "$row[$field]" : " ";
}

function display_donation($field, $row1) {
    echo isset($row1[$field]) ? "$row1[$field]" : " ";
}

function login($username, $password) {
    global $tbl_users, $con;
    $sql = "SELECT * FROM `$tbl_users` WHERE username='$username'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    if ($row) {
        if (md5($password) == $row['password']) {
            return true;
        }
    }
    return false;
}

function redirect($loc) {
    global $path;
    header('Location:' . $path . "/" . $loc);
}

function get_families($cond = '') {
    global $con, $tbl_family;
    $h_age = " DATE_FORMAT( NOW( ) , '%Y' ) - DATE_FORMAT( dob, '%Y' ) - ( DATE_FORMAT( NOW( ) , '00-%m-%d' ) < DATE_FORMAT( dob, '00-%m-%d' ) ) AS h_age ";
    $w_age = " DATE_FORMAT( NOW( ) , '%Y' ) - DATE_FORMAT( w_dob, '%Y' ) - ( DATE_FORMAT( NOW( ) , '00-%m-%d' ) < DATE_FORMAT( w_dob, '00-%m-%d' ) ) AS w_age ";
    if ($cond != '')
        $where = " $cond ";
    else
        $where = '';
    $sql = "SELECT *,$h_age,$w_age FROM $tbl_family where `deleted`=0 $where ";
    $result = mysqli_query($con, $sql);
    $family = array();
    while ($row = mysqli_fetch_array($result)) {
        $family[$row['id']] = $row;
    }
    return $family;
}

function get_child($child_id) {
    global $con, $tbl_child;
    $sql = "SELECT * from $tbl_child where id=$child_id ";
    $result = mysqli_query($con, $sql);
    return mysqli_fetch_array($result);
}

function get_donationlist($id) {
    global $con, $tbl_donation;
    $sql = "SELECT * from $tbl_donation where id=$id ";
    $result = mysqli_query($con, $sql);
    return mysqli_fetch_array($result);
}

function get_children($father_id = '') {
    global $con, $tbl_child;
    $c_age = " DATE_FORMAT( NOW( ) , '%Y' ) - DATE_FORMAT( c_dob, '%Y' ) - ( DATE_FORMAT( NOW( ) , '00-%m-%d' ) < DATE_FORMAT( c_dob, '00-%m-%d' ) ) AS c_age ";
    if ($father_id == '') {
        $where = '';
    } else {
        $where = " where father_id = $father_id";
    }
    $sql = "SELECT *,$c_age from $tbl_child $where ";
    $result = mysqli_query($con, $sql);
    $children = array();
    while ($row = mysqli_fetch_array($result)) {
        $children[$row['father_id']][$row['id']] = $row;
    }
    return $children;
}

function count_family() {
    global $con, $tbl_family;
    $query = "SELECT count(*) AS total FROM  $tbl_family";
    $count = mysqli_query($con, $query);
    $values = mysqli_fetch_assoc($count);
    $num_rows = $values['total'];
    return $num_rows;
}

// Helper to handle NULLs and escaping
function sql_null_or_str($val) {
    global $con;
    return ($val === '' ? 'NULL' : "'" . mysqli_real_escape_string($con, $val) . "'");
}
function sql_null_or_int($val) {
    return ($val === '' ? 0 : (int)$val);
}

function add_member($m) {
    global $con, $tbl_family;
    if (count($m) && $m['name'] != '') {
        $name = sql_null_or_str($m['name']);
        $father_name = sql_null_or_str($m['father_name']);
        $mother_name = sql_null_or_str($m['mother_name']);
        $dob = sql_null_or_str($m['dob']);
        $blood_group = sql_null_or_int($m['blood_group']);
        $qualification = sql_null_or_int($m['qualification']);
        $education_details = sql_null_or_str($m['education_details']);
        $occupation = sql_null_or_int($m['occupation']);
        $occupation_details = sql_null_or_str($m['occupation_details']);
        $email = sql_null_or_str($m['email']);
        $pudavai = sql_null_or_str($m['pudavai']);
        $mobile_no = sql_null_or_str($m['mobile_no']);
        $permanent_address = sql_null_or_str($m['permanent_address']);
        $current_address = sql_null_or_str($m['current_address']);
        $village = sql_null_or_str($m['village']);
        $district = sql_null_or_str($m['district']);
        $country = sql_null_or_str($m['country']);
        $taluk = sql_null_or_str($m['taluk']);
        $c_village = sql_null_or_str($m['c_village']);
        $c_taluk = sql_null_or_str($m['c_taluk']);
        $c_district = sql_null_or_str($m['c_district']);
        $c_country = sql_null_or_str($m['c_country']);
        $kattalai = sql_null_or_int($m['kattalai']);
        $k_village = sql_null_or_str($m['k_village']);
        $w_name = sql_null_or_str($m['w_name'] ?? '');
        $w_dob = sql_null_or_str($m['w_dob'] ?? '');
        $w_blood_group = sql_null_or_int($m['w_blood_group'] ?? '');
        $w_qualification = sql_null_or_int($m['w_qualification'] ?? '');
        $w_education_details = sql_null_or_str($m['w_education_details'] ?? '');
        $w_occupation_details = sql_null_or_str($m['w_occupation_details'] ?? '');
        $w_occupation = sql_null_or_int($m['w_occupation'] ?? '');
        $w_kootam = sql_null_or_int($m['w_kootam'] ?? '');
        $w_temple = sql_null_or_str($m['w_temple'] ?? '');
        $w_email = sql_null_or_str($m['w_email'] ?? '');
        $ic = sql_null_or_str($m['ic']);
        $remarks = sql_null_or_str($m['remarks']);
        $pincode = sql_null_or_str($m['pincode']);
        $c_pincode = sql_null_or_str($m['c_pincode']);
        $state = sql_null_or_str($m['state']);
        $c_state = sql_null_or_str($m['c_state']);
        $same_as_permanent = isset($m['same_as_permanent']) ? sql_null_or_int($m['same_as_permanent']) : 0;
        $join_date = sql_null_or_str(date('Y-m-d H:i:s'));
        $created_date = sql_null_or_str(date('Y-m-d H:i:s'));
        $created_by = isset($_SESSION['ID']) && is_numeric($_SESSION['ID']) ? (int)$_SESSION['ID'] : 'NULL';
        $deleted = 0;

        $sql = "INSERT INTO `$tbl_family`(`name`,`father_name`, `mother_name`,`dob`, `blood_group`, `qualification`,  `occupation`, `email`, `pudavai`, `mobile_no`, `permanent_address`, `current_address`, `village`, `district`, `country`,`taluk`, `c_village`, `c_district`, `c_country`,`c_taluk`, `kattalai`,`k_village`, `w_name`, `w_dob`, `w_blood_group`, `w_qualification`,`w_occupation`, `w_kootam`, `w_temple`, `w_email`, `created_by`, `created_date`, `occupation_details`, `education_details`, `w_education_details`, `w_occupation_details`,`pincode`,`state`,`c_pincode`,`c_state`,`ic`, `same_as_permanent`, `deleted`)"
                . " VALUES ($name,$father_name, $mother_name, $dob,  $blood_group, $qualification, $occupation, $email, $pudavai,$mobile_no,$permanent_address,$current_address, $village,$district,$country,$taluk,$c_village,$c_district,$c_country,$c_taluk, $kattalai,$k_village, $w_name,$w_dob,$w_blood_group,$w_qualification,$w_occupation,$w_kootam,$w_temple,$w_email,$created_by,$created_date,$education_details,$occupation_details,$w_education_details,$w_occupation_details,$c_pincode,$c_state,$pincode,$state,$ic, $same_as_permanent, $deleted)";
        //echo $sql
        if (mysqli_query($con, $sql)) {
            $id = mysqli_insert_id($con);
            return $id;
        } else {
            return false;
        }
    }
}

function get_member($id) {
    global $con, $tbl_family;

    $h_age = " DATE_FORMAT( NOW( ) , '%Y' ) - DATE_FORMAT( dob, '%Y' ) - ( DATE_FORMAT( NOW( ) , '00-%m-%d' ) < DATE_FORMAT( dob, '00-%m-%d' ) ) AS h_age ";
    $w_age = " DATE_FORMAT( NOW( ) , '%Y' ) - DATE_FORMAT( w_dob, '%Y' ) - ( DATE_FORMAT( NOW( ) , '00-%m-%d' ) < DATE_FORMAT( w_dob, '00-%m-%d' ) ) AS w_age ";
    $sql = "SELECT *,$h_age,$w_age FROM `$tbl_family` WHERE `id`='$id' AND `deleted`=0";

    //echo $sql;

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    return $row;
}

function get_donation($id = '', $type = '') {
    global $con, $tbl_donation;

    $where = "1=1";
    if ($id)
        $where .= ' and member_id = ' . $id;

    if ($type)
        $where .= " and type='$type'";

    $sql = "SELECT * FROM `$tbl_donation` WHERE $where ";
    //$sql = "SELECT * FROM `$tbl_donation` where 1=1 and member_id = 1 ";
    //echo $sql;
    $result1 = mysqli_query($con, $sql);
    $res = array();
    while ($row1 = mysqli_fetch_array($result1)) {
        $res[$row1['member_id']][] = $row1;
    }
    return $res;
}

function count_child($father_id) {
    global $con, $tbl_child;

    $query = "SELECT count(*) AS total FROM `$tbl_child` WHERE `father_id`='$father_id'";
    $count = mysqli_query($con, $query);
    $values = mysqli_fetch_assoc($count);
    $num_rows = $values['total'];
    return $num_rows;
}

function add_child($child) {
    global $con, $tbl_child;
    if (count($child) && $child['c_name'] != '') {
        $father_id = $child['father_id'];
        $fam_id = 0; // Set fam_id as 0 initially (not linked to any family yet)
        $c_name = $child['c_name'];
        $c_dob = $child['c_dob'];
        $c_gender = $child['c_gender'];
        $c_blood_group = $child['c_blood_group'];
        $c_marital_status = $child['c_marital_status'];
        $c_qualification = $child['c_qualification'];
        $c_mobile_no = $child['c_mobile_no'];
        $c_email = $child['c_email'];
        $c_occupation = $child['c_occupation'];
        $c_education_details = $child['c_education_details'];
        $c_occupation_details = $child['c_occupation_details'];
        $c_image = '';
        $c_created_date = date('Y-m-d');
        $c_created_by = $_SESSION['username'] ?? 'admin';
        $c_lastmodified_by = $_SESSION['username'] ?? 'admin';
        $c_lastmodified_date = date('Y-m-d');

        $sql = "INSERT INTO `$tbl_child`(`fam_id`, `father_id`, `c_name`, `c_dob`, `c_gender`, `c_blood_group`, `c_marital_status`,`c_qualification`, `c_mobile_no`, `c_email`, `c_occupation`, `c_education_details`, `c_occupation_details`, `c_image`, `c_created_date`, `c_created_by`, `c_lastmodified_by`, `c_lastmodified_date`) 
					VALUES (0, '$father_id', '$c_name', '$c_dob', '$c_gender', '$c_blood_group', '$c_marital_status', '$c_qualification', '$c_mobile_no', '$c_email', '$c_occupation', '$c_education_details', '$c_occupation_details', '$c_image', '$c_created_date', '$c_created_by', '$c_lastmodified_by', '$c_lastmodified_date')";
        $result = mysqli_query($con, $sql);
        
        // Regenerate family tree after adding child
        if ($result && $father_id > 0) {
            regenerateFamilyTree($father_id);
        }
        
        return $result;
    }
}

function add_donation($donation) {
    global $con, $tbl_donation;
    if (count($donation) && $donation['amount'] != '') {
        $name = $donation['name'];
        $reg_code = $donation['reg_code'];
        $member_id = $donation['member_id'];
        $type = $donation['type'];
        $amount = $donation['amount'];
        $paid_on = $donation['paid_on'];
        $receipt_no = $donation['receipt_no'];
        $sub_year = $donation['sub_year'];
       $created_date = date('d-m-Y H:i:s');
        $created_by = $_SESSION['id'];
        $last_modified_date = date('d-m-Y H:i:s');
        $last_modified_by = $_SESSION['id'];
        $sql = "INSERT INTO `$tbl_donation`(`name`,`type`, `amount`, `paid_on`, `receipt_no`, `sub_year`,`member_id`,`reg_code`,`created_date`,`created_by`,`last_modified_date`,`last_modified_by`) 
		             VALUES ('$name','$type', '$amount', '$paid_on', '$receipt_no', '$sub_year','$reg_code','$member_id','$created_date','$created_by','$last_modified_date','$last_modified_by')";
        return mysqli_query($con, $sql);
    }
}
function add_horoscope($horo) {

    global $con, $tbl_matrimony;
    // echo 'inside function <br><br>';
    //var_dump($horo);
    if (count($horo) && $horo['name'] != '') {
        $name = $horo['name'];
        $father_name = $horo['father_name'];
        $mother_name = $horo['mother_name'];
        $gender = $horo['gender'];
        $age = $horo['age'];
        //   $birth_date = $horo['birth_date'];
        //   $birth_time = $horo['birth_time'];

        $birth_place = $horo['birth_place'];
        $raasi = $horo['raasi'];
        $star = $horo['star'];
        $laknam = $horo['laknam'];
        $padham = $horo['padham'];
        // $raaghu_kaedhu = $horo['raaghu_kaedhu'];
        $raaghu_kaedhu = isset($horo['raaghu_kaedhu']) ? "1" : "0";
        $marital_status = $horo['marital_status'];
        //$sevvai = $horo['sevvai'];
        $sevvai = isset($horo['sevvai']) ? "1" : "0";
        $kulam = $horo['kulam'];
        $m_kulam = $horo['m_kulam'];
        $mm_kulam = $horo['mm_kulam'];
        $pm_kulam = $horo['pm_kulam'];
        $temple = $horo['temple'];
        $height = $horo['height'];
        $weight = $horo['weight'];
        $colour = $horo['colour'];
        $blood_group = $horo['blood_group'];
        $qualification = $horo['qualification'];
        $education_details = $horo['education_details'];
        $college_details = $horo['college_details'];
        $occupation = $horo['occupation'];
        $occupation_details = $horo['occupation_details'];
        $income = $horo['income'];
        $asset_details = $horo['asset_details'];
        $email = $horo['email'];
        $mobile_no = $horo['mobile_no'];
        $contact_person = $horo['contact_person'];
        $relationship = $horo['relationship'];
        $address = $horo['address'];
        $country = $horo['country'];
        //  $referred_by = $horo['referred_by'];
        $ref_id = $horo['ref_id'];
        //   $registered_date = $horo['registered_date'];
        $sibling = $horo['sibling'];
        $f_occupation = $horo['f_occupation'];
        $m_occupation = $horo['m_occupation'];
        //     $about_myself = $horo['about_myself'];
        //    $admin_notes = $horo['admin_notes'];
        // $status = $horo['status'];
        //  $close_reason_code=$horo['close_reason_code'];
        // $married_to = $horo['married_to'];
        // $close_reason_detail=$horo['close_reason_detail'];
        //echo "<br /><br />" . $ref_id;
        $pp_occupation = $horo['pp_occupation'];
        $pp_education = $horo['pp_education'];
        $pp_work_location = $horo['pp_work_location'];
        $pp_salary = $horo['pp_salary'];
        $pp_asset_details = $horo['pp_asset_details'];
        $pp_expectation = $horo['pp_expectation'];

        $reg_no = intval($horo['reg_no'] ?? get_max_mat_no());
        $referred_by = $horo['referred_by'] ?? '';
        $registered_date = $horo['registered_date'] ?? date('Y-m-d'); // date only
        $about_myself = $horo['about_myself'] ?? '';
        
        // Additional fields that might be in database
        $status = $horo['status'] ?? 'active';
        $admin_notes = $horo['admin_notes'] ?? '';
        $close_reason_code = intval($horo['close_reason_code'] ?? 0); // int(11)
        $married_to = intval($horo['married_to'] ?? 0); // int(11)
        $close_reason_detail = $horo['close_reason_detail'] ?? '';
        
        // Missing fields from schema
        $photo = $horo['photo'] ?? '';
        $horo_file = $horo['horo'] ?? '';
        $deleted = intval($horo['deleted'] ?? 0); // tinyint(1)
        
        // Correct data types for boolean fields (should be varchar in schema)
        $raaghu_kaedhu = isset($horo['raaghu_kaedhu']) ? "1" : "0"; // varchar(25)
        $sevvai = isset($horo['sevvai']) ? "1" : "0"; // varchar(25)
        
        // Ensure integer fields are properly converted
        $age = intval($horo['age'] ?? 0);
        $height = intval($horo['height'] ?? 0);
        $weight = intval($horo['weight'] ?? 0);
        $ref_id = intval($horo['ref_id'] ?? 0);
        
        // Fix birth_date formatting to handle empty values
        $birth_day = $horo['birth_date']['day'] ?? '';
        $birth_month = $horo['birth_date']['month'] ?? '';
        $birth_year = $horo['birth_date']['year'] ?? '';
        
        // Only create date if all components are present
        if (!empty($birth_day) && !empty($birth_month) && !empty($birth_year)) {
            $birth_date = $birth_year . "-" . str_pad($birth_month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($birth_day, 2, '0', STR_PAD_LEFT);
        } else {
            $birth_date = '0000-00-00'; // Default date for empty values
        }
        
        // Fix birth_time formatting to handle empty values
        $birth_hour = $horo['birth_time']['hour'] ?? '';
        $birth_min = $horo['birth_time']['min'] ?? '';
        
        // Only create time if both components are present
        if (!empty($birth_hour) && !empty($birth_min)) {
            $birth_time = str_pad($birth_hour, 2, '0', STR_PAD_LEFT) . ":" . str_pad($birth_min, 2, '0', STR_PAD_LEFT) . ":00";
        } else {
            $birth_time = '00:00:00'; // Default time for empty values
        }

        $sql = "INSERT INTO `$tbl_matrimony` (`reg_no`,`name`,`father_name`, `mother_name`,`gender`, `age`, `blood_group`, `qualification`,  `occupation`, `email`, `birth_date`, `birth_time`,`birth_place`,`mobile_no`, `address`,`sibling`, `raasi`,`star`,`laknam`,`padham`,`raaghu_kaedhu`, `sevvai`, `marital_status`, `country`,`income`,`kulam`, `temple`,`m_kulam`, `mm_kulam`, `pm_kulam`,`height`, `weight`, `colour`,`f_occupation`,`m_occupation`,`education_details`,`college_details`,`occupation_details`,`ref_id`,`referred_by`,`registered_date`,`about_myself`,`status`,`admin_notes`,`photo`,`horo`,`deleted`,`close_reason_code`,`married_to`,`close_reason_detail`,`asset_details`,`pp_occupation`,`pp_education`,`pp_work_location`,`pp_salary`,`pp_asset_details`,`pp_expectation`,`contact_person`,`relationship`) 
                                    VALUES ('$reg_no','$name', '$father_name', '$mother_name', '$gender', '$age', '$blood_group', '$qualification', '$occupation', '$email', '$birth_date','$birth_time','$birth_place','$mobile_no', '$address','$sibling','$raasi', '$star','$laknam','$padham','$raaghu_kaedhu','$sevvai','$marital_status','$country','$income','$kulam','$temple','$m_kulam','$mm_kulam','$pm_kulam','$height','$weight','$colour','$f_occupation','$m_occupation','$education_details','$college_details','$occupation_details','$ref_id','$referred_by','$registered_date','$about_myself','$status','$admin_notes','$photo','$horo_file','$deleted','$close_reason_code','$married_to','$close_reason_detail','$asset_details' ,'$pp_occupation','$pp_education','$pp_work_location','$pp_salary','$pp_asset_details','$pp_expectation','$contact_person','$relationship')";
        //echo $sql;
        return mysqli_query($con, $sql);
    }
}

function update_horoscope($id, $hdata) {

    global $con, $tbl_matrimony;
    if (count($hdata) > 0) {
        $name = $hdata['name'];
        $father_name = $hdata['father_name'];
        $mother_name = $hdata['mother_name'];
        $gender = $hdata['gender'];
        $age = $hdata['age'];
        $birth_date = $hdata['birth_date']['year'] . "-" . $hdata['birth_date']['month'] . "-" . $hdata['birth_date']['day'];
        $birth_time = $hdata['birth_time']['hour'] . ":" . $hdata['birth_time']['min'] . ":00";
        $birth_place = $hdata['birth_place'];
        $raasi = $hdata['raasi'];
        $star = $hdata['star'];
        $laknam = $hdata['laknam'];
        $padham = $hdata['padham'];
        $raaghu_kaedhu = $hdata['raaghu_kaedhu'];
        $marital_status = $hdata['marital_status'];
        //    $status = $hdata['status'];
        $sevvai = $hdata['sevvai'];
        $kulam = $hdata['kulam'];
        $m_kulam = $hdata['m_kulam'];
        $mm_kulam = $hdata['mm_kulam'];
        $pm_kulam = $hdata['pm_kulam'];
        $temple = $hdata['temple'];
        $height = $hdata['height'];
        $weight = $hdata['weight'];
        $colour = $hdata['colour'];
        $blood_group = $hdata['blood_group'];
        $qualification = $hdata['qualification'];
        $education_details = $hdata['education_details'];
        $college_details = $hdata['college_details'];
        $occupation = $hdata['occupation'];
        $occupation_details = $hdata['occupation_details'];
        $income = $hdata['income'];
        $email = $hdata['email'];
        $mobile_no = $hdata['mobile_no'];
        $contact_person = $hdata['contact_person'];
        $relationship = $hdata['relationship'];
        $address = $hdata['address'];
        $country = $hdata['country'];
        //  $referred_by = $hdata['referred_by'];
        //$registered_date = $hdata['registered_date'];
        $sibling = $hdata['sibling'];
        $f_occupation = $hdata['f_occupation'];
        $m_occupation = $hdata['m_occupation'];
        $about_myself = $hdata['about_myself'];
        //  $admin_notes = $hdata['admin_notes'];
        $asset_details = $hdata['asset_details'];
        $pp_occupation = $hdata['pp_occupation'];
        $pp_education = $hdata['pp_education'];
        $pp_work_location = $hdata['pp_work_location'];
        $pp_salary = $hdata['pp_salary'];
        $pp_asset_details = $hdata['pp_asset_details'];
        $pp_expectation = $hdata['pp_expectation'];

        $sql = "UPDATE `$tbl_matrimony` SET `name`='$name', `father_name`='$father_name',`mother_name`='$mother_name',`gender`='$gender',`age`='$age', `birth_date`='$birth_date',`birth_time`='$birth_time',`birth_place`='$birth_place',`raasi`='$raasi',`star`='$star',`laknam`='$laknam',`padham`='$padham',`raaghu_kaedhu`='$raaghu_kaedhu',`sevvai`='$sevvai',`kulam`='$kulam',`temple`='$temple',`height`='$height',`weight`='$weight',`colour`='$colour',`qualification`='$qualification',`occupation`='$occupation', `blood_group`='$blood_group',`mobile_no`='$mobile_no',`address`='$address',`country`='$country',`email`='$email',`income`='$income',`sibling`='$sibling',`marital_status`='$marital_status',`m_kulam`='$m_kulam',`mm_kulam`='$mm_kulam',`pm_kulam`='$pm_kulam',`education_details`='$education_details',`college_details`='$college_details',`occupation_details`='$occupation_details',`f_occupation`='$f_occupation',`m_occupation`='$m_occupation',`about_myself`='$about_myself',`asset_details`='$asset_details', `pp_occupation`='$pp_occupation', `pp_education`='$pp_education', `pp_work_location`='$pp_work_location', `pp_salary`='$pp_salary', `pp_asset_details`='$pp_asset_details', `pp_expectation`='$pp_expectation', `contact_person`='$contact_person', `relationship`='$relationship'  WHERE `id` ='$id'";

        return mysqli_query($con, $sql);
    }
    return false;
}

function update_matri_field($data, $cond) {
    global $con, $tbl_matrimony;
    $sql = "update $tbl_matrimony set ";
    $prev = "";

    foreach ($data as $k => $v) {

        $sql .= $prev . " $k = $v";
        $prev = ",";
    }

    $sql .= " where ";
    $prev = "";
    foreach ($cond as $k => $v) {

        $sql .= $prev . " $k = $v";
        $prev = " AND ";
    }

    //echo $sql; die;
    return mysqli_query($con, $sql);
}

function update_family_field($id, $data, $cond = array()) {
    global $con, $tbl_family;

    $cond['id'] = $id;
    $sql = "update $tbl_family set ";
    $prev = "";

    foreach ($data as $k => $v) {

        $sql .= $prev . " $k = $v";
        $prev = ",";
    }

    $sql .= " where ";
    $prev = " ";
    foreach ($cond as $k => $v) {

        $sql .= $prev . " $k = $v";
        $prev = " AND ";
    }

    //echo $sql; die;
    return mysqli_query($con, $sql);
}

function get_horoscope($id) {
    global $con, $tbl_matrimony;
    $result = mysqli_query($con, "SELECT * FROM `$tbl_matrimony` WHERE `id`='$id'");
    $row = mysqli_fetch_array($result);
    return $row;
}

function get_attachments($id) {
    global $con, $tbl_attachments;
    $result1 = mysqli_query($con, "SELECT * FROM `$tbl_attachments` WHERE `m_id`='$id' AND `type`='photo'");
    $row1 = mysqli_fetch_array($result1);
    return $row1;
}

function display_graham($k_no, $kattam) {
    global $graham_short;

    if (isset($kattam[$k_no])) {

        foreach ($kattam[$k_no] as $k => $v) {

            echo "<span class='graham_sh'>" . $graham_short[$v] . "</span>";
        }
    }
}

/*
 *  Returns kattam details of a horoscope from kattam table.
 * parameter:  matrimony id of the horoscope
 * returns:  rasi and amsam kattam as array
 */

function get_kattam($m_id) {
    global $con, $tbl_kattam;

    $sql = "SELECT * FROM `$tbl_kattam` WHERE `m_id`=$m_id";
//echo $sql;
    $result2 = mysqli_query($con, $sql);
    if (!$result2)
        return false;
    $row2 = mysqli_fetch_array($result2);
    if (!$row2)
        return false;
    $rasi[$row2['r_sevvai']][] = "sevvai";
    $rasi[$row2['r_sooriyan']][] = "sooriyan";
    $rasi[$row2['r_sukkiran']][] = "sukkiran";
    $rasi[$row2['r_chandran']][] = "chandran";
    $rasi[$row2['r_budhan']][] = "budhan";
    $rasi[$row2['r_guru']][] = "guru";
    $rasi[$row2['r_laknam']][] = "laknam";
    $rasi[$row2['r_raaghu']][] = "raaghu";
    $rasi[$row2['r_kaedhu']][] = "kaedhu";
    $rasi[$row2['r_sani']][] = "sani";

    $amsam[$row2['a_sevvai']][] = "sevvai";
    $amsam[$row2['a_sooriyan']][] = "sooriyan";
    $amsam[$row2['a_sukkiran']][] = "sukkiran";
    $amsam[$row2['a_chandran']][] = "chandran";
    $amsam[$row2['a_budhan']][] = "budhan";
    $amsam[$row2['a_guru']][] = "guru";
    $amsam[$row2['a_laknam']][] = "laknam";
    $amsam[$row2['a_raaghu']][] = "raaghu";
    $amsam[$row2['a_kaedhu']][] = "kaedhu";
    $amsam[$row2['a_sani']][] = "sani";

    $kattam['rasi'] = $rasi;
    $kattam['amsam'] = $amsam;
    return $kattam;
}

function update_family($id, $data) {
    global $con, $tbl_family;

    if (count($data) > 0) {
        // Debug: Check what w_dob data looks like
        if (isset($data['w_dob'])) {
            error_log("w_dob data: " . print_r($data['w_dob'], true));
        }
        
        // Debug: Check for individual date components
        if (isset($data['w_dob']['year'])) {
            error_log("w_dob year: " . $data['w_dob']['year']);
        }
        if (isset($data['w_dob']['month'])) {
            error_log("w_dob month: " . $data['w_dob']['month']);
        }
        if (isset($data['w_dob']['date'])) {
            error_log("w_dob date: " . $data['w_dob']['date']);
        }
        
        $name = sql_null_or_str($data['name']);
        $father_name = sql_null_or_str($data['father_name']);
        $mother_name = sql_null_or_str($data['mother_name']);
        
        // Handle date fields - check if they are arrays or already formatted strings
        if (isset($data['dob']) && is_array($data['dob']) && isset($data['dob']['year']) && isset($data['dob']['month']) && isset($data['dob']['date']) && 
            !empty($data['dob']['year']) && !empty($data['dob']['month']) && !empty($data['dob']['date'])) {
            $dob = $data['dob']['year'] . "-" . $data['dob']['month'] . "-" . $data['dob']['date'];
        } elseif (isset($data['dob']) && !empty($data['dob']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['dob'])) {
            $dob = $data['dob'];
        } else {
            $dob = 'NULL';
        }
        
        // $age = $data['age'];
        $blood_group = sql_null_or_int($data['blood_group']);
        $qualification = sql_null_or_int($data['qualification']);
        $education_details = sql_null_or_str($data['education_details']);
        $occupation = sql_null_or_int($data['occupation']);
        $occupation_details = sql_null_or_str($data['occupation_details']);
        $w_education_details = sql_null_or_str($data['w_education_details'] ?? '');
        $w_occupation_details = sql_null_or_str($data['w_occupation_details'] ?? '');
        $email = sql_null_or_str($data['email']);
        $pudavai = sql_null_or_str($data['pudavai']);
        $mobile_no = sql_null_or_str($data['mobile_no']);
        $permanent_address = sql_null_or_str($data['permanent_address']);
        $current_address = sql_null_or_str($data['current_address']);
        $w_name = sql_null_or_str($data['w_name'] ?? '');
        
        // Handle wife's date of birth - check for separate date fields
        if (isset($data['w_dob']) && is_array($data['w_dob']) && isset($data['w_dob']['year']) && isset($data['w_dob']['month']) && isset($data['w_dob']['date']) && 
            !empty($data['w_dob']['year']) && !empty($data['w_dob']['month']) && !empty($data['w_dob']['date'])) {
            $w_dob = $data['w_dob']['year'] . "-" . $data['w_dob']['month'] . "-" . $data['w_dob']['date'];
        } elseif (isset($data['w_dob']) && is_string($data['w_dob']) && !empty($data['w_dob'])) {
            // Check if it's a valid date format (YYYY-MM-DD)
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['w_dob'])) {
                $w_dob = $data['w_dob'];
            } else {
                // If it's just a year or invalid format, set to NULL
                $w_dob = 'NULL';
            }
        } else {
            // Check for individual date components that might be sent as separate form fields
            $w_dob_year = isset($data['w_dob']['year']) ? $data['w_dob']['year'] : '';
            $w_dob_month = isset($data['w_dob']['month']) ? $data['w_dob']['month'] : '';
            $w_dob_date = isset($data['w_dob']['date']) ? $data['w_dob']['date'] : '';
            
            if (!empty($w_dob_year) && !empty($w_dob_month) && !empty($w_dob_date)) {
                $w_dob = $w_dob_year . "-" . $w_dob_month . "-" . $w_dob_date;
            } else {
                // If no valid date data, set to NULL
                $w_dob = 'NULL';
            }
        }
        
        $w_blood_group = sql_null_or_int($data['w_blood_group'] ?? '');
        $w_qualification = sql_null_or_int($data['w_qualification'] ?? '');
        $w_occupation = sql_null_or_int($data['w_occupation'] ?? '');
        $w_kootam = sql_null_or_str($data['w_kootam'] ?? '');
        $w_temple = sql_null_or_str($data['w_temple'] ?? '');
        $w_email = sql_null_or_str($data['w_email'] ?? '');
        $village = sql_null_or_str($data['village']);
        $taluk = sql_null_or_str($data['taluk']);
        $district = sql_null_or_str($data['district']);
        $country = sql_null_or_str($data['country']);
        $c_village = sql_null_or_str($data['c_village']);
        $c_taluk = sql_null_or_str($data['c_taluk']);
        $c_district = sql_null_or_str($data['c_district']);
        $c_country = sql_null_or_str($data['c_country']);
        $kattalai = sql_null_or_int($data['kattalai']);
        $k_village = sql_null_or_str($data['k_village']);
        $pincode = sql_null_or_str($data['pincode']);
        $c_pincode = sql_null_or_str($data['c_pincode']);
        $state = sql_null_or_str($data['state']);
        $c_state = sql_null_or_str($data['c_state']);
        $ic = sql_null_or_str($data['ic']);
        $same_as_permanent = isset($data['same_as_permanent']) ? sql_null_or_int($data['same_as_permanent']) : 0;
        //$_2000_bk_no = $data['_2000_bk_no'];
        //$_2000_rc_no = $data['_2000_rc_no'];
        $remarks = sql_null_or_str($data['remarks']);
        // $member_name = $data['member_name'];

        $sql = "UPDATE `$tbl_family` SET `name`=$name,`father_name`=$father_name,`mother_name`=$mother_name,`dob`=" . ($dob === 'NULL' ? 'NULL' : "'$dob'") . ",`blood_group`=$blood_group,`qualification`=$qualification,`occupation`=$occupation,`email`=$email,`pudavai`=$pudavai,`mobile_no`=$mobile_no,`permanent_address`=$permanent_address,`current_address`=$current_address,`country`=$country,`village`=$village,`district`=$district,`taluk`=$taluk,`c_village`=$c_village,`c_taluk`=$c_taluk,`c_district`=$c_district,`c_country`=$c_country,`kattalai`=$kattalai,`k_village`=$k_village,`w_name`=$w_name,`w_dob`=" . ($w_dob === 'NULL' ? 'NULL' : "'$w_dob'") . ",`w_blood_group`=$w_blood_group,`w_qualification`=$w_qualification,`w_occupation`=$w_occupation,`w_kootam`=$w_kootam,`w_temple`=$w_temple,`w_email`=$w_email,`ic`=$ic,`remarks`=$remarks,`education_details`=$education_details,`occupation_details`=$occupation_details,`w_education_details`=$w_education_details,`w_occupation_details`=$w_occupation_details,`pincode`=$pincode,`state`=$state,`c_pincode`=$c_pincode,`c_state`=$c_state,`same_as_permanent`=$same_as_permanent WHERE `id` ='$id'";

        //echo $sql;
        return mysqli_query($con, $sql);
    }
    return false;
}

function update_child($id, $cdata) {
    global $con, $tbl_child;

    if (count($cdata) > 0) {
        $c_name = $cdata['c_name'];
        $c_dob = $cdata['c_dob'];
        $c_gender = $cdata['c_gender'];
        $c_blood_group = $cdata['c_blood_group'];
        $c_marital_status = $cdata['c_marital_status'];
        $c_qualification = $cdata['c_qualification'];
        $c_occupation = $cdata['c_occupation'];
        $c_mobile_no = $cdata['c_mobile_no'];
        $c_email = $cdata['c_email'];
        $c_education_details = $cdata['c_education_details'];
        $c_occupation_details = $cdata['c_occupation_details'];

        $sql = "UPDATE `$tbl_child` SET `c_name`='$c_name',`c_dob`='$c_dob',`c_gender`='$c_gender',`c_blood_group`='$c_blood_group',`c_marital_status`='$c_marital_status',`c_qualification`='$c_qualification',`c_occupation`='$c_occupation',`c_mobile_no`='$c_mobile_no',`c_email`='$c_email',`c_occupation_details`='$c_occupation_details',`c_education_details`='$c_education_details' WHERE `id` ='$id'";

        return mysqli_query($con, $sql);
    }
    return false;
}

function update_donation($id, $donation) {
    global $con, $tbl_donation;

    if (count($donation) > 0) {
        $name = $donation['name'];
        $reg_code = $donation['reg_code'];
        $member_id = $donation['member_id'];
        $type = $donation['type'];
        $amount = $donation['amount'];
        $paid_on = $donation['paid_on'];
        $receipt_no = $donation['receipt_no'];
        $sub_year = $donation['sub_year'];
        $last_modified_date = date('Y-m-d H:i:s');
        $sql = "UPDATE `$tbl_donation` SET `name`='$name',`type`='$type',`amount`='$amount',`paid_on`='$paid_on',`receipt_no`='$receipt_no',`sub_year`='$sub_year',`member_id`='$reg_code',`reg_code`='$member_id', `last_modified_date`='$last_modified_date' WHERE `id` ='$id'";

        return mysqli_query($con, $sql);
    }
    return false;
}

function add_event($event) {
    global $con, $tbl_event;
    if (count($event) && $event['event_name'] != '') {
        $event_name = $event['event_name'];
        $date = $event['date'];
        $location = $event['location'];
        $no_of_books = $event['no_of_books'];
        $amount = $event['amount'];

        $sql = "INSERT INTO `$tbl_event`(`event_name`, `date`, `location`,`no_of_books`,`amount`) VALUES ('$event_name', '$date', '$location', 'no_of_books','amount')";
        return mysqli_query($con, $sql);
    }
}

function add_book($book) {
    global $con, $tbl_book;

    if (count($book) && $book['book_no'] != '') {
        $book_no = $book['book_no'];
        $book_sno = $book['rec_start_no'];
        $book_eno = $book['rec_end_no'];
        $denom = $book['denom'];
        $issued_to = $book['issued_to'];
        $collected_amount = $book['collected_amount'];

        $sql = "INSERT INTO `$tbl_book`(`book_no`, `rec_start_no`, `rec_end_no`, `denom`, `collected_amount`, `issued_to`) VALUES ('$book_no', '$book_sno', '$book_eno', '$denom', '$collected_amount', '$issued_to')";
        return mysqli_query($con, $sql);
    }
}

function list_event($event_id = '') {
    global $con, $tbl_event;

    if ($event_id == '') {
        $sql = "SELECT * from  $tbl_event ";
    } else {
        $sql = "SELECT * from  $tbl_event where event_id = $event_id";
    }

    $result = mysqli_query($con, $sql);
    return $result;
}

function list_book($event_id = '') {
    global $con, $tbl_book;

    if ($event_id == '') {
        $sql = "SELECT * from  $tbl_book ";
    } else {
        $sql = "SELECT * from  $tbl_book where event_id  = $event_id ";
    }

    $result = mysqli_query($con, $sql);
    return $result;
}

function list_receipt($book_id = '') {
    global $con, $tbl_receipt;

    if ($book_id == '') {
        $sql = "SELECT * from  $tbl_receipt ";
    } else {
        $sql = "SELECT * from  $tbl_receipt where book_id = $book_id";
    }

    $result = mysqli_query($con, $sql);
    return $result;
}

function get_event($event_id) {
    global $con, $tbl_event;
    $result = mysqli_query($con, "SELECT * FROM `$tbl_event` WHERE `event_id`='$event_id'");
    $row = mysqli_fetch_array($result);
    return $row;
}

function get_book($event_id) {
    global $con, $tbl_book;
    $result = mysqli_query($con, "SELECT * FROM `$tbl_book` WHERE `event_id`='$event_id'");
    $row1 = mysqli_fetch_array($result);
    return $row1;
}

function get_receipt($book_id) {
    global $con, $tbl_receipt;
    $result = mysqli_query($con, "SELECT * FROM `$tbl_receipt` WHERE `book_id`='$book_id'");
    $row = mysqli_fetch_array($result);
    return $row;
}

function update_event($event_id, $edata) {

    global $con, $tbl_event;
    if (count($edata) > 0) {
        $event_name = $edata['event_name'];
        $date = $edata['date'];
        $location = $edata['location'];
        //$no_of_books=$_POST['no_of_books'];
        //$amount=$_POST['amount'];

        $sql = "UPDATE `$tbl_event` SET `event_name` = '$event_name',`date`= '$date', `location` = '$location' WHERE `event_id`='$event_id'";
        return mysqli_query($con, $sql);
    }
}

function update_book($event_id, $bdata) {

    global $con, $tbl_book;
    if (count($bdata) > 0) {
        $book_no = $bdata['book_no'];
        $book_sno = $bdata['book_sno'];
        $book_eno = $bdata['book_eno'];
        $denom = $bdata['denom'];
        $collected_amount = $bdata['collected_amount'];
        $issued_to = $bdata['issued_to'];

        $sql = "UPDATE `$tbl_book` SET `book_no`='$book_no',`book_sno`='$book_sno',`book_eno`='$book_eno',`denom`='$denom', `collected_amount`='$collected_amount', `issued_to`='$issued_to' WHERE `event_id`='$event_id'";
        return mysqli_query($con, $sql);
    }
}

function update_receipt($id, $rdata) {

    global $con, $tbl_receipt;
    if (count($rdata) > 0) {
        $rec_no = $rdata['rec_no'];
        $name = $rdata['name'];
        $mobile_no = $rdata['mobile_no'];
        $date = $rdata['date'];
        $amount = $rdata['amount'];

        $sql = "UPDATE `$tbl_receipt` SET `name`='$name',`rec_no`='$rec_no',`mobile_no`= '$mobile_no',`date`='$date', `amount`='$amount' WHERE `id` ='$id'";
        return mysqli_query($con, $sql);
    }
}

function get_horo_by_member($member_id) {

    $cond = " AND ref_id = $member_id ";
    $res = get_horo_list($cond);
    $horo = array();
    while ($row = mysqli_fetch_array($res)) {

        $horo[$row['id']] = $row;
    }

    return $horo;
}

function get_subsfee_by_member($member_id) {

    $cond = " AND ref_id = $member_id ";
    $res = get_subsfee_list($cond);
    $subsfee = array();
    while ($row = mysqli_fetch_array($res)) {

        $subsfee[$row['id']] = $row;
    }

    return $subsfee;
}

function get_subsfee_list($where = '') {
    global $con, $tbl_donation;

    $where = " type= subsfee " . $where;
    $sql = "SELECT * from  $tbl_donation  WHERE $where ";
    //
    $result = mysqli_query($con, $sql);

    //while ($row = mysqli_fetch_array($result)) {
    //  $horo['name']= $row['name'];
    //}

    return $result;
}

function get_horo_list($where = '') {
    global $con, $tbl_matrimony;

    $where = " deleted = 0  " . $where;
    $sql = "SELECT * from  $tbl_matrimony  WHERE $where ";
//echo $sql;
    $result = mysqli_query($con, $sql);

    //while ($row = mysqli_fetch_array($result)) {
    //  $horo['name']= $row['name'];
    //}
    return $result;
}

function get_horo_photo_url($photo = '') {
    
}

/* function get_horo_list($id = '', $cond = array()) {
  global $con, $tbl_matrimony;

  if ($id == '') {
  $sql = "SELECT * from  $tbl_matrimony  WHERE `deleted`=0";
  } else {
  $sql = "SELECT * from  $tbl_matrimony where id = $id";
  }

  $result = mysqli_query($con, $sql);

  while ($row = mysqli_fetch_array($result)) {
  $horo ['name'] = ($row['name']);
  $horo ['qualification'] = ($row['qualification']);
  $horo ['mobile_no'] = ($row['mobile_no']);
  $horo['w_name'] = ($row['w_name']);
  $horo ['father_name'] = ($row['father_name']);
  $horo ['mother_name'] = ($row['mother_name']);
  $horo ['permanent_address'] = ($row['permanent_address']);

  $horo_list[$row['id']] = $horo;
  }
  return $horo_list;
  } */

function get_labels_by_type($type_slug) {
    global $con, $tbl_labels;
    $labels = array();
    $sql = "select * from $tbl_labels where type=0 and slug='$type_slug'";
    //echo $sql;
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($res);

    if ($row) {
        $labels = get_labels($row['id']);
    }

    return $labels;
}

function get_labels($t = '') {
    global $con, $tbl_labels;

    $labels = array();
    $types = array();  // label types alone.. which has type field as 0 ..

    $where = " where type != 0 ";

    if ($t != '')
        $where .= " and type = $t ";

    $sql = "SELECT * from  $tbl_labels $where order by type, $tbl_labels.order ";
//echo $sql;
    $tps = get_types();

    // var_dump($tps);

    $types = $tps;

    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_array($result)) {
        //if($row['type'] ==0)
        //     $types[$row['id']] = $row;
        // else
        $labels[$row['id']] = $row;
    }

    foreach ($labels as $k => $v) {
        $labels[$k]['type_name'] = $types[$v['type']]['display_name'];
        $labels[$k]['type_slug'] = $types[$v['type']]['slug'];
    }
    return $labels;
}

function get_users($id = '') {
    global $con, $tbl_users;

    if ($id == '') {
        $sql = "SELECT * from  $tbl_users";
    } else {
        $sql = "SELECT * from  $tbl_users where id = $id";
    }

    $result = mysqli_query($con, $sql);
    return $result;
}

function get_user($id) {
    global $con, $tbl_users;
    $result = mysqli_query($con, "SELECT * FROM `$tbl_users` WHERE `id`='$id'");
    $row = mysqli_fetch_array($result);
    return $row;
}

function get_label($id) {
    global $con, $tbl_labels;
    $result = mysqli_query($con, "SELECT * FROM `$tbl_labels` WHERE `id`='$id'");
    $row = mysqli_fetch_array($result);
    return $row;
}

function get_location($loc = 'village') {

    return get_autosuggest($loc);
}

function get_autosuggest($field) {
    global $con, $tbl_family;
    $sql = "SELECT distinct $field FROM `$tbl_family` ";

    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_array($result)) {

        $values[] = $row[$field];
    }


    return $values;
}

function update_users($id, $udata) {

    global $con, $tbl_users;
    if (count($udata) > 0) {
        $email = $udata['email'];
        $username = $udata['username'];
        $role = $udata['role'];
        $profile_id = $udata['profile_id'];
        //$creation_date = $udata['creation_date'];

        $sql = "UPDATE `$tbl_users` SET `email` = '$email',`username`= '$username',  `role` = '$role',`profile_id`= '$profile_id'  WHERE `id` = '$id'";
        return mysqli_query($con, $sql);
    }
}

function update_labels($id, $ldata) {

    global $con, $tbl_labels;
    if (count($ldata) > 0) {
        $display_name = $ldata['display_name'];
        $slug = $ldata['slug'];
        $type = $ldata['type'];
        $category = $ldata['category'];
        $parent_id = $ldata['parent_id'];
        $order = $ldata['order'];

        $sql = "UPDATE `$tbl_labels` SET `display_name` = '$display_name',`slug`= '$slug',  `type` = '$type',`category`= '$category', `parent_id`='$parent_id',`order` = '$order'  WHERE `id` = '$id'";
        return mysqli_query($con, $sql);
    }
}

function add_user($udata) {
    global $con, $tbl_users;

    if (count($udata) && $udata['username'] != '') {
        $email = isset($udata['email']) ? $udata['email'] : '';
        $username = $udata['username'];
        $password = isset($udata['password']) ? $udata['password'] : '';
        $role = isset($udata['role']) ? $udata['role'] : '';
        $profile_id = isset($udata['profile_id']) ? $udata['profile_id'] : '';
        //$creation_date = $udata['creation_date'];
        //$creation_date = "now()";
        $created_by = isset($udata['created_by']) ? $udata['created_by'] : '';

        $sql = "INSERT INTO `$tbl_users`(`username`, `email`, `password`, `role`, `profile_id`, `creation_date`, `created_by`, `u_image`, `mobile_no` ) VALUES ('$username', '$email', '$password', '$role', '$profile_id', now(), '$created_by', '', '')";
//echo $sql;die;
        return mysqli_query($con, $sql);
    }
}

function add_label($ldata) {
    global $con, $tbl_labels;

    if (count($ldata) && $ldata['display_name'] != '') {
        $display_name = $ldata['display_name'];
        $slug = $ldata['slug'];
        $type = $ldata['type'];
        $category = $ldata['category'];
        $parent_id = $ldata['parent_id'];
        $order = $ldata['order'];

        $sql = "INSERT INTO `$tbl_labels`(`display_name`, `slug`, `type`, `category`, `parent_id`, `order` ) VALUES ('$display_name', '$slug', '$type', '$category', '$parent_id', '$order')";

        return mysqli_query($con, $sql);
    }
}

function display_kulam_list($name = "kulam", $default = '') {

    //global $kulam;
    $kulam = get_labels_by_type('kootam');
    ?>
    <select  name="<?php echo $name ?>"  class="form-control"  id="<?php echo $name ?>">
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($kulam as $k => $v) {

            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>

    </select>

    <?php
}

function display_closing_reasons($name = "close_reason_code", $default = '') {
    //global $blood_group;

    $close_reason_code = get_labels_by_type('mat_close');
    ?>
    <select name="<?php echo $name ?>"  class="form-control" id="close_reason_code" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($close_reason_code as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>
    </select>

    <?php
}

function display_blood_group_list($name = "blood_group", $default = '') {
    //global $blood_group;

    $blood_group = get_labels_by_type('blood_group');
    ?>
    <select name="<?php echo $name ?>"  class="form-control" id="blood_group" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($blood_group as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>

    </select>

    <?php
}

function display_occupation($name = "occupation", $default = '') {

    //global $occupation;
    $occ = get_labels_by_type('occupation');
    //var_dump($occ);
    ?>
    <select name="<?php echo $name ?>"  class="form-control" id="<?php echo $name ?>" >
        <option value="">-select- </option>
        <?php
        $sel = '';
        foreach ($occ as $k => $v) {
            ;
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function get_types() {
    global $con, $tbl_labels;

    $types = array();
    $sql = "select * from $tbl_labels where type=0 ";

    $res = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_array($res)) {
        $types[$row['id']] = $row;
    }

    return $types;
}

function get_type($id) {
    global $con, $tbl_labels;

    $sql = "select * from $tbl_labels where type=0 and id=$id";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($res);
    return $row;
}

function display_type($name = "type", $default = '') {

    $types = get_types();
    ?>
    <select name="<?php echo $name ?>"  class="form-control" id="<?php echo $name ?>" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($types as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>

    </select>

    <?php
}

function display_qualification($name = "qualification", $default = '') {
    //global $qualification;

    $edu = get_labels_by_type('education');
    ?>
    <select name="<?php echo $name ?>"  class="form-control" id="<?php echo $name ?>" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($edu as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>

    </select>

    <?php
}

function display_kattalai($name = "kattalai", $default = '') {
    //global $kattalai;

    $katt = get_labels_by_type('kattalai');
    // var_dump($katt);
    ?>
    <select name="<?php echo $name ?>"  class="form-control" id="<?php echo $name ?>" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($katt as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_kattalai_village($name = "k_village", $default = '') {
    //global $kattalai;

    $kvill = get_labels_by_type('kattalai_village');
    // var_dump($katt);
    ?>
    <select name="<?php echo $name ?>"  class="form-control" id="<?php echo $name ?>" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($kvill as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_age($name = "age", $defuault = '') {
    $age = 18;
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value='' >-Select-</option>
        <?php
        $sel = '';
        for ($i = $age; $i <= 59; $i++) {
            ?>
            <option value="<?php echo $i ?>"  <?php echo $sel ?>><?php echo $i ?></option>

            <?php
        }
        ?>
    </select>
    <?php
}

function display_height($name = "height", $default = '') {

    $height = array('1' => "4ft - 121 cm",
        '2' => "4ft 1in - 124cm",
        '3' => "4ft 2in - 127cm ",
        '4' => "4ft 3in - 129cm",
        '5' => "4ft 4in - 132cm",
        '6' => "4ft 5in - 134cm",
        '7' => "4ft 6in - 137cm",
        '8' => "4ft 7in - 139cm",
        '9' => "4ft 8in - 142cm",
        '10' => "4ft 9in - 144cm",
        '11' => "4ft 10in - 147cm",
        '12' => "4ft 11in - 149cm",
        '13' => "5ft - 152cm",
        '14' => "5ft 1in - 154cm",
        '15' => "5ft 2in - 157cm",
        '16' => "5ft 3in - 160cm",
        '17' => "5ft 4in - 162cm",
        '18' => "5ft 5in - 165cm",
        '19' => "5ft 6in - 167cm",
        '20' => "5ft 7in - 170cm",
        '21' => "5ft 8in - 172cm",
        '22' => "5ft 9in - 175cm",
        '23' => "5ft 10in - 177cm",
        '24' => "5ft 11in - 180cm",
        '25' => "6ft - 182cm",
        '26' => "6ft 1in - 185cm",
        '27' => "6ft 2in - 187cm",
        '28' => "6ft 3in - 190cm",
        '29' => "6ft 4in - 193cm",
        '30' => "6ft 5in - 195cm",
        '31' => "6ft 6in - 198cm",
        '32' => "6ft 7in - 200cm",
        '33' => "6ft 8in - 203cm",
        '34' => "6ft 9in - 205cm",
        '35' => "6ft 10in - 208cm",
        '36' => "6ft 11in - 210cm",
        '37' => "7ft - 213cm"
    );
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value="">-select- </option>
        <?php
        $sel = '';
        foreach ($height as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_height_horo($name = "height", $default = '') {

    global $height_horo;
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value="">-select- </option>
        <?php
        $sel = '';
        foreach ($height_horo as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_weight($name = "weight", $default = '') {
    $weight = 40;
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value='' >-Select-</option>
        <?php
        $sel = '';

        for ($i = $weight; $i <= 140; $i++) {
            if ($i == $default)
                $sel = " selected ";
            else
                $sel = '';
            ?>
            <option value="<?php echo $i ?>"  <?php echo $sel ?>><?php echo $i . " kg" ?></option>

            <?php
        }
        ?>
    </select>
    <?php
}

function display_gender($name = "gender", $default = '') {

    $gender = array('male' => "Male",
        'female' => "Female",
    );
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value="">-select- </option>
        <?php
        $sel = '';
        foreach ($gender as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_donation_type($name = "donation", $default = '') {

    $type = array('entry' => "Entry Fees",
        'subscription' => "Subsription Fees",
        'donation' => "Donation",
    );
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value="">-select- </option>
        <?php
        $sel = '';
        foreach ($type as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_marital_status($name = "marital_status", $default = 'unmarried') {
    global $marital_status;
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($marital_status as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_workplace($name = "country", $default = '') {

    //  global $workplace;
    $workplace = get_labels_by_type('workplace');
    // var_dump($workplace);
    ?>
    <select name="<?php echo $name ?>"  class="form-control"  id="<?php echo $name ?>">
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($workplace as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_colour($name = "colour", $default = '') {
    //global $colour;
    $colour = get_labels_by_type('Colour');
    //var_dump($colour);
    ?>
    <select name="<?php echo $name ?>"  class="form-control"  id="<?php echo $name ?>">
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($colour as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v['display_name'] ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_date($name = "birth_date", $default = "") {

    //here $default is a date value.. we extract the day part here
    $date = date("m", strtotime($default));

    echo "<select name='$name'  class='form-control' >";
    echo "<option value='' >-Day-</option>";

    $sel = '';
    for ($i = 1; $i <= 31; $i++) {
        $i1 = sprintf("%02d", $i);

        if ($i1 == $date)
            $sel = "selected";
        else
            $sel = '  ';


        echo "<option value='$i1'  $sel >$i1</option>";
    }
    echo " </select>";
}

function display_month($name = "birth_date", $default = '') {

    global $month;

    if ($default != '')
        $def_month = date('m', strtotime($default));
    else
        $def_month = '';
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value="">-Month- </option>
        <?php
        $sel = '';
        foreach ($month as $k => $v) {
            if ($k == $def_month)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
            <?php
        }
        ?>

    </select>

    <?php
}

function display_year($name = "birth_date", $default = '', $type = "") {
    $cur_year = date('Y');
    $def_year = date('Y', strtotime($default));
    if ($type == 'horo') {
        $end_year = $cur_year - 15;
        $start_year = $end_year - 25;
    } else {
        $end_year = $cur_year;
        $start_year = $end_year - 100;
    }
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value='' >-Year-</option>
        <?php
        $sel = '';
        for ($i = $end_year; $i >= $start_year; $i--) {
            if ($i == $def_year)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>

            <option value="<?php echo $i ?>"  <?php echo $sel ?>><?php echo $i ?></option>

            <?php
        }
        ?>

    </select>
    <?php
}

function display_raasi($name = "raasi", $default = '', $style = '') {

    global $raasi;
    ?>
    <select name="<?php echo $name ?>"  class="form-control" style="<?php echo $style ?>" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($raasi as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
            <?php
        }
        ?>

    </select>


    <?php
}

function display_star_checkbox($name = "star", $default = array()) {
    global $stars;
    ?>
    <style>
        <?php echo '.' . $name ?> label{
            width:92%;
            margin:0px;
            padding:5px;
            font-weight:normal;
        }
        <?php echo '.' . $name ?> input[type="checkbox"]:checked + label {
            background: #c0cefe ;
        }

        <?php echo '.' . $name ?> li{

            padding:0px;
            padding-left:5px;
        }
    </style>
    <ul class="list-group checked-list-box <?php echo $name ?>">
        <?php
        foreach ($stars as $k => $v) {
            ?>
            <li class="list-group-item">
                <input type="checkbox" name="<?php echo $name . '[' . $k . ']' ?>"  <?php if (isset($default[$k])) echo "checked" ?>> 
                <label >   <?php echo $v ?> </label>
            </li>    

            <?php
        }
        ?>

    </ul>

    <?php
}

function display_marital_checkbox($name = "marital", $default = array('unmarried' => '0')) {
    global $marital_status;
    ?>
    <style>
        <?php echo '.' . $name ?> label{
            width:92%;
            margin:0px;
            padding:5px;
            font-weight:normal;
        }
        <?php echo '.' . $name ?> input[type="checkbox"]:checked + label {
            background: #c0cefe ;
        }

        <?php echo '.' . $name ?> li{

            padding:0px;
            padding-left:5px;
        }
    </style>
    <ul class="list-group checked-list-box <?php echo $name ?>">
        <?php
        foreach ($marital_status as $k => $v) {
            ?>
            <li class="list-group-item">
                <input type="checkbox" name="<?php echo $name . '[' . $k . ']' ?>"  <?php if (isset($default[$k])) echo " checked " ?>> 
                <label >   <?php echo $v ?> </label>
            </li>    

            <?php
        }
        ?>

    </ul>

    <?php
}

function display_star($name = "star", $default = '', $style = '') {
    global $stars;
    ?>

    <select name="<?php echo $name ?>"  class="form-control" >
        <option value="">-Select- </option>
        <?php
        $sel = '';
        foreach ($stars as $k => $v) {
            if ($k == $default)
                $sel = ' selected ';
            else
                $sel = '  ';
            ?>
            <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

function display_padham($name = "padham", $default = '') {
    $padham = 1;
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value='' >-Select-</option>
        <?php
        $sel = '';
        for ($i = $padham; $i <= 4; $i++) {

            if ($i == $default)
                $sel = " selected ";
            else
                $sel = '';
            ?>
            <option value="<?php echo $i ?>"  <?php echo $sel ?>><?php echo $i ?></option>

            <?php
        }
        ?>
    </select>
    <?php
}

function display_hour($name = "birth_time", $default = '') {
    $hour = 0;
    $hr = date("H", strtotime($default));
    ?>
    <select name="<?php echo $name ?>"  class="form-control" >
        <option value='' >-hh-</option>
        <?php
        $sel = '';
        for ($i = $hour; $i < 24; $i++) {
            $i1 = sprintf("%02d", $i);

            if ($i1 == $hr)
                $sel = " selected ";
            else
                $sel = '';
            ?>

            <option value="<?php echo $i1 ?>"  <?php echo $sel ?>><?php echo $i1 ?></option>

            <?php
        }
        echo "</select>";
    }

    function display_minute($name = "birth_time", $default = '') {
        $minute = 00;
        $min = date("i", strtotime($default));
        ?>
        <select name="<?php echo $name ?>"  class="form-control" >
            <option value='' >-mm-</option>
            <?php
            $sel = '';
            for ($i = $minute; $i < 60; $i++) {
                $i1 = sprintf("%02d", $i);

                if ($i1 == $min)
                    $sel = " selected ";
                else
                    $sel = '';
                ?>

                <option value="<?php echo $i1 ?>"  <?php echo $sel ?>><?php echo $i1 ?></option>

                <?php
            }
            echo "</select>";
        }

        function display_time($name = "birth_time", $default = '') {
            $time = array('am' => "AM",
                'pm' => "PM",
            );
            ?>
            <select name="<?php echo $name ?>"  class="form-control" >
                <option value="am/pm">-AM / PM- </option>
                <?php
                $sel = '';
                foreach ($time as $k => $v) {
                    if ($k == $default)
                        $sel = ' selected ';
                    else
                        $sel = '  ';
                    ?>
                    <option value="<?php echo $k ?>"   <?php echo $sel ?> ><?php echo $v ?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }

        function display_raghu_kedhu_checkbox($name = 'raaghu_kaedhu', $default = '', $style = '') {
            ?>
            <input type="checkbox" name="<?php echo $name ?>" value="" <?php echo ($default > 0) ? " checked " : "" ?> style="<?php echo $style ?>"> 
            <?php
        }

        function display_raghu_kedhu_dropdown($name = 'raaghu_kaedhu', $default = '') {
            ?>
            <select name="<?php echo $name ?>"  class="form-control" >
                <option value="">-Select- </option>
                <option value="Yes"  <?php echo ($default == "Yes") ? " selected " : '' ?> >Yes </option>
                <option value="No"  <?php echo ($default == "No") ? " selected " : '' ?>  >No</option>
            </select>
            <?php
        }

        function display_sevvai_dropdown($name = 'sevvai', $default = '') {
            ?>
            <select name="<?php echo $name ?>"  class="form-control" >
                <option value="">-Select- </option>
                <option value="Yes"  <?php echo ($default == "Yes") ? " selected " : '' ?> >Yes </option>
                <option value="No"  <?php echo ($default == "No") ? " selected " : '' ?>  >No</option>
                <option value="Pariharam"  <?php echo ($default == "Pariharam") ? " selected " : '' ?>  >Pariharam</option>
            </select>
            <?php
        }

        function display_sevvai_checkbox($name = 'sevvai', $default = '', $style = '') {
            ?>
            <input type="checkbox" name="<?php echo $name ?>" value="" <?php echo ($default > 0) ? " checked " : "" ?> style="<?php echo $style ?>"> 
            <?php
        }

        function is_field_empty($field) {
            // Implement as needed
        }

        function get_marital_status($m) {
            global $marital_status;
            return $marital_status[$m];
        }

        function get_kulam($k) {
            $kulam = get_label($k);
            return isset($kulam['display_name']) ? $kulam['display_name'] : '';
        }

        function get_occupation($o) {
            $occupation = get_label($o);
            return isset($occupation['display_name']) ? $occupation['display_name'] : '';
        }

          function get_raasi($r) {
            global $raasi;
            return isset($raasi[$r]) ? $raasi[$r] : '';
        }

        function get_lagnam($lg) {
            global $raasi;
            return isset($raasi[$lg]) ? $raasi[$lg] : '';
        }

        function get_star($s) {
            global $stars;
            return isset($stars[$s]) ? $stars[$s] : '';
        }

      
        function get_workplace($w) {
            $workplace = get_label($w);
            return isset($workplace['display_name']) ? $workplace['display_name'] : '';
        }

        function get_qualification($q) {
            $qualification = get_label($q);
            return isset($qualification['display_name']) ? $qualification['display_name'] : '';
        }

        function get_blood_group($b) {
            $blood_group = get_label($b);
            return isset($blood_group['display_name']) ? $blood_group['display_name'] : '';
        }

        function get_colour($c) {
            $colour = get_label($c);
            return isset($colour['display_name']) ? $colour['display_name'] : '';
        }

        function get_kattalai($k) {
            $kattalai = get_label($k);
            return isset($kattalai['display_name']) ? $kattalai['display_name'] : '';
        }

        function split_date($date) {
            // Implement as needed
        }

        function get_age($date) {
            // Implement as needed
        }

        function get_max_mem_no($area_code) {
            global $con, $tbl_family;
            $sql = "SELECT max(member_no) as mem_no FROM `$tbl_family` WHERE kattalai='$area_code'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($result);
            if ($row['mem_no'] == null)
                return 1;
            else
                return $row['mem_no'] + 1;
        }

        function generate_member_id($family_id) {
            $fam = get_member($family_id);
            $k_id = $fam['kattalai'];
            $kattalai = get_label($k_id);
            $mem_no = 0;
            $area_code = $kattalai['order'];
            $max_no = get_max_mem_no($area_code);
            $mem_id = $area_code . sprintf("%03d", $max_no);
            $data = array('member_no' => $max_no, 'member_id' => $mem_id);
            update_family_field($family_id, $data);
            var_dump($kattalai['order']);
        }
         function generate_matri_no($m_id) {
            $max_no = get_max_mat_no();
            $data = array('reg_no' => $max_no);
            update_matri_field($m_id, $data);
        }

        function get_max_mat_no() {
            global $con, $tbl_matrimony;
            $sql = "SELECT max(reg_no) as reg_no FROM `$tbl_matrimony`";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($result);
            if ($row['reg_no'] == null)
                return 1;
            else
                return $row['reg_no'] + 1;
        }

// ============================================
// FAMILY TREE FUNCTIONS
// ============================================

/**
 * Generate a unified family tree starting from root ancestor
 */
function generateFamilyTree($root_family_id) {
    return buildFamilyTreeNode($root_family_id);
}

/**
 * Recursively build tree node for a family
 */
function buildFamilyTreeNode($family_id, &$visited = []) {
    global $con, $tbl_child;
    
    // Prevent infinite loops - check if we've already visited this family
    if (in_array($family_id, $visited)) {
        return null;
    }
    
    // Mark this family as visited
    $visited[] = $family_id;
    
    // Limit tree depth to prevent memory issues
    if (count($visited) > 100) {
        return null;
    }
    
    $node = [
        'id' => $family_id,
        'type' => 'family',
        'children' => []
    ];
    
    // Get all children of this family
    $sql = "SELECT id, fam_id FROM $tbl_child WHERE father_id = ? ORDER BY c_dob ASC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $family_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($child = mysqli_fetch_assoc($result)) {
        // Create a child node with child_id and fam_id
        $child_node = [
            'child_id' => $child['id'],
            'fam_id' => $child['fam_id'],
            'type' => 'child',
            'children' => []
        ];
        
        // If child has their own family, recurse to build their family tree
        // But only if we haven't visited that family yet (prevent circular references)
        if (!empty($child['fam_id']) && $child['fam_id'] > 0 && !in_array($child['fam_id'], $visited)) {
            $family_node = buildFamilyTreeNode($child['fam_id'], $visited);
            if ($family_node !== null) {
                $child_node['children'][] = $family_node;
            }
        }
        
        $node['children'][] = $child_node;
    }
    
    mysqli_stmt_close($stmt);
    return $node;
}

/**
 * Find the root ancestor of a family
 */
function findRootAncestor($family_id) {
    global $con, $tbl_family;
    
    $current_id = $family_id;
    $max_iterations = 20;
    $iteration = 0;
    
    while ($iteration < $max_iterations) {
        $sql = "SELECT parent_id FROM $tbl_family WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $current_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        if (!$row || !$row['parent_id'] || $row['parent_id'] == 0) {
            return $current_id;
        }
        
        $current_id = $row['parent_id'];
        $iteration++;
    }
    
    return $family_id;
}

/**
 * Save family tree to ftree table
 */
function saveFamilyTreeToDb($tree_structure) {
    global $con, $tbl_ftree;
    
    $json_tree = json_encode($tree_structure, JSON_UNESCAPED_UNICODE);
    
    $sql = "INSERT INTO $tbl_ftree (tree_structure) VALUES (?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $json_tree);
    mysqli_stmt_execute($stmt);
    $ftree_id = mysqli_insert_id($con);
    mysqli_stmt_close($stmt);
    
    return $ftree_id;
}

/**
 * Assign ftree_id to all families in the tree
 */
function assignFtreeIdToAllFamilies($tree, $ftree_id) {
    global $con, $tbl_family;
    
    $node_type = $tree['type'] ?? 'family';
    
    // Only update if this is a family node
    if ($node_type == 'family' && isset($tree['id'])) {
        $sql = "UPDATE $tbl_family SET ftree_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $ftree_id, $tree['id']);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        
        // Debug log
        error_log("Assigned ftree_id $ftree_id to family {$tree['id']}, affected rows: $affected");
    }
    
    // Recurse through children
    if (isset($tree['children']) && is_array($tree['children'])) {
        foreach ($tree['children'] as $child) {
            assignFtreeIdToAllFamilies($child, $ftree_id);
        }
    }
}

/**
 * Fetch family tree from database
 */
function fetchFamilyTree($ftree_id) {
    global $con, $tbl_ftree;
    
    $sql = "SELECT tree_structure FROM $tbl_ftree WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $ftree_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if ($row) {
        return json_decode($row['tree_structure'], true);
    }
    
    return null;
}

/**
 * Get ftree_id for a family
 */
function getFamilyTreeId($family_id) {
    global $con, $tbl_family;
    
    $sql = "SELECT ftree_id FROM $tbl_family WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $family_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    return $row ? $row['ftree_id'] : null;
}

/**
 * Regenerate family tree
 */
function regenerateFamilyTree($family_id) {
    global $con, $tbl_ftree;
    
    $root_family_id = findRootAncestor($family_id);
    $old_ftree_id = getFamilyTreeId($root_family_id);
    $tree = generateFamilyTree($root_family_id);
    
    if ($old_ftree_id) {
        // Update existing tree
        $json_tree = json_encode($tree, JSON_UNESCAPED_UNICODE);
        $sql = "UPDATE $tbl_ftree SET tree_structure = ?, date_modified = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "si", $json_tree, $old_ftree_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // Update ftree_id for all families in the tree (including newly added ones)
        assignFtreeIdToAllFamilies($tree, $old_ftree_id);
        
        return $old_ftree_id;
    } else {
        // Create new tree
        $ftree_id = saveFamilyTreeToDb($tree);
        assignFtreeIdToAllFamilies($tree, $ftree_id);
        
        return $ftree_id;
    }
}

/**
 * Get or generate family tree for a family
 */
function getOrGenerateFamilyTree($family_id) {
    $ftree_id = getFamilyTreeId($family_id);
    
    if ($ftree_id) {
        $tree = fetchFamilyTree($ftree_id);
        if ($tree) {
            return ['tree' => $tree, 'ftree_id' => $ftree_id];
        }
    }
    
    $ftree_id = regenerateFamilyTree($family_id);
    $tree = fetchFamilyTree($ftree_id);
    
    return ['tree' => $tree, 'ftree_id' => $ftree_id];
}
        
        ?>