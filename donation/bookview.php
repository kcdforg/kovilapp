<?php
include('../header.php');

$event_id = $_GET['event_id'];
$result = list_receipt();

$row1 = get_event($event_id);

$row2 = get_book($event_id);
?>
<div class="box box-primary">
    <div class="box-body box-profile">
        <div  class="col-sm-12">
            <div class="col-sm-3" >
                <b>Event Name :</b> <span><?php echo $row1['event_name'] ?></span>
            </div>
            <div class="col-sm-9">
                <b>Book No :</b> <span><?php echo $row2['book_no'] ?></span>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>


<div class="container-fluid">
    <h2 class="container text-center">Book View</h2>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-12">	 
            <div class="box table-responsive">
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr> 
                                <th>Receipt No</th>						
                                <th>Personal Details</th>
                                <th>Mobile No</th>						
                                <th>Date</th>		
                                <th>Amount</th>				
                                <th>Action</th>			 		
                            </tr>		
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysql_fetch_array($result)) {
                                ?>
                                <tr>          
                                    <td class="col-md-1"><?php echo $row['rec_no'] ?></td>
                                    <td class="col-md-2"><?php echo $row['name'] ?><br>S/O <?php echo $row['father_name'] . "<br>" . $row['address'] ?></td>
                                    <td class="col-md-1"><?php echo $row['mobile_no'] ?></td>
                                    <td class="col-md-1"><?php echo $row['date'] ?></td>			
                                    <td class="col-md-1"><?php echo $row['amount'] ?></td>
                                    <td width="10%"><a href="#" id="a" onclick="updatereceipt(<?php echo $row['event_id'] ?>)"><span class="glyphicon glyphicon-edit"></span></a>  <a href="#" id="a" onclick="deletereceipt(<?php echo $row['event_id'] ?>)"> <span class="glyphicon glyphicon-trash"></span></a>	
                                </tr>
                            <script>
                                function updatereceipt(id)
                                {
                                    url = "updatereceipt.php?id=" + id;
                                    title = "popup";
                                    var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
                                }
                            </script>
                            <script>
                                function deletereceipt(id)
                                {
    //alert("Do you want to upload husband photo?");
                                    url = "dltreceipt.php?id=" + id;
                                    title = "popup";
                                    var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
                                }
                            </script>
    <?php
}
mysql_close($con);
?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>	  
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>					  
<div style="clear:both"></div>  
<?php
include('../footer.php');
?>