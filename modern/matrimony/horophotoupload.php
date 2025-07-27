<?php
$id = $_GET['id'];
$m_id = $_GET['id'];
$s = $id . "_photo";
include('../popupheader.php');
?>
<section class="content-header">
    <h1>
        Upload your Photo 
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">
            <form action="photoupload.php?id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
                <label for="photo">select your photo:</label>
                <input type="file" name="photo" id="photo" />
                <input type="hidden" class="form-control" id="inputusername3" name="m_id" value="<?php echo $m_id ?>" >
                <br>
                <br>
                <input type="submit" name="submit" value="Submit" />
                <button type="button" onclick="window.close()">Cancel</button>

            </form>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>