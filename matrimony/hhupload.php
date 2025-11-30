<?php
$id = $_GET['id'];
$s = $id . "_horo";
include('../popupheader.php');
?>
<section class="content-header">
    <h1>
        Upload your Horoscope
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">
            <form action="hhhupload.php?id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
                <label for="image">select your Horoscope:</label>
                <input type="file" name="horo" id="image" />
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