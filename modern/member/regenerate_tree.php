<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$family_id = $_POST['family_id'] ?? 0;

if ($family_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid family ID']);
    exit;
}

try {
    // Regenerate the family tree
    $ftree_id = regenerateFamilyTree($family_id);
    
    if ($ftree_id) {
        echo json_encode([
            'success' => true,
            'message' => 'Family tree regenerated successfully',
            'ftree_id' => $ftree_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to regenerate family tree'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>

