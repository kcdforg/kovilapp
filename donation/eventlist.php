<?php
include('../header.php');

//$start_from = ($page-1) * $num_rec_per_page; 
$result =list_event();
?> 			
<br>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <h2 class="container text-center">Event List
            <button class="btn btn-info pull-right" onclick="addevent()"> Add Event</button></h2>
    </div>
</section>	
<script>
    function addevent()
    {
        url = "addevent.php";
        title = "popup";
        var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
    }
</script>
<!-- Main content -->
<section class="content">	 
    <div class="row">
        <div class="col-md-12">		 
            <div class="box table-responsive">
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>           
                                <th>Event name </th>
                                <th>Date</th>	
                                <th>No.of books</th>	
                                <th>Amount</th>	
                                <th>Location</th>	
                                <th>Action</th>			 		
                            </tr>		
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysql_fetch_array($result)) {
                                ?>
                                <tr>          
                                    <td class="col-md-2"><?php echo $row['event_name'] ?></td>
                                    <td class="col-md-1"><?php echo $row['date'] ?></td>
                                    <td class="col-md-1"><?php echo $row['no_of_books'] ?></td>
                                    <td class="col-md-1"><?php echo $row['amount'] ?></td>
                                    <td class="col-md-2"><?php echo $row['location'] ?></td>
                                    <td width="10%"><a id="a" href="eventview.php?event_id=<?php echo $row['event_id'] ?>"><span class="glyphicon glyphicon-eye-open"></span></a><a href="#" id="a" onclick="updateevent(<?php echo $row['event_id'] ?>)"><span class="glyphicon glyphicon-edit"></span></a>  <a href="#" id="a" onclick="deleteevent(<?php echo $row['event_id'] ?>)"> <span class="glyphicon glyphicon-trash"></span></a>	
                                </tr>
                            <script>
                                function deleteevent(id)
                                {
                                    //alert("Do you want to upload husband photo?");
                                    url = "dltevent.php?event_id=" + id;
                                    title = "popup";
                                    var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
                                }
                            </script>
                            <script>
                                function updateevent(id)
                                {
                                    url = "updateevent.php?event_id=" + id;
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
<!-- /.content -->
<?php
include('../footer.php');
?>