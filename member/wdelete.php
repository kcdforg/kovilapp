<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id']) && isset($_GET['w_image'])) {
    $member_id = (int)$_GET['id'];
    $image_filename = $_GET['w_image'];

    if ($member_id > 0 && !empty($image_filename)) {
        $image_path = dirname(__DIR__) . "/images/member/" . $image_filename;

        // Check if the file exists and delete it
        if (file_exists($image_path) && unlink($image_path)) {
            // Update the database to clear the image field
            $sql = "UPDATE $tbl_family SET w_image = '' WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $member_id);

            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = 'Wife photo deleted successfully.';
            } else {
                $response['message'] = 'Failed to update database after deleting file.';
            }
            mysqli_stmt_close($stmt);
        } else {
            // If file doesn't exist or couldn't be unlinked, still try to update DB
            $sql = "UPDATE $tbl_family SET w_image = '' WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $member_id);

            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = 'Wife photo record cleared from database (file not found or already deleted).';
            } else {
                $response['message'] = 'Failed to update database (file not found or already deleted).';
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $response['message'] = 'Invalid member ID or image filename.';
    }
} else {
    $response['message'] = 'Required parameters missing.';
}

echo json_encode($response);
?>
