<?php
// Start output buffering to catch any unexpected output
ob_start();

include('../init.php');
check_login();

// Clear any previous output
ob_clean();

header('Content-Type: application/json');

$family_id = $_POST['family_id'] ?? 0;

if ($family_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid family ID']);
    exit;
}

try {
    // Check if function exists
    if (!function_exists('regenerateFamilyTree')) {
        throw new Exception('regenerateFamilyTree function not found');
    }
    
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
            'message' => 'Failed to regenerate family tree. Function returned false.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
} catch (Error $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Fatal Error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}

// End output buffering and send output
ob_end_flush();
?>

