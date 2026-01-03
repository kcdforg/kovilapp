<?php
include('../../init.php');
check_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
$description = trim($_POST['description'] ?? '');
$is_required = isset($_POST['is_required']) ? 1 : 0;
$display_order = (int)($_POST['display_order'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit;
}

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

$data = [
    'name' => $name,
    'slug' => $slug,
    'description' => $description,
    'is_required' => $is_required,
    'display_order' => $display_order
];

try {
    $result = update_group_type($id, $data);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Group type updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update group type'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

