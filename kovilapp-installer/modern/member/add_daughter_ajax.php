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
if (empty($_POST['c_name']) || empty($_POST['c_dob'])) {
    $response['message'] = 'Name and Date of Birth are required fields';
    echo json_encode($response);
    exit;
}

try {
    // Prepare the data for add_child function
    $child_data = array(
        'c_name' => $_POST['c_name'],
        'c_dob' => $_POST['c_dob'],
        'c_gender' => $_POST['c_gender'] ?? 'female',
        'c_blood_group' => $_POST['c_blood_group'] ?? '-',
        'c_marital_status' => $_POST['c_marital_status'] ?? 'No',
        'c_qualification' => $_POST['c_qualification'] ?? '-',
        'c_mobile_no' => $_POST['c_mobile_no'] ?? '-',
        'c_email' => $_POST['c_email'] ?? '-',
        'c_occupation' => $_POST['c_occupation'] ?? '-',
        'c_education_details' => $_POST['c_education_details'] ?? '-',
        'c_occupation_details' => $_POST['c_occupation_details'] ?? '-',
        'father_id' => $_POST['father_id']
    );
    
    // Call the add_child function
    $result = add_child($child_data);
    
    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Daughter added successfully!';
    } else {
        $response['message'] = 'Error: ' . mysqli_error($con);
    }
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?> 