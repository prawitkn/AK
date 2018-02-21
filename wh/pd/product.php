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
      Products
        <small>Product management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main Menu</a></li>
        <li class="active">Product Information</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

<!-- To allow only admin to access the content -->        
        <?php 
            if ($s_admin != 'admin') {
                echo 'You are not permitted to access this level.';
       
            } else {
 // Closing Bracket of Else is on line 143                
?>
        
        
      <!-- Your Page Content Here -->
      <a href="frm_product.php" class="btn btn-google">Add Products</a>
    <div class="box box-primary">
        <div class="box-header with-border">
        <h3 class="box-title"> Product List</h3>
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
          <?php
                $sql_prod = "SELECT COUNT(*) AS COUNTPRODUCT FROM product";
                $result_prod = mysqli_query($link, $sql_prod);
                $count_prod = mysqli_fetch_assoc($result_prod);
          ?>
          <span class="label label-primary">Total <?php echo $count_prod['COUNTPRODUCT']; ?> items</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            
            <button id="remove" class="btn btn-danger" disabled><i class="glyphicon glyphicon-remove"></i>  Erase Product!</button>
            
               <table id="table" data-toggle="table" data-url="product_json.php"
                    
                      data-pagination = "true"
                      data-page-size = "10"
                      data-page-list = "[10,20,40,ALL]"
                      data-search = "true"
                      data-height = "700"
                      data-show-refresh = "true"
                      data-show-toggle = "true"
                      data-show-columns = "true"
                      data-toolbar-align ="right"
                      data-pagination-v-align ="top"
                      data-show-footer ="true"
                      data-detail-view= "true"
                      data-toolbar ="#remove"
                      data-id-field ="prodID"
                >      
                      
                 <thead>
                     <tr>
                        <th data-field="state" data-checkbox="true"></th> 
                        <th data-field="prodID" data-align="center" data-sortable="true">Product ID</th>
                        <th data-field="prodName">Product Name</th>
                        <th data-field="prodPrice" data-sortable="true" data-formatter="priceFormatter">Price</th>
                        <th data-field="prodTypeName" data-sortable="true">Product Type</th>
                        <th data-field="operate" data-align="center" data-events="operateEvents" data-formatter="operateFormatter">Tools</th>
                     </tr>
                 </thead>
               </table> 
       
 <!--           <script type="text/javascript" src="jquery.dataTables.js"></script>
                <script type="text/javascript" src="dataTables.numericComma.js"></script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#table').dataTable( {
                            "prodPrice": [
                                { "type": "numeric-comma", targets: 3 }
                            ]
                        } );
                    } );
                </script>
 -->           
    
    </div><!-- /.box-body -->
  <div class="box-footer">
      
      
    <!--The footer of the box -->
  </div><!-- box-footer -->
</div><!-- /.box -->

  <!-- Closing of above If/Else to access the content about line # 62-65.   -->
          <?php } ?>

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

<script src="bootstraptable/bootstrap-table.min.js"></script>

<script>
   var $table = $('#table');
   var $remove = $('#remove');
        $(function() {
            $table.on('check.bs.table uncheck.bs.table ' +
                'check-all.bs.table uncheck-all.bs.table', function () {
                    $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
            }); 
              
            $remove.click(function() {
                $.smkConfirm({text:'Are you sure you want to delete?',accept:'OK Sure.', cancel:'Do not Delete.'}, function (e){if(e){
                    var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
                            return row.prodID;
                        });
                      
        //test whether we get prodID as ids
        //             alert(ids); 
                     
        //ajax 1:22
               $.get("del_product.php", { "prodID[]": ids } )
                              .done(function(data) {
    //                         alert(data.status); 
                                   if (data.status === "success"){                  
                                
                                       $.smkAlert({
                                        text: data.message,
                                        type: data.status,
                                        position:'top-center'
                                        });
                                 } else {
                                        $.smkAlert({
                                        text: data.message,
                                        type: data.status,
       //                                 position:'top-center'
                                        });
                                 }
                              
                                    $table.bootstrapTable('refresh');
                                  });  
        
                                 
          
                                 
         
           
           //  make remove button disable after erase.        
                     $remove.prop('disabled', true);
           // uncheck all checkboxes.          
                     $table.bootstrapTable('togglePagination').bootstrapTable('uncheckAll').bootstrapTable('togglePagination');
                }});
            });
                
        
        });
        
        
        
 // Day7 1:35:35 placing comma in value of thousand. 
             function priceFormatter(value){
              return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              
              }
              
               function operateFormatter(value, row, index) {
                    return [
                        '<a class="edit" href="javascript:void(0)" title="Edit Data">',
                        '<i class="glyphicon glyphicon-pencil"></i>',
                        '</a>  '
                    
                    ].join('');
              }
// Day7 @ 1:44:20 OK
              window.operateEvents = {
                        'click .edit': function (e, value, row, index) {
                            alert('You click like action, row: ' + [row.prodID]);
                            window.location.replace('frm_edit_product.php?prodID='+ [row.prodID]);
                        }
                        
                };
 //       }
 //   };
</script>

</body>
</html>
