<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id'])) {
    $child_id = (int)$_GET['id'];
    
    if ($child_id > 0) {
        // Delete the child record
        $sql = "DELETE FROM $tbl_child WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $child_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Child record deleted successfully.';
        } else {
            $response['message'] = 'Failed to delete child record: ' . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Invalid child ID.';
    }
} else {
    $response['message'] = 'Child ID not provided.';
}

echo json_encode($response);
?>

