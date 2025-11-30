<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id']) && isset($_GET['app_front']) && isset($_GET['app_back'])) {
    $member_id = (int)$_GET['id'];
    $app_front = $_GET['app_front'];
    $app_back = $_GET['app_back'];

    if ($member_id > 0) {
        $image_dir = dirname(__DIR__) . "/images/member/";
        
        // Delete front image if exists
        if (!empty($app_front)) {
            $front_path = $image_dir . $app_front;
            if (file_exists($front_path)) {
                unlink($front_path);
            }
        }
        
        // Delete back image if exists
        if (!empty($app_back)) {
            $back_path = $image_dir . $app_back;
            if (file_exists($back_path)) {
                unlink($back_path);
            }
        }
        
        // Update the database to clear the image fields
        $sql = "UPDATE $tbl_family SET app_front = '', app_back = '' WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $member_id);

        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Application deleted successfully.';
        } else {
            $response['message'] = 'Failed to update database.';
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Invalid member ID.';
    }
} else {
    $response['message'] = 'Required parameters missing.';
}

echo json_encode($response);
?>

