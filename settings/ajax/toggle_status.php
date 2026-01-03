<?php
include('../../init.php');
check_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$type = $_POST['type'] ?? ''; // 'group' or 'group_type'
$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit;
}

try {
    if ($type === 'group_type') {
        $result = toggle_group_type_status($id);
    } elseif ($type === 'group') {
        $result = toggle_group_status($id);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid type']);
        exit;
    }
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update status'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

