<?php
include('../../init.php');
check_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$group_type_id = (int)($_POST['group_type_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$code = trim($_POST['code'] ?? '');
$description = trim($_POST['description'] ?? '');
$display_order = (int)($_POST['display_order'] ?? 0);

if ($group_type_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid group type ID']);
    exit;
}

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

$data = [
    'group_type_id' => $group_type_id,
    'name' => $name,
    'code' => $code,
    'description' => $description,
    'display_order' => $display_order
];

try {
    $result = add_group($data);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Group added successfully',
            'id' => $result
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add group'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

