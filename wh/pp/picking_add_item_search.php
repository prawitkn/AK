<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; ?>  
<?php include 'inc_helper.php'; ?>      
   
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
		Product Stock Info
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main</a></li>
        <li class="active">Product Stock Info</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
      <!-- Your Page Content Here -->
	  
	
	
	<!-- Main row -->
      <div class="row">
		<div class="col-md-12">
			
			<!-- TABLE: LATEST ORDERS -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Available Item Stock</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<form id="form1" action="" method="post" class="form" novalidate>
			<?php
				$pickNo=$_GET['pickNo'];
				$code=$_GET['code'];
				$sql = "
				SELECT dtl.`prodCode`, dtl.`issueDate`, dtl.`grade`, dtl.`qty`
				, IFNULL(SUM(dtl.`packQty`),0) as packQty, IFNULL(SUM(dtl.`qty`),0) as total
				, dtl.Remark as sendingRemark 
				FROM `receive` hdr 
				INNER JOIN receive_detail dtl on dtl.rcNo=hdr.rcNo  
				WHERE 1=1
				AND hdr.statusCode='P' 
				AND dtl.prodCode=:prodCode 
				AND dtl.isReturn is NULL 
				
				GROUP BY dtl.`prodCode`, dtl.`issueDate`, dtl.`grade`, dtl.Remark
								
				ORDER BY dtl.`issueDate` DESC 
				";
				$result = mysqli_query($link, $sql);
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':prodCode', $code);
				$stmt->execute();	
					
				?>
				<input type="hidden" name="pickNo" value="<?=$pickNo;?>" />				
				<input type="hidden" name="prodCode" value="<?=$code;?>" />		
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
					<th>No.</th>
                    <th>ProdCode</th>
					<th>issue Date</th>
					<th>Grade</th>
					<th>Sending Remark</th>
					<th>Per Pack</th>
                    <th style="color: blue;">Pack</th>
					<th style="color: blue;">Total</th>				
                    <th>Pick Qty</th>
					<th>#</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php $row_no = 1; while ($row = $stmt->fetch()) { 
				?>
                  <tr>
					<td><?= $row_no; ?></td>
					<td><?= $row['prodCode']; ?></td>					
					<td><?= $row['issueDate']; ?></td>
					<td><?= $row['grade']; ?></td>
					<td><?= $row['sendingRemark']; ?></td>
					<td><?= $row['qty']; ?></td>
					<td style="color: blue;"><?= $row['packQty']; ?></td>
					<td style="color: blue;"><?= $row['total']; ?></td>		
					<td><input type="textbox" name="pickQty" class="control" value=""  /></td>
					<td><a href="#" name="btn_row_submit" class="btn btn-defalt" 
					data-prodCode="<?=$row['prodCode'];?>" data-issueDate="<?=$row['issueDate'];?>" 
					data-grade="<?=$row['grade'];?>" > Add</a></td>
                </tr>
                <?php $row_no+=1; } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
				<a name="btn_submit" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> Submit</a>
            </div>
            <!-- /.box-footer -->
			</form>
			<!--form-->
          </div>
          <!-- /.box -->
		  
		  </div>
		  <!-- col-md-12 -->
		  
      </div>
      <!-- /.row  -->
	   	  
	<div id="spin"></div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include'footer.php'; ?>  
  
</div>
<!-- ./wrapper -->
</body>

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- Add Spinner feature -->
<script src="bootstrap/js/spin.min.js"></script>
<!-- Add smoke dialog -->
<script src="bootstrap/js/smoke.min.js"></script>

<script> 
// to start and stop spiner.  
$( document ).ajaxStart(function() {
	$("#spin").show();
}).ajaxStop(function() {
	$("#spin").hide();
});
		
		
$(document).ready(function() { 
	var spinner = new Spinner().spin();
	$("#spin").append(spinner.el);
	$("#spin").hide();
				
	
	
	$('a[name=btn_row_submit]').click (function(e) {
		var params = {
			pickNo: $('input[name=pickNo]').val(),
			prodCode: $('input[name=prodCode]').val(),
			issueDate: $(this).attr('data-issueDate'),
			grade: $(this).attr('data-grade'),
			pickQty: $(this).closest("tr").find('input[name="pickQty"]').val()
		};
		//alert(params.pickQty);
		if ($('#form1').smkValidate()){
			$.smkConfirm({text:'Are you sure to Submit ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
				$.post({
					url: 'picking_add_item_search_row_submit_ajax.php',
					data: params,
					dataType: 'json'
				}).done(function(data) {
					if (data.success){  
						$.smkAlert({
							text: data.message,
							type: 'success',
							position:'top-center'
						});
						window.location.href = "picking_add.php?pickNo=" + params.pickNo;
					}else{
						$.smkAlert({
							text: data.message,
							type: 'danger',
							position:'top-center'
						});
					}
					//e.preventDefault();		
				}).error(function (response) {
					alert(response.responseText);
				});
				//.post
			}});
			//smkConfirm
		e.preventDefault();
		}//.if end
	});
	//.btn_click
	
	
	$('#form1 a[name=btn_submit]').click (function(e) {
		alert('big');
		if ($('#form1').smkValidate()){
			$.smkConfirm({text:'Are you sure to Submit ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
				$.post({
					url: 'delivery_add_item_search_submit_ajax.php',
					data: $("#form1").serialize(),
					dataType: 'json'
				}).done(function(data) {
					if (data.success){  
						$.smkAlert({
							text: data.message,
							type: 'success',
							position:'top-center'
						});
						window.location.href = "delivery_add.php?doNo=" + data.doNo;
					}else{
						$.smkAlert({
							text: data.message,
							type: 'danger',
							position:'top-center'
						});
					}
					//e.preventDefault();		
				}).error(function (response) {
					alert(response.responseText);
				});
				//.post
			}});
			//smkConfirm
		e.preventDefault();
		}//.if end
	});
	//.btn_click
});
</script>

</html>
