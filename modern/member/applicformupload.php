<?php
include('../popupheader.php');

$upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/";

if (is_dir($upload_dir) && is_writable($upload_dir)) {
    // do upload logic here
} else {
    echo 'Upload directory is not writable, or does not exist.';
}

//var_dump($_FILES);

$id = $_GET['id'];
$s = $id . "_appfront" . ".jpg";
$s1 = $id . "_appback" . ".jpg";
if ($_FILES["appfront"]["error"] && $_FILES["app_back"]["error"] > 0) {
    echo "Return Code: " . $_FILES["appfront"]["error"] . $_FILES["app_back"]["error"] . "<br />";
} else {
    //echo "images: " . $_FILES["image"]["$s"] . "<br />";
    // echo "Type: " . $_FILES["appfront"]["type"] . "<br />";
    //echo "Type: " . $_FILES["app_back"]["type"] . "<br />";
    //echo "Size: " . ($_FILES["image"]["size"] / 1024) . " Kb<br />";
    //echo "Temp file: " . $_FILES["image"]["tmp_name"] . "<br />";
    

    if ($_FILES["appfront"]["type"] && $_FILES["app_back"]["type"] != 'image/jpeg') {
        echo "Image should be in jpeg format";
        die;
    } else {
        if (file_exists("../images/" . $s)) {
            echo $_FILES["appfront"]["name"] . " already exists. ";
        } else {
            $res = move_uploaded_file($_FILES["appfront"]["tmp_name"], "../images/" . $s);
            //var_dump($res);
			//echo "Stored in: " . "images/" . $s; 
        }

        if (file_exists("../images/" . $s1)) {
            echo $_FILES["app_back"]["name"] . " already exists. ";
        } else {
            move_uploaded_file($_FILES["app_back"]["tmp_name"], "../images/" . $s1);
            // echo "Stored in: " . "images/" . $s1; 
            ?>
            <div class="col-md-3 col-sm-6 col-xs-12" >
                <div class="info-box bg-green">
                    <span class="info-box-icon" style="padding_top:20px"><i class="fa fa-thumbs-o-up"></i></span>
                    <div class="info-box-content" style="padding_top:20px">
                        <span class="info-box-text"><h4>Upload success!</h4></span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div>
            <?php
        }
    }

    include('function.php');
$image=$_FILES["image"]["$s"];
echo $image;
  
    $sql = "UPDATE `$tbl_family` SET `appfront`='$s', `app_back`='$s1' where `id`=$id";
    if (!mysql_query($sql, $con)) {
        die('Error: ' . mysql_error());
    }
}
?>

