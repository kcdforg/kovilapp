<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

global $con, $tbl_family;

// If type parameter is provided, return individual field suggestions (for modal selectize)
if (!empty($type)) {
    $search_param = !empty($search) ? '%' . $search . '%' : '%';
    $results = [];
    
    switch ($type) {
        case 'village':
            $sql = "SELECT DISTINCT village FROM $tbl_family 
                    WHERE deleted = 0 
                    AND village IS NOT NULL 
                    AND village != '' 
                    AND village LIKE ? 
                    ORDER BY village ASC 
                    LIMIT 30";
            break;
            
        case 'taluk':
            $sql = "SELECT DISTINCT taluk FROM $tbl_family 
                    WHERE deleted = 0 
                    AND taluk IS NOT NULL 
                    AND taluk != '' 
                    AND taluk LIKE ? 
                    ORDER BY taluk ASC 
                    LIMIT 30";
            break;
            
        case 'district':
            $sql = "SELECT DISTINCT district FROM $tbl_family 
                    WHERE deleted = 0 
                    AND district IS NOT NULL 
                    AND district != '' 
                    AND district LIKE ? 
                    ORDER BY district ASC 
                    LIMIT 30";
            break;
            
        case 'state':
            $sql = "SELECT DISTINCT state FROM $tbl_family 
                    WHERE deleted = 0 
                    AND state IS NOT NULL 
                    AND state != '' 
                    AND state LIKE ? 
                    ORDER BY state ASC 
                    LIMIT 30";
            break;
            
        case 'pincode':
            $sql = "SELECT DISTINCT pincode FROM $tbl_family 
                    WHERE deleted = 0 
                    AND pincode IS NOT NULL 
                    AND pincode != '' 
                    AND pincode LIKE ? 
                    ORDER BY pincode ASC 
                    LIMIT 30";
            break;
            
        default:
            echo json_encode(['results' => []]);
            exit;
    }
    
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        error_log("Prepare failed for type=$type: " . mysqli_error($con));
        echo json_encode(['results' => [], 'error' => 'Database error']);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $search_param);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed for type=$type: " . mysqli_stmt_error($stmt));
        echo json_encode(['results' => [], 'error' => 'Query error']);
        exit;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Get result failed for type=$type: " . mysqli_stmt_error($stmt));
        echo json_encode(['results' => [], 'error' => 'Result error']);
        exit;
    }
    
    while ($row = mysqli_fetch_assoc($result)) {
        $column_name = array_key_first($row);
        $value = $row[$column_name];
        if (!empty($value)) {
            $results[] = [
                'id' => $value,
                'text' => $value
            ];
        }
    }
    
    mysqli_stmt_close($stmt);
    echo json_encode(['results' => $results, 'count' => count($results), 'type' => $type, 'search' => $search]);
    exit;
}

// Original behavior: Search for complete location combinations
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
?>
