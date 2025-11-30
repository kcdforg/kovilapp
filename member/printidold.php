<?php
include('../popupheader.php');
$id = $_GET['id'];

$row = get_member($id);
?>
<style>

    @media (max-width: 1200px) 
    {
        #idimage.img-responsive   {
            display:block;
            height:120px;
            width:120px;
            margin-left: 150px;          
        }
        .idview {
            padding-left: 18%;
            padding-right:18%;
        }

        #idfield{
            width:50%;
            font-size: 14px;
            text-align: right;
            padding-left: 0px;
            float:left;
        }
        #iddata{
            width:50%;
            font-size: 14px;
            text-align: left;
            float:right;
        }
        #idlist.list-group-item
        {
            border:0px;
        }
    }

    #idimage.img-responsive   {
        display:block;
        height:120px;
        width:120px;
        margin-left: 35%;          
    }
    #idfield{
        width:50%;
        font-size: 13px;
        text-align: right;
        padding-left: 0px;
        float:left;
    }
    #iddata{
        width:50%;
        font-size: 13px;
        text-align: left;
        float:right;
    }
    #idlist.list-group-item
    {
        border:0px;
    }



</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content">
        <div class="idview">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="container-fluid">
                        <h5 class="container text-center"><b> சங்ககிரி கொங்கு வெள்ளாளக் கவுண்டர்கள் சங்கம்</b></h5>
                    </div>
                    <img id="idimage" class="img-responsive" src="../images/<?php echo $row['image'] ?>"  /><br>
                    <ul class="list-group list-group-unbordered">
                        <li id="idlist" class="list-group-item">
                            <div class="row"> <div  id="idfield" class="col-sm-7" > <label> <b> உறுப்பினர் எண் : </b> </label> </div>  <div  id="iddata" class="col-sm-5"><?php echo $row['member_id'] ?></div> </div>                       
                        </li>
                        <li id="idlist" class="list-group-item">
                            <div class="row"> <div  id="idfield" class="col-sm-5" > <label> <b>பெயர் :</b> </label> </div>  <div  id="iddata" class="col-sm-5"><?php echo $row['name'] ?> </div></div>                       
                        </li>
                        <li id="idlist" class="list-group-item">
                            <div class="row"> <div  id="idfield" class="col-sm-7" > <label> <b>வயது :</b> </label> </div>  <div  id="iddata" class="col-sm-5"><?php echo $row['age'] ?> </div></div>                       
                        </li>
                        <li id="idlist" class="list-group-item">
                            <div class="row"> <div  id="idfield" class="col-sm-7" > <label> <b>ஊர் :</b> </label> </div>  <div  id="iddata" class="col-sm-5"><?php echo $row['village'] ?> </div></div>                       
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </section>
</div>
