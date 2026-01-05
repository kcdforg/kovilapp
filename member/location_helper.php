<?php
/**
 * Location Helper Functions
 * Contains functions to fetch location data from database
 */

/**
 * Get all distinct location field values
 * Returns individual values for village, taluk, district, state, pincode
 */
function getAllLocationFields() {
    global $con, $tbl_family;
    
    $locations = [
        'village' => [],
        'taluk' => [],
        'district' => [],
        'state' => [],
        'pincode' => []
    ];
    
    $fields = ['village', 'taluk', 'district', 'state', 'pincode'];
    
    foreach ($fields as $field) {
        $sql = "SELECT DISTINCT $field FROM $tbl_family 
                WHERE deleted = 0 
                AND $field IS NOT NULL 
                AND $field != '' 
                ORDER BY $field ASC";
        
        $result = mysqli_query($con, $sql);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value = $row[$field];
                if (!empty($value)) {
                    $locations[$field][] = [
                        'id' => $value,
                        'text' => $value
                    ];
                }
            }
        }
    }
    
    return $locations;
}

/**
 * Get all location combinations
 * Returns complete location records with village, taluk, district, state, country, pincode
 */
function getAllLocationCombinations() {
    global $con, $tbl_family;
    
    $sql = "SELECT DISTINCT 
                village, taluk, district, state, country, pincode
            FROM $tbl_family 
            WHERE deleted = 0 
            AND village IS NOT NULL 
            AND village != ''
            ORDER BY village ASC
            LIMIT 500";
    
    $result = mysqli_query($con, $sql);
    
    $locations = [];
    if ($result) {
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
                'id' => base64_encode(json_encode($row)),
                'text' => $display_text,
                'village' => $row['village'],
                'taluk' => $row['taluk'],
                'district' => $row['district'],
                'state' => $row['state'],
                'country' => $row['country'],
                'pincode' => $row['pincode']
            ];
        }
    }
    
    return $locations;
}
?>
