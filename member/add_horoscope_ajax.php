<?php
include('../init.php');
check_login();

// Set content type to JSON
header('Content-Type: application/json');

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'data' => null
);

try {
    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get POST data
    $post_data = $_POST;
    
    // Validate required fields (if any validation is needed)
    if (empty($post_data['name'])) {
        throw new Exception('Name is required');
    }

    // Prepare the data for the add_horoscope function
    $horoscope_data = array();
    
    // Generate registration number using the existing function
    $horoscope_data['reg_no'] = get_max_mat_no();
    
    // Personal Details
    $horoscope_data['name'] = $post_data['name'] ?? '';
    $horoscope_data['gender'] = $post_data['gender'] ?? '';
    $horoscope_data['age'] = intval($post_data['age'] ?? 0); // int(5) NOT NULL
    $horoscope_data['height'] = intval($post_data['height'] ?? 0); // int(10) NOT NULL
    $horoscope_data['weight'] = intval($post_data['weight'] ?? 0); // int(11) NOT NULL
    $horoscope_data['blood_group'] = $post_data['blood_group'] ?? '';
    $horoscope_data['colour'] = $post_data['colour'] ?? '';
    $horoscope_data['marital_status'] = $post_data['marital_status'] ?? '';
    $horoscope_data['asset_details'] = $post_data['asset_details'] ?? '';
    $horoscope_data['qualification'] = $post_data['qualification'] ?? '';
    $horoscope_data['education_details'] = $post_data['education_details'] ?? '';
    $horoscope_data['occupation'] = $post_data['occupation'] ?? '';
    $horoscope_data['occupation_details'] = $post_data['occupation_details'] ?? '';
    $horoscope_data['college_details'] = $post_data['college_details'] ?? '';
    $horoscope_data['income'] = $post_data['income'] ?? '';
    $horoscope_data['country'] = $post_data['country'] ?? '';
    $horoscope_data['address'] = $post_data['address'] ?? '';

    // Contact Details
    $horoscope_data['mobile_no'] = $post_data['mobile_no'] ?? '';
    $horoscope_data['email'] = $post_data['email'] ?? '';
    $horoscope_data['contact_person'] = $post_data['contact_person'] ?? '';
    $horoscope_data['relationship'] = $post_data['relationship'] ?? '';

    // Family Details
    $horoscope_data['father_name'] = $post_data['father_name'] ?? '';
    $horoscope_data['mother_name'] = $post_data['mother_name'] ?? '';
    $horoscope_data['f_occupation'] = $post_data['f_occupation'] ?? '';
    $horoscope_data['m_occupation'] = $post_data['m_occupation'] ?? '';
    $horoscope_data['sibling'] = $post_data['sibling'] ?? '';
    $horoscope_data['kulam'] = $post_data['kulam'] ?? '';
    $horoscope_data['temple'] = $post_data['temple'] ?? '';
    $horoscope_data['m_kulam'] = $post_data['m_kulam'] ?? '';
    $horoscope_data['mm_kulam'] = $post_data['mm_kulam'] ?? '';
    $horoscope_data['pm_kulam'] = $post_data['pm_kulam'] ?? '';

    // Horoscope Details
    $horoscope_data['birth_date'] = array(
        'day' => $post_data['birth_date']['day'] ?? '',
        'month' => $post_data['birth_date']['month'] ?? '',
        'year' => $post_data['birth_date']['year'] ?? ''
    );
    $horoscope_data['birth_time'] = array(
        'hour' => $post_data['birth_time_hour'] ?? '',
        'min' => $post_data['birth_time_min'] ?? ''
    );
    $horoscope_data['birth_place'] = $post_data['birth_place'] ?? '';
    $horoscope_data['raaghu_kaedhu'] = isset($post_data['raaghu_kaedhu']) ? 1 : 0;
    $horoscope_data['sevvai'] = isset($post_data['sevvai']) ? 1 : 0;
    $horoscope_data['raasi'] = $post_data['raasi'] ?? '';
    $horoscope_data['laknam'] = $post_data['laknam'] ?? '';
    $horoscope_data['star'] = $post_data['star'] ?? '';
    $horoscope_data['padham'] = $post_data['padham'] ?? '';

    // Referrer Details
    $horoscope_data['ref_id'] = intval($post_data['ref_id'] ?? 0); // bigint(20) NOT NULL
    $horoscope_data['referred_by'] = $post_data['referred_by'] ?? '';
    $horoscope_data['registered_date'] = date('Y-m-d'); // date NOT NULL
    
    // Additional Details
    $horoscope_data['about_myself'] = $post_data['about_myself'] ?? '';
    
    // Status and Admin Fields (if they exist in database)
    $horoscope_data['status'] = $post_data['status'] ?? 'active';
    $horoscope_data['admin_notes'] = $post_data['admin_notes'] ?? '';
    $horoscope_data['close_reason_code'] = intval($post_data['close_reason_code'] ?? 0); // int(11) NOT NULL
    $horoscope_data['married_to'] = intval($post_data['married_to'] ?? 0); // int(11) NOT NULL
    $horoscope_data['close_reason_detail'] = $post_data['close_reason_detail'] ?? '';
    
    // Missing fields from schema
    $horoscope_data['photo'] = $post_data['photo'] ?? '';
    $horoscope_data['horo'] = $post_data['horo'] ?? '';
    $horoscope_data['deleted'] = intval($post_data['deleted'] ?? 0); // tinyint(1) NOT NULL

    // Expectation
    $horoscope_data['pp_education'] = $post_data['pp_education'] ?? '';
    $horoscope_data['pp_occupation'] = $post_data['pp_occupation'] ?? '';
    $horoscope_data['pp_work_location'] = $post_data['pp_work_location'] ?? '';
    $horoscope_data['pp_salary'] = $post_data['pp_salary'] ?? '';
    $horoscope_data['pp_asset_details'] = $post_data['pp_asset_details'] ?? '';
    $horoscope_data['pp_expectation'] = $post_data['pp_expectation'] ?? '';

    // Call the add_horoscope function
    $result = add_horoscope($horoscope_data);
    
    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Horoscope added successfully!';
        $response['data'] = array('horoscope_id' => $result);
    } else {
        throw new Exception('Failed to add horoscope: ' . mysqli_error($con));
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
} catch (Error $e) {
    $response['success'] = false;
    $response['message'] = 'System Error: ' . $e->getMessage();
}

// Return JSON response
echo json_encode($response);
exit;
?> 