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

global $con, $tbl_child, $tbl_family;

// Get the father_id (parent family id) from the child record
$sql = "SELECT father_id FROM $tbl_child WHERE id = ?";
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

// Check if the family is already linked to another child
$sql = "SELECT c.id, c.c_name, f.name as father_name FROM $tbl_child c 
        LEFT JOIN $tbl_family f ON c.father_id = f.id 
        WHERE c.fam_id = ? AND c.id != ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $family_id, $child_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$existing_link = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($existing_link) {
    echo json_encode([
        'success' => false, 
        'message' => 'This family is already linked to "' . htmlspecialchars($existing_link['c_name']) . '" from "' . htmlspecialchars($existing_link['father_name']) . '" family. Please unlink that first or choose a different family.'
    ]);
    exit();
}

// Update child record to link to family
$sql = "UPDATE $tbl_child SET fam_id = ? WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $family_id, $child_id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    
    // Update the linked family's parent_id to point to the parent family
    $sql = "UPDATE $tbl_family SET parent_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $parent_family_id, $family_id);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        
        // Regenerate the family tree to include the newly linked family
        $ftree_id = regenerateFamilyTree($parent_family_id);
        
        if ($ftree_id) {
            echo json_encode(['success' => true, 'message' => 'Family linked and tree updated successfully']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Family linked successfully but tree update failed']);
        }
    } else {
        mysqli_stmt_close($stmt);
        echo json_encode(['success' => false, 'message' => 'Error updating parent_id: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error linking family: ' . mysqli_error($con)]);
    mysqli_stmt_close($stmt);
}
?>

