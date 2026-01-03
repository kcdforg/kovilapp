<?php
include('../../init.php');
check_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
$description = trim($_POST['description'] ?? '');
$is_required = isset($_POST['is_required']) ? 1 : 0;
$display_order = (int)($_POST['display_order'] ?? 0);

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

if (empty($slug)) {
    // Auto-generate slug from name
    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $name));
}

$data = [
    'name' => $name,
    'slug' => $slug,
    'description' => $description,
    'is_required' => $is_required,
    'display_order' => $display_order
];

try {
    $result = add_group_type($data);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Group type added successfully',
            'id' => $result
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add group type. Slug may already exist.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

