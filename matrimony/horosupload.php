<?php
include('../popupheader.php');
$id = $_GET['id'];
$m_id = $_GET['id'];
$s = $id . "_horoscope_" . time() . ".jpg";
//echo $id. "_photo_" . time().".jpg";
//die;
if ($_FILES["horoscope"]["error"] > 0) {
    echo "Return Code: " . $_FILES["horoscope"]["error"] . "<br />";
} else {
    //echo "attachments: " . $_FILES["image"]["$s"] . "<br />";
    //echo "Type: " . $_FILES["image"]["type"] . "<br />";
    //echo "Size: " . ($_FILES["image"]["size"] / 1024) . " Kb <br />";
    //echo "Temp file: " . $_FILES["image"]["tmp_name"] . " <br />";
    //var_dump($_FILES["image"]);
    if ($_FILES["horoscope"]["type"] != 'image/jpeg') {
        echo "Image should be in jpeg format";
        die;
    } else {
        if (file_exists("../attachments/" . $s)) {
            echo $_FILES["horoscope"]["name"] . " already exists. ";
        } else {
            move_uploaded_file($_FILES["horoscope"]["tmp_name"], "../attachments/" . $s);
            // echo "Stored in:" . "attachments/" . $s;
            $attachment_path = "../attachments/" . $s;
            ?>
            <div class="callout callout-danger">
                <h4>Upload success!</h4>
            </div>
            <?php
        }
    }
//$image=$_FILES["image"]["$s"];
//echo $image;
   
//$sql = "UPDATE `attachments` SET `horoscope`='$s' where `id`=$id";
    $type = "horoscope";
    $sql = "INSERT INTO `$tbl_attachments`(`m_id`,`type`,`file_name`,`path`) VALUES ('$m_id','$type','$s','$attachment_path')";
    if (!mysql_query($sql, $con)) {
        die('Error: ' . mysql_error());
    }
//echo "Successfully photo Updated";
}
?>