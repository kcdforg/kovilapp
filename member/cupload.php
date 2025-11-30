<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id']) && isset($_FILES['c_image'])) {
    $child_id = (int)$_GET['id'];
    
    if ($child_id > 0) {
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
        if ($_FILES['c_image']['error'] > 0) {
            $response['message'] = 'Upload error: ' . $_FILES['c_image']['error'];
            echo json_encode($response);
            exit;
        }
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        $file_type = $_FILES['c_image']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $response['message'] = 'Invalid file type. Only JPEG, JPG and PNG images are allowed.';
            echo json_encode($response);
            exit;
        }
        
        // Generate filename
        $file_extension = pathinfo($_FILES['c_image']['name'], PATHINFO_EXTENSION);
        $filename = $child_id . "_child." . $file_extension;
        $target_path = $upload_dir . $filename;
        
        // Delete old image if exists
        $old_images = glob($upload_dir . $child_id . "_child.*");
        foreach ($old_images as $old_image) {
            if (file_exists($old_image)) {
                unlink($old_image);
            }
        }
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['c_image']['tmp_name'], $target_path)) {
            chmod($target_path, 0664);
            
            // Update database
            $sql = "UPDATE $tbl_child SET c_image = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $filename, $child_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = 'Photo uploaded successfully!';
                $response['filename'] = $filename;
            } else {
                $response['message'] = 'Failed to update database: ' . mysqli_error($con);
                // Delete uploaded file if database update fails
                unlink($target_path);
            }
            mysqli_stmt_close($stmt);
        } else {
            $response['message'] = 'Failed to move uploaded file.';
        }
    } else {
        $response['message'] = 'Invalid child ID.';
    }
} else {
    $response['message'] = 'Required parameters missing.';
}

echo json_encode($response);
?>
