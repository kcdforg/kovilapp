<?php
include('../header.php');
//$start_from = ($page-1) * $num_rec_per_page; 
$result = get_labels();
/* $query = "SELECT count(*) AS total FROM $users"; 
  $count = mysql_query($query);
  $values = mysql_fetch_assoc($count);
  $num_rows = $values['total'];
  echo "<b>Total no.of users=$num_rows</b>"; */
?> 			
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <h2 class="container text-center">Label List
                <button class="btn btn-info pull-right" onclick="addlabel()"> Add Label</button></h2>
        </div>       
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box table-responsive">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>                 
                                    <th class="col-sm-1">Display Name </th>
                                    <th class="col-sm-1">Slug</th>
                                    <th class="col-sm-1">Type</th>
                                    <th class="col-sm-1">Category</th>
                                    <th class="col-sm-1">Parent id</th>
                                    <th class="col-sm-1">Order</th>
                                    <th width="10%">Action</th>					
                                </tr>		
                            </thead>
                            <tbody>
                                <?php
                                if($result){
                                    
                              foreach($result as $k => $row){
                                    ?>
                                    <tr>          
                                        <td><?php echo $row['display_name'] ?></td>
                                        <td><?php echo $row['slug'] ?></td>
                                        <td><?php echo $row['type_name'] ?></td>
                                        <td><?php echo $row['category'] ?></td>
                                        <td><?php echo $row['parent_id'] ?></td>
                                        <td><?php echo $row['order'] ?></td>
                                        <td>  <a id="a" href="javascript:void(0);"   onclick="updatelabel(<?php echo $row['id'] ?>)" ><span class="glyphicon glyphicon-edit"></span></a>  <a href="#" id="a" onclick="deletelabel(<?php echo $row['id'] ?>)"> <span class="glyphicon glyphicon-trash"></span></a>
                                    </tr>
                               
                                <?php
                                }}
                            
                            ?>
 <script>
                                    function deletelabel(id)
                                    {
                                        url = "dltlabel.php?id="+id;
                                        title = "popup";
                                        var newWindow = window.open(url, title, 'scrollbars=yes, width=800 height=400');
                                    }
                                </script> 
                                <script>
                                    function addlabel()
                                    {
                                        url = "addlabel.php";
                                        title = "popup";
                                        var newWindow = window.open(url, title, 'scrollbars=yes, width=800 height=400');
                                    }
                                </script> 
                                <script>
                                    function updatelabel(id)
                                    {
                                        url = "updatelabel.php?id="+id;
                                        title = "popup";
                                        var newWindow = window.open(url, title, 'scrollbars=yes, width=800 height=400');
                                    }
                                </script> 


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
</div>

<?php
include('../footer.php');
?>


