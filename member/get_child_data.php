<?php
include('../init.php');
check_login();

// Set JSON content type
header('Content-Type: application/json');

$response = array('success' => false, 'message' => '', 'child' => null);

// Check if it's a GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Validate required fields
if (empty($_GET['id'])) {
    $response['message'] = 'Child ID is required';
    echo json_encode($response);
    exit;
}

try {
    $child_id = $_GET['id'];
    
    // Get child data using the existing function
    $child_data = get_child($child_id);
    
    if ($child_data) {
        $response['success'] = true;
        $response['child'] = $child_data;
        $response['message'] = 'Child data loaded successfully';
    } else {
        $response['message'] = 'Child not found';
    }
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?> 