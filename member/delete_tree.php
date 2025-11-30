<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$family_id = $_POST['family_id'] ?? 0;

if ($family_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid family ID']);
    exit;
}

global $con, $tbl_ftree, $tbl_family;

// Get the ftree_id for this family
$sql = "SELECT ftree_id FROM $tbl_family WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $family_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$ftree_id = $row ? $row['ftree_id'] : null;

if (!$ftree_id) {
    echo json_encode(['success' => false, 'message' => 'No family tree found for this family']);
    exit;
}

// Delete the tree from ftree table
$sql = "DELETE FROM $tbl_ftree WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $ftree_id);
$success = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!$success) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete tree from database']);
    exit;
}

// Set ftree_id to NULL for all families that had this tree
$sql = "UPDATE $tbl_family SET ftree_id = NULL WHERE ftree_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $ftree_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo json_encode([
    'success' => true,
    'message' => 'Family tree deleted successfully'
]);
?>

