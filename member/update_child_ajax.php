<?php
include('../init.php');
check_login();

// Set JSON content type
header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Validate required fields
if (empty($_POST['c_name']) || empty($_POST['c_dob']) || empty($_POST['child_id'])) {
    $response['message'] = 'Name, Date of Birth, and Child ID are required fields';
    echo json_encode($response);
    exit;
}

try {
    // Prepare the data for update_child function
    $child_data = array(
        'c_name' => $_POST['c_name'],
        'c_dob' => $_POST['c_dob'],
        'c_gender' => $_POST['c_gender'] ?? '',
        'c_blood_group' => $_POST['c_blood_group'] ?? '',
        'c_marital_status' => $_POST['c_marital_status'] ?? 'No',
        'c_qualification' => $_POST['c_qualification'] ?? '',
        'c_mobile_no' => $_POST['c_mobile_no'] ?? '',
        'c_email' => $_POST['c_email'] ?? '',
        'c_occupation' => $_POST['c_occupation'] ?? '',
        'c_education_details' => $_POST['c_education_details'] ?? '',
        'c_occupation_details' => $_POST['c_occupation_details'] ?? ''
    );
    
    $child_id = $_POST['child_id'];
    
    // Get the father_id before updating to regenerate family tree
    global $con, $tbl_child;
    $sql = "SELECT father_id FROM $tbl_child WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $child_id);
    mysqli_stmt_execute($stmt);
    $result_child = mysqli_stmt_get_result($stmt);
    $child_record = mysqli_fetch_assoc($result_child);
    mysqli_stmt_close($stmt);
    
    // Call the update_child function
    $result = update_child($child_id, $child_data);
    
    if ($result) {
        // Regenerate family tree if child belongs to a family
        if ($child_record && !empty($child_record['father_id'])) {
            regenerateFamilyTree($child_record['father_id']);
        }
        
        $response['success'] = true;
        $response['message'] = 'Child updated successfully!';
    } else {
        $response['message'] = 'Error: ' . mysqli_error($con);
    }
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?> 