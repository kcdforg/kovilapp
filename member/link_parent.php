<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

global $con, $tbl_family, $tbl_child;

$member_id = $_POST['member_id'] ?? '';
$parent_id = $_POST['parent_id'] ?? '';

if (empty($member_id) || empty($parent_id)) {
    echo json_encode(['success' => false, 'message' => 'Member ID and Parent ID are required']);
    exit();
}

// Validate that both members exist and get member details
$check_sql = "SELECT id, name, dob, blood_group, qualification, occupation, mobile_no, email, education_details, occupation_details FROM $tbl_family WHERE id = ? AND deleted = 0";

// Check member exists and get details
$stmt = mysqli_prepare($con, $check_sql);
mysqli_stmt_bind_param($stmt, "i", $member_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$member = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$member) {
    echo json_encode(['success' => false, 'message' => 'Member not found']);
    exit();
}

// Check parent exists
$stmt = mysqli_prepare($con, "SELECT id FROM $tbl_family WHERE id = ? AND deleted = 0");
mysqli_stmt_bind_param($stmt, "i", $parent_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) == 0) {
    mysqli_stmt_close($stmt);
    echo json_encode(['success' => false, 'message' => 'Parent family not found']);
    exit();
}
mysqli_stmt_close($stmt);

// Prevent linking to self
if ($member_id == $parent_id) {
    echo json_encode(['success' => false, 'message' => 'Cannot link a member as their own parent']);
    exit();
}

// Start transaction
mysqli_begin_transaction($con);

try {
    // Update the member's parent_id
    $update_sql = "UPDATE $tbl_family SET parent_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $update_sql);
    mysqli_stmt_bind_param($stmt, "ii", $parent_id, $member_id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error updating parent_id: ' . mysqli_error($con));
    }
    mysqli_stmt_close($stmt);

    // Check if child record already exists (with fam_id linking to this member)
    $check_child_sql = "SELECT id FROM $tbl_child WHERE father_id = ? AND fam_id = ?";
    $stmt = mysqli_prepare($con, $check_child_sql);
    mysqli_stmt_bind_param($stmt, "ii", $parent_id, $member_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $child_exists = mysqli_stmt_num_rows($stmt) > 0;
    
    if ($child_exists) {
        // Get child id for update
        mysqli_stmt_bind_result($stmt, $child_id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        
        // Update existing child record
        $update_child_sql = "UPDATE $tbl_child SET 
            c_name = ?, 
            c_dob = ?, 
            c_blood_group = ?, 
            c_qualification = ?, 
            c_occupation = ?, 
            c_mobile_no = ?, 
            c_email = ?,
            c_education_details = ?,
            c_occupation_details = ?,
            c_gender = 'male',
            c_marital_status = 'Yes',
            c_lastmodified_by = ?,
            c_lastmodified_date = ?
            WHERE id = ?";
        
        $stmt = mysqli_prepare($con, $update_child_sql);
        $current_user = $_SESSION['username'] ?? 'admin';
        $current_date = date('Y-m-d');
        mysqli_stmt_bind_param($stmt, "ssissssssssi", 
            $member['name'],
            $member['dob'],
            $member['blood_group'],
            $member['qualification'],
            $member['occupation'],
            $member['mobile_no'],
            $member['email'],
            $member['education_details'],
            $member['occupation_details'],
            $current_user,
            $current_date,
            $child_id
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Error updating child record: ' . mysqli_error($con));
        }
        mysqli_stmt_close($stmt);
    } else {
        mysqli_stmt_close($stmt);
        
        // Insert new child record
        $insert_child_sql = "INSERT INTO $tbl_child 
            (father_id, fam_id, c_name, c_dob, c_gender, c_blood_group, c_marital_status, 
             c_qualification, c_occupation, c_mobile_no, c_email, c_education_details, 
             c_occupation_details, c_image, c_created_date, c_created_by, c_lastmodified_by, c_lastmodified_date) 
            VALUES (?, ?, ?, ?, 'male', ?, 'Yes', ?, ?, ?, ?, ?, ?, '', ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $insert_child_sql);
        $current_user = $_SESSION['username'] ?? 'admin';
        $current_date = date('Y-m-d');
        mysqli_stmt_bind_param($stmt, "iisssisssssssss", 
            $parent_id,
            $member_id,
            $member['name'],
            $member['dob'],
            $member['blood_group'],
            $member['qualification'],
            $member['occupation'],
            $member['mobile_no'],
            $member['email'],
            $member['education_details'],
            $member['occupation_details'],
            $current_date,
            $current_user,
            $current_user,
            $current_date
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Error creating child record: ' . mysqli_error($con));
        }
        mysqli_stmt_close($stmt);
    }

    // Commit transaction
    mysqli_commit($con);
    
    // Regenerate family tree for both families
    if (function_exists('regenerateFamilyTree')) {
        regenerateFamilyTree($parent_id);
        regenerateFamilyTree($member_id);
    }
    
    echo json_encode(['success' => true, 'message' => 'Parent family linked successfully']);
    
} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
