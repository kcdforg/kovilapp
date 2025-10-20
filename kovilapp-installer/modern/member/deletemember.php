<?php
include('../init.php');

// Check if user is logged in
check_login();

// Set JSON header
header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;
$response = array(
    'success' => false,
    'message' => '',
    'member_name' => ''
);

// Get member details directly from database
$member_sql = "SELECT id, name, deleted FROM $tbl_family WHERE id = ?";
$member_stmt = mysqli_prepare($con, $member_sql);
mysqli_stmt_bind_param($member_stmt, "i", $id);
mysqli_stmt_execute($member_stmt);
$member_result = mysqli_stmt_get_result($member_stmt);
$member = mysqli_fetch_assoc($member_result);
mysqli_stmt_close($member_stmt);

if (!$member) {
    $response['message'] = 'Member not found with ID: ' . $id;
} else {
    // Check if already deleted
    if ($member['deleted'] == 1) {
        $response['message'] = 'Member "' . htmlspecialchars($member['name']) . '" is already deleted.';
    } else {
        // Perform the deletion (soft delete by setting deleted=1)
        $sql = "UPDATE $tbl_family SET `deleted`=1 WHERE id=? AND deleted=0";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            if ($affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Member "' . htmlspecialchars($member['name']) . '" has been successfully deleted.';
                $response['member_name'] = htmlspecialchars($member['name']);
            } else {
                $response['message'] = 'No rows were updated. Member may already be deleted.';
            }
        } else {
            $response['message'] = 'Error deleting member: ' . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}

// Return JSON response
echo json_encode($response);
exit();
?>