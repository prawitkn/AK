<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; ?>
<?php include 'inc_helper.php'; ?>

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->	
    <section class="content-header">
		<?php
			$rtNo = $_GET['rtNo'];
			$refNo = $_GET['refNo'];
		?>
      <h1>
       Return
        <small>Return management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main Menu</a></li>
        <li class="active">Return</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
    <a href="rt_add.php?rtNo=<?=$rtNo;?>" class="btn btn-google">Back</a>
    <div class="box box-primary">
		<?php	
			
			$reason = mysqli_query($link,"SELECT code, name FROM wh_return_reason_type WHERE statusCode='A' ");	
			
		
			$sql = "SELECT hdr.* 
			FROM receive hdr 
			WHERE hdr.statusCode='P' AND hdr.rcNo=:rcNo 
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':rcNo', $refNo);	
			$stmt->execute();
			$hdr = $stmt->fetch();
			
		
			
			

		?>
        <div class="box-header with-border">
        <h3 class="box-title">Return No. : <?=$rtNo;?></h3>
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
         
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">			
       		
			<?php
					$sql = "SELECT count(*) as countTotal 
					FROM receive_detail dtl 
					WHERE rcNo=:rcNo 
					";						
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':rcNo', $refNo);
					$stmt->execute();	
					$row = $stmt->fetch();
					$countTotal = $row['countTotal'];
			  ?>
			<div class="row" <?php echo ($countTotal==0?' style="display: none;" ':''); ?>  >
				<div class="box-header with-border">
				<h3 class="box-title">Product List From RC No. : <?=$refNo;?></h3>
				
				
				
				<div class="box-tools pull-right">
				  <!-- Buttons, labels, and many other things can be placed here! -->
				  <!-- Here is a label for example -->
				  
				  <span class="label label-primary">Total <?php echo $countTotal; ?> items</span>
				</div><!-- /.box-tools -->
				</div><!-- /.box-header -->
				<div class="box-body">
					<form id="form2" action="" method="post" class="form" novalidate>
						<input type="hidden" name="rtNo" value="<?=$rtNo;?>" />
					<?php
						$sql = "SELECT dtl.`id`, dtl.`prodItemId`, dtl.`prodId`, dtl.`prodCode`, dtl.`barcode`, dtl.`issueDate`
						, dtl.`machineId`, dtl.`seqNo`, dtl.`NW`, dtl.`GW`, dtl.`qty`, dtl.`packQty`, dtl.`grade`, dtl.`gradeDate`
						, dtl.`refItemId`, dtl.`itemStatus`, dtl.`remark`, dtl.`problemId`, dtl.`shelfCode`, dtl.`rcNo`
						FROM receive_detail dtl 
						WHERE dtl.rcNo=:rcNo 
								";
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':rcNo', $refNo);		
						$stmt->execute();
						//$result = sqlsrv_query($ssConn, $sql);
						//$countTotal = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
					?>
					<div class="table-responsive">
					<table id="tbl_items" class="table table-striped">
						<tr>
							<th><input type="checkbox" id="checkAll" checked />Select All</th>
							<th>No.</th>
							<th>Barcode</th>
							<th>Qty</th>
							<th>Reason</th>
							<th>Reason Remark</th>
						</tr>
						<?php $row_no=1; while ($row = $stmt->fetch()) { mysqli_data_seek($reason, 0);
						?>
						<tr>
							<td><input type="checkbox" name="prodItemId[]" value="<?=$row['id'];?>" checked /></td>
							<td><?= $row_no; ?></td>
							<td><?= $row['barcode']; ?></td>
							<td><?= $row['qty']; ?></td>
							<td>
								<select name="returnReasonCode[]" class="form-control" name="division_code">
									<option value="">- - ระบุ --</option>
									<?php 
									   $rank_code = "";							   
									   
									   while($r = mysqli_fetch_array($reason)) {
										   $selected = '';
										   if( ($rank_code == $r['code']) ) {
											   $selected = "selected";
										   }
										  echo '<option value="'.$r['code'].'" '.$selected.' >'.$r['code'].' : '.$r['name'].'</option>';									
										}
									?>
									</select>
							</td>
							<td><input type="text"  class="form-control" name="returnReasonRemark[]" /></td>
						</tr>
						<?php $row_no+=1; } ?>
					</table>
					</div>
					<!--/.table-responsive-->
					<a name="btn_submit" href="#" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> Submit</a>
					
					
				</div>
				<!--/box-body-->
			</div>
			<!--/.row table-responsive-->
			
		</div>
		<!-- form-->
		
    </div><!-- /.box-body -->
  <div class="box-footer">
      
      
    <!--The footer of the box -->
  </div><!-- box-footer -->
</div><!-- /.box -->

<div id="spin"></div>

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

<!-- Add Spinner feature -->
<script src="bootstrap/js/spin.min.js"></script>


<script> 
// to start and stop spiner.  
$( document ).ajaxStart(function() {
$("#spin").show();
}).ajaxStop(function() {
	$("#spin").hide();
});
//   

$(document).ready(function() {
//       alert("jquery ok");
	$("#custName").focus();
	
// Append and Hide spinner.          
	var spinner = new Spinner().spin();
	$("#spin").append(spinner.el);
	$("#spin").hide();
  //           

		
			
	$('#form2 a[name=btn_submit]').click (function(e) {
		if ($('#form2').smkValidate()){
			$.smkConfirm({text:'Are you sure to Submit ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
				$.post({
					url: 'rt_add_search_prod_submit_ajax.php',
					data: $("#form2").serialize(),
					dataType: 'json'
				}).done(function(data) {
					if (data.success){  
						$.smkAlert({
							text: data.message,
							type: 'success',
							position:'top-center'
						});
						window.location.href = "rt_add.php?rtNo=<?=$rtNo;?>";
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
	
	$("#checkAll").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});

	
	$("html,body").scrollTop(0);
	$("#statusName").fadeOut('slow').fadeIn('slow').fadeOut('slow').fadeIn('slow');
	
	$('#txt_row_first').select();
	
});
        
        
   
  </script>
  
  <link href="bootstrap-datepicker-custom-thai/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
    <script src="bootstrap-datepicker-custom-thai/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="bootstrap-datepicker-custom-thai/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
  
<script>
	$(document).ready(function () {
		$('.datepicker').datepicker({
			daysOfWeekHighlighted: "0,6",
			autoclose: true,
			format: 'dd/mm/yyyy',
			todayBtn: true,
			language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
			thaiyear: true              //Set เป็นปี พ.ศ.
		});  
				
		<?php if(isset($searchFromDate)){ ?>
		//กำหนดเป็น วันที่จากฐานข้อมูล
		var queryDate = '<?= $searchFromDate;?>',
		dateParts = queryDate.match(/(\d+)/g)
		realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]); 
		$('#searchFromDate').datepicker('setDate', realDate);
		//จบ กำหนดเป็น วันที่จากฐานข้อมูล
		<?php } ?>
		
		<?php if(isset($searchToDate)){ ?>
		//กำหนดเป็น วันที่จากฐานข้อมูล
		var queryDate = '<?= $searchToDate;?>',
		dateParts = queryDate.match(/(\d+)/g)
		realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]); 
		$('#searchToDate').datepicker('setDate', realDate);
		//จบ กำหนดเป็น วันที่จากฐานข้อมูล
		<?php } ?>
		
	});
</script>




<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>



<!--Integers (non-negative)-->
<script>
  function numbersOnly(oToCheckField, oKeyEvent) {
    return oKeyEvent.charCode === 0 ||
        /\d/.test(String.fromCharCode(oKeyEvent.charCode));
  }
</script>

<!--Decimal points (non-negative)-->
<script>
  function decimalOnly(oToCheckField, oKeyEvent) {        
    var s = String.fromCharCode(oKeyEvent.charCode);
    var containsDecimalPoint = /\./.test(oToCheckField.value);
    return oKeyEvent.charCode === 0 || /\d/.test(s) || 
        /\./.test(s) && !containsDecimalPoint;
  }
</script>