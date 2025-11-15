<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$child_id = $_POST['child_id'] ?? '';
$family_id = $_POST['family_id'] ?? '';

if (empty($child_id) || empty($family_id)) {
    echo json_encode(['success' => false, 'message' => 'Child ID and Family ID are required']);
    exit();
}

global $con, $tbl_child;

// Update child record to link to family
$sql = "UPDATE $tbl_child SET fam_id = ? WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $family_id, $child_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Family linked successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error linking family: ' . mysqli_error($con)]);
}

mysqli_stmt_close($stmt);
?>

