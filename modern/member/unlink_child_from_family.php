<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$child_id = $_POST['child_id'] ?? '';

if (empty($child_id)) {
    echo json_encode(['success' => false, 'message' => 'Child ID is required']);
    exit();
}

global $con, $tbl_child;

// Update child record to unlink from family (set fam_id to 0)
$sql = "UPDATE $tbl_child SET fam_id = 0 WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $child_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Family unlinked successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error unlinking family: ' . mysqli_error($con)]);
}

mysqli_stmt_close($stmt);
?>

