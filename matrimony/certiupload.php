<?php
$id = $_GET['id'];
$s = $id . "_photo";
$m_id = $_GET['id'];
include('../popupheader.php');
?>
<section class="content-header">
    <h1>
        Upload your Certificate 
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">
            <form action="certifupload.php?id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
                <label for="photo">select your Certificate:</label>
                <input type="file" name="certificate" id="certificate" />
                <input type="hidden" class="form-control" id="inputusername3" name="m_id" value="<?php echo $m_id; ?>">
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
<?php
include ('../footer.php');
?>