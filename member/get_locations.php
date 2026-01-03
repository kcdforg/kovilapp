<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($search)) {
    echo json_encode(['results' => []]);
    exit;
}

global $con, $tbl_family;

// Search for locations in the database
// Get unique combinations of location data where village, taluk, or district matches search
$sql = "SELECT DISTINCT 
            village, taluk, district, state, country, pincode
        FROM $tbl_family 
        WHERE deleted = 0 
        AND (
            village LIKE ? OR 
            taluk LIKE ? OR 
            district LIKE ?
        )
        AND village IS NOT NULL 
        AND village != ''
        GROUP BY village, taluk, district, state, country, pincode
        ORDER BY village ASC
        LIMIT 50";

$search_param = '%' . $search . '%';
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "sss", $search_param, $search_param, $search_param);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$locations = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Build display text: "Village - Taluk, District, State (Pincode)"
    $display_parts = [];
    
    if (!empty($row['village'])) {
        $display_parts[] = $row['village'];
    }
    
    $location_parts = [];
    if (!empty($row['taluk'])) $location_parts[] = $row['taluk'];
    if (!empty($row['district'])) $location_parts[] = $row['district'];
    if (!empty($row['state'])) $location_parts[] = $row['state'];
    
    if (!empty($location_parts)) {
        $display_parts[] = implode(', ', $location_parts);
    }
    
    $display_text = implode(' - ', $display_parts);
    
    if (!empty($row['pincode'])) {
        $display_text .= ' (' . $row['pincode'] . ')';
    }
    
    $locations[] = [
        'id' => base64_encode(json_encode($row)), // Encode all data as ID
        'text' => $display_text,
        'village' => $row['village'],
        'taluk' => $row['taluk'],
        'district' => $row['district'],
        'state' => $row['state'],
        'country' => $row['country'],
        'pincode' => $row['pincode']
    ];
}

mysqli_stmt_close($stmt);

// Add "Add New Location" option at the end
$response = [
    'results' => $locations,
    'pagination' => ['more' => false]
];

echo json_encode($response);

