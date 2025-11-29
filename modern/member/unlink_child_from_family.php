<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$child_id = $_POST['child_id'] ?? '';

if (empty($child_id)) {
    echo json_encode(['success' => false, 'message' => 'Child ID is required']);
    exit();
}

global $con, $tbl_child, $tbl_family;

// Get the father_id (parent family id) and old fam_id before unlinking
$sql = "SELECT father_id, fam_id FROM $tbl_child WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $child_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$child_data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$child_data) {
    echo json_encode(['success' => false, 'message' => 'Child not found']);
    exit();
}

$parent_family_id = $child_data['father_id'];
$old_fam_id = $child_data['fam_id'];

// Update child record to unlink from family (set fam_id to 0)
$sql = "UPDATE $tbl_child SET fam_id = 0 WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $child_id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    
    // Clear parent_id from the unlinked family
    if ($old_fam_id > 0) {
        $sql = "UPDATE $tbl_family SET parent_id = NULL WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $old_fam_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Regenerate the parent family tree
    regenerateFamilyTree($parent_family_id);
    
    echo json_encode(['success' => true, 'message' => 'Family unlinked and tree updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error unlinking family: ' . mysqli_error($con)]);
    mysqli_stmt_close($stmt);
}
?>

