<?php
include('../header.php');

$event_id = $_GET['event_id'];
$row = get_event($event_id);

$result = list_book();
?>

<div class="container-fluid">
    <h2 class="container text-center">Event Details</h2>
</div>
<div class="box box-primary">
    <div class="box-body box-profile">
        <div  class="col-sm-12">
            <div class="col-sm-5" style="padding-left:120px">
                <b>Event Name :</b> <span><?php echo $row['event_name'] ?></span>
            </div>
            <div class="col-sm-4">
                <b>Date :</b> <span><?php echo $row['date'] ?></span>
            </div>
            <div class="col-sm-3">
                <b>Location :</b> <span><?php echo $row['location'] ?></span>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>

<section id="sec" class="content">
    <div id="fluid" class="container-fluid">
        <h2 class="container text-center">Book List
            <a class="btn btn-info pull-right" onclick="addbook()">Add Book</a></h2>
    </div>
    <script>
        function addbook()
        {
            url = "addbook.php?event_id=<?php echo $event_id ?>";
            title = "popup";
            var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
        }
    </script>
    <div class="row">
        <div class="col-md-12">	 
            <div class="box table-responsive">
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>       
              
                                <th>Book no</th>
                                <th>Rec Starting.no</th>	
                                <th>Rec Ending.no</th>	
                                <th>Denom</th>						
                                <th>Amount Collected </th>
                                <th>Issued to</th>				
                                <th>Action</th>			 		
                            </tr>		
                        </thead>
                        <tbody>
                            <?php
                            while ($rowbook = mysql_fetch_array($result)) {
                                ?>
                                <tr>          
                                    <td class="col-md-1"><?php echo $rowbook['book_no'] ?></td>
                                    <td class="col-md-1"><?php echo $rowbook['rec_start_no'] ?></td>
                                    <td class="col-md-1"><?php echo $rowbook['rec_end_no'] ?></td>
                                    <td class="col-md-1"><?php echo $rowbook['denom'] ?></td>
                                    <td class="col-md-1"><?php echo $rowbook['collected_amount'] ?></td>			
                                    <td class="col-md-2"><?php echo $rowbook['issued_to'] ?></td>
                                    <td width="10%"><a id="a" href="bookview.php?event_id=<?php echo $event_id ?>"><span class="glyphicon glyphicon-eye-open"></span></a><a href="#" id="a" onclick="updatebook(<?php echo $rowbook['book_id'] ?>)"><span class="glyphicon glyphicon-edit"></span></a>  <a href="#" id="a" onclick="deletebook(<?php echo $rowbook['book_id'] ?>)" > <span class="glyphicon glyphicon-trash"></span></a>	
                                </tr>
                            <script>
                                function updatebook(id)
                                {
                                    url = "updatebook.php?book_id=" + id;
                                    title = "popup";
                                    var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
                                }
                            </script>
                            <script>
                                function deletebook(id)
                                {
                                    //alert("Do you want to upload husband photo?");
                                    url = "dltbook.php?book_id=" + id;
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