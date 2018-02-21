<?php
    include '../db/database.php';
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; ?>
    
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Users
        <small>User management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main Menu</a></li>
        <li class="active">User Information</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <a href="frm_user.php" class="btn btn-google">Add User</a>
    <div class="box box-primary">
        <div class="box-header with-border">
        <h3 class="box-title">User List</h3>
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
          <?php
                $sql_user = "SELECT COUNT(*) AS COUNTUSER FROM user";
                $result_user = mysqli_query($link, $sql_user);
                $count_user = mysqli_fetch_assoc($result_user);
          ?>
          <span class="label label-primary">Total <?php echo $count_user['COUNTUSER']; ?> items</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
           <?php
                $sql = "SELECT * FROM user ORDER BY userID ASC";
                $result = mysqli_query($link, $sql);
                
           ?> 
            
            <table class="table table-striped">
                <tr>
                    <th>User ID</th>
                    <th>User Full Name</th>
                    <th>UserName</th>
                    <th>UserEmail</th>
                    <th>UserTelephone</th>
                    <th>Active</th>
                    <th>Delete</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td>
                         <?= $row['userID']; ?>
                    </td>
                    <td>
                         <?= $row['userFullname']; ?>
                    </td>
                    <td>
                         <?= $row['userName']; ?>
                    </td>
                    <td>
                         <?= $row['userEmail']; ?>
                    </td>
                    <td>
                         <?= $row['userTel']; ?>
                    </td>
                    <td>
                         <?= $row['isActive']; ?>
                    </td>
                    <td>
                        <a class="btn_del" href="#" id="<?= $row['userID']; ?>"><i class="fa fa-trash"></i></a>                     
                   
                    </td>
                </tr>
                <?php } ?>
            </table>
    
    </div><!-- /.box-body -->
  <div class="box-footer">
      
      
    <!--The footer of the box -->
  </div><!-- box-footer -->
</div><!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include'footer.php'; ?>
  
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>

<script src="bootstrap/js/smoke.min.js"></script>
<script>
        $( document ).ready(function() {
            $("a.btn_del").on("click",function(e) {
                var row_id = $(this).attr('id');
                $.smkConfirm({text:'Are you sure you want to delete data?', accept:'Delete', cancel:'cancel'}, function(e){if(e){ 
                    window.location.replace('del_user.php?id='+row_id);
                }});
                e.preventDefault();
                
             });
         });
         
         
         
         
         
         
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
