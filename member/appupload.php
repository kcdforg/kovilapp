<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id']) && isset($_FILES['app_front']) && isset($_FILES['app_back'])) {
    $member_id = (int)$_GET['id'];
    
    if ($member_id > 0) {
        $upload_dir = dirname(__DIR__) . "/images/member/";
        
        // Check if upload directory exists and is writable
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        if (!is_writable($upload_dir)) {
            $response['message'] = 'Upload directory is not writable.';
            echo json_encode($response);
            exit;
        }
        
        // Check for upload errors
        if ($_FILES['app_front']['error'] > 0 || $_FILES['app_back']['error'] > 0) {
            $response['message'] = 'Upload error: Front=' . $_FILES['app_front']['error'] . ', Back=' . $_FILES['app_back']['error'];
            echo json_encode($response);
            exit;
        }
        
        // Validate file types
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        $front_type = $_FILES['app_front']['type'];
        $back_type = $_FILES['app_back']['type'];
        
        if (!in_array($front_type, $allowed_types) || !in_array($back_type, $allowed_types)) {
            $response['message'] = 'Invalid file type. Only JPEG, JPG and PNG images are allowed.';
            echo json_encode($response);
            exit;
        }
        
        // Generate filenames
        $front_extension = pathinfo($_FILES['app_front']['name'], PATHINFO_EXTENSION);
        $back_extension = pathinfo($_FILES['app_back']['name'], PATHINFO_EXTENSION);
        $front_filename = $member_id . "_appfront." . $front_extension;
        $back_filename = $member_id . "_appback." . $back_extension;
        $front_path = $upload_dir . $front_filename;
        $back_path = $upload_dir . $back_filename;
        
        // Delete old images if exist
        $old_front_images = glob($upload_dir . $member_id . "_appfront.*");
        foreach ($old_front_images as $old_image) {
            if (file_exists($old_image)) {
                unlink($old_image);
            }
        }
        
        $old_back_images = glob($upload_dir . $member_id . "_appback.*");
        foreach ($old_back_images as $old_image) {
            if (file_exists($old_image)) {
                unlink($old_image);
            }
        }
        
        // Move uploaded files
        if (move_uploaded_file($_FILES['app_front']['tmp_name'], $front_path) && 
            move_uploaded_file($_FILES['app_back']['tmp_name'], $back_path)) {
            chmod($front_path, 0664);
            chmod($back_path, 0664);
            
            // Update database
            $sql = "UPDATE $tbl_family SET app_front = ?, app_back = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 'ssi', $front_filename, $back_filename, $member_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = 'Application uploaded successfully!';
                $response['app_front'] = $front_filename;
                $response['app_back'] = $back_filename;
            } else {
                $response['message'] = 'Failed to update database: ' . mysqli_error($con);
                // Delete uploaded files if database update fails
                unlink($front_path);
                unlink($back_path);
            }
            mysqli_stmt_close($stmt);
        } else {
            $response['message'] = 'Failed to move uploaded files.';
        }
    } else {
        $response['message'] = 'Invalid member ID.';
    }
} else {
    $response['message'] = 'Required parameters missing.';
}

echo json_encode($response);
?>

