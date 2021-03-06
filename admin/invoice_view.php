<?php
  //  include '../db/database.php';
  include 'inc_helper.php';
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; 

$rootPage="invoice";	


$invNo = $_GET['invNo'];
		
$sql = "SELECT hdr.`invNo`, hdr.`doNo`, hdr.`refNo`, hdr.`invoiceDate`, hdr.`custCode`, hdr.`smCode`, hdr.`totalExcVat`
, hdr.`vatAmount`, hdr.`totalIncVat`, hdr.`remark`, hdr.`statusCode`, hdr.`createTime`, hdr.`createById`
, hdr.`updateTime`, hdr.`updateById`, hdr.`confirmTime`, hdr.`confirmById`, hdr.`approveTime`, hdr.`approveById`
, ct.custName, ct.custAddr, ct.taxId, ct.creditDay 
, concat(sm.name, '  ', sm.surname) as smFullname 
, dh.remark as delivery_remark 
, dh.soNo, sh.poNo 
, uca.userFullname as createByName, ucf.userFullname as confirmByName, uap.userFullname as approveByName
FROM invoice_header hdr 	
INNER JOIN  delivery_header dh on dh.doNo=hdr.doNo 			
INNER JOIN  prepare pa on pa.ppNo=dh.ppNo 				
INNER JOIN  picking pi on pi.pickNo=pa.pickNo
INNER JOIN sale_header sh on sh.soNo=pi.soNo 
LEFT JOIN customer ct on ct.code=hdr.custCode ";
switch($s_userGroupCode){
	case 'it' : case 'admin' : 
		break;
	case 'sales' : $sql .= " AND ct.smCode=:s_smCode "; break;
	case 'salesAdmin' : 	$sql = " AND ct.smAdmCode=:s_smCode' "; break;
	default : 
		//return JSON
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'Access Denied.'));
		exit();
}		
$sql .= "
LEFT JOIN salesman sm on sm.code=hdr.smCode 
left join user uca on hdr.createByID=uca.userID
left join user ucf on hdr.confirmByID=ucf.userID
left join user uap on hdr.approveByID=uap.userID

WHERE hdr.invNo=:invNo AND hdr.createByID=:s_userID

		";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':s_userID', $s_userID);	
$stmt->bindParam(':invNo', $invNo);
switch($s_userGroupCode){
	case 'it' : case 'admin' : 
		break;
	case 'sales' : $stmt->bindParam(':s_smCode', $s_userID);
		break;
	case 'salesAdmin' : $stmt->bindParam(':s_smCode', $s_userID);
		break;
	default : 
}	
$stmt->execute();
$hdr = $stmt->fetch();
$invNo = $hdr['invNo'];
$doNo = $hdr['doNo'];
?>
    
    
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
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="plugins/iCheck/all.css"> 
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
       <h1><i class="glyphicon glyphicon-usd"></i>
       Invoice
        <small>Invoice management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=$rootPage;?>.php"><i class="glyphicon glyphicon-list"></i>Invoice List</a></li>
		<li><a href="<?=$rootPage;?>_add.php?invNo=<?=$invNo;?>"><i class="glyphicon glyphicon-edit"></i>Invoice No.<?=$invNo;?></a></li>
		<li><a href="#"><i class="glyphicon glyphicon-list"></i>View</a></li>
      </ol>
    </section>
	
	

    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
    <div class="box box-primary">
        <div class="box-header with-border">
			<input type="hidden" name="invNo" id="invNo" value="<?=$invNo;?>" />
			<h3 class="box-title">Invoice No : <b><?= $invNo; ?></b></h3>
			<div class="box-tools pull-right">
				<?php $statusName = '<b style="color: red;">Unknown</b>'; switch($hdr['statusCode']){
							case 'A' : $statusName = '<b style="color: orange;">Incomplete</b>'; break;
							case 'B' : $statusName = '<b style="color: blue;">Begin</b>'; break;
							case 'C' : $statusName = '<b style="color: blue;">Confirmed</b>'; break;
							case 'P' : $statusName = '<b style="color: green;">Approved</b>'; break;
							default : 
				} ?>
				<h3 class="box-title" id="statusName">Status : <?= $statusName; ?></h3>
			</div><!-- /.box-tools -->

        </div><!-- /.box-header -->
        <div class="box-body">
			<input type="hidden" id="invNo" value="<?= $invNo; ?>" />
            <div class="row">	
					<div class="col-md-4">
						Customer : <br/>
						<b><?= $hdr['custName']; ?></b><br/>
						<?= $hdr['custAddr']; ?>
					</div><!-- /.col-md-3-->	
					<div class="col-md-4">
						Customer TAX ID No. : 
						<b><?= $hdr['taxId']; ?></b><br/>
						Term of Payment : 
						<b><?= $hdr['creditDay']; ?></b> Days<br/>							
						Salesman : <br/>
						<b><?= $hdr['smFullname']; ?></b><br/>
					</div><!-- /.col-md-3-->						
					<div class="col-md-4">
						Invoice Date : <b><?= $hdr['invoiceDate']; ?></b><br/>
						DO No : <b><?= $hdr['doNo']; ?></b><br/>
						SO No : <b><?= $hdr['soNo']; ?></b><br/>			
						PO No : <b><?= $hdr['poNo']; ?></b><br/>	
					</div>	<!-- /.col-md-3-->	
			</div> <!-- row add items -->
		
			<div class="row"><!-- row show items -->
				<div class="box-header with-border">
				<h3 class="box-title">Item List</h3>
				<div class="box-tools pull-right">
				  <!-- Buttons, labels, and many other things can be placed here! -->
				  <!-- Here is a label for example -->
				  <?php
						$sql = "SELECT COUNT(id) as rowCount FROM invoice_detail`
								WHERE invNo=:invNo 
									";						
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':invNo', $hdr['invNo']);
						$stmt->execute();	
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
				  ?>
				  <span class="label label-primary">Total <?php echo $row['rowCount']; ?> items</span>
				</div><!-- /.box-tools -->
				</div><!-- /.box-header -->
				<div class="box-body">
				   <?php
						$sql = "
								SELECT dd.`prodCode`, dd.`salesPrice`, dd.`qty`, dd.`total`, dd.`discPercent`, dd.`discAmount`, dd.`netTotal`
								, p.prodName, p.prodDesc
								FROM `invoice_detail` dd
								LEFT JOIN product p on dd.prodCode=p.code
								WHERE 1
								AND dd.`invNo`=:invNo 
						";
						//$stmt = $pdo->prepare($sql);	
						//$stmt->bindParam(':invNo', $hdr['invNo']);
						//$stmt->execute();	
						$sql = "SELECT id.`id`, itm.`prodCode`, id.`salesPrice`, itm.`qty`, id.`total`, id.`discPercent`, id.`discAmount`, id.`netTotal`
						, pd.prodName, pd.prodDesc, pd.salesUom 
						FROM `invoice_detail` id
						INNER JOIN invoice_header ih on ih.invNo=id.invNo 
						INNER JOIN product_item itm ON itm.prodItemId=id.prodItemId 
						LEFT JOIN product pd on itm.prodCode=pd.code 
						WHERE 1
						AND ih.invNo=:invNo 
								";
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':invNo', $invNo);		
						$stmt->execute();
				   ?>	
					<table class="table table-striped">
						<tr>
							<th>No.</th>
							<th>Product Name</th>
							<th>Product Desc</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total</th>
						</tr>
						<?php $row_no=1; while ($row = $stmt->fetch()) { ?>
						<tr>
							<td style="text-align: center;"><?= $row_no; ?></td>
							<td><?= $row['prodName']; ?></td>
							<td><?= $row['prodDesc']; ?></td>
							<td style="text-align: right;"><?= number_format($row['qty'],0,'.',','); ?></td>
							<td style="text-align: right;"><?= number_format($row['salesPrice'],2,'.',','); ?></td>
							<td style="text-align: right;"><?= number_format($row['netTotal'],2,'.',','); ?></td>
						</tr>
						<?php $row_no+=1; } ?>
						<?php
							$sql = "SELECT IFNULL(SUM(id.nettotal),0) as netTotal 
							FROM `invoice_detail` id
							WHERE 1
							AND id.invNo=:invNo 
									";
							$stmt = $pdo->prepare($sql);
							$stmt->bindParam(':invNo', $invNo);		
							$stmt->execute();
							$row = $stmt->fetch(PDO::FETCH_ASSOC);
						?>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><b>Total</b></td>
							<td style="text-align: right;"><input type="hidden" id="hdrTotal" value="<?= $row['netTotal']; ?>" />
								<b><?= number_format($hdr['totalExcVat'],2,'.',','); ?></b>
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><b>Vat 7%</b></td>
							<td style="text-align: right;"><input type="hidden" id="hdrVatAmount" value="<?= $row['netTotal']*0.07; ?>" />
								<b><?= number_format($hdr['vatAmount'],2,'.',','); ?></b>
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><b>Net Total</b></td>
							<td style="text-align: right;"><input type="hidden" id="hdrNetTotal" value="<?= $row['netTotal'] + ($row['netTotal']*0.07); ?>" />
								<b><?= number_format($hdr['totalIncVat'],2,'.',','); ?></b>
							</td>
						</tr>
					</table>
				</div><!-- /.box-body -->
	</div><!-- /.row add items -->
		
	<div class="row">
		<div class="col-md-4">
					
		</div>
		<div class="col-md-4">
					
		</div>
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-4">
					Create By : </br>
					Create Time : </br>
					Confirm By : </br>
					Confirm Time : </br>
					Approve By : </br>
					Approve Time : 		
				</div>
				<div class="col-md-8">
					<label class=""><?php echo $hdr['createByName']; ?></label></br>
					<label class=""><?php if(strtotime($hdr['createTime'])<>0) {echo to_thai_datetime_fdt($hdr['createTime']);} ?></label></br>
					<label class=""><?php echo $hdr['confirmByName']; ?></label></br>
					<label class=""><?php if(strtotime($hdr['confirmTime'])<>0) {echo to_thai_datetime_fdt($hdr['confirmTime']);} ?></label></br>
					<label class=""><?php echo $hdr['approveByName']; ?></label></br>
					<label class=""><?php if(strtotime($hdr['approveTime'])<>0) {echo to_thai_datetime_fdt($hdr['approveTime']);} ?></label>	
				</div>				
			</div>			
		</div>
	</div>
	<!-- /.row -->
	
	
    </div><!-- /.box-body -->
  <div class="box-footer">
    <div class="col-md-12">
		  <?php switch($s_userGroupCode){ case 'admin' : case 'salesAdmin' : ?>
          <button type="button" id="btn_approve" class="btn btn-success pull-right" <?php echo ($hdr['statusCode']=='C'?'':'disabled'); ?>>
		 <i class="glyphicon glyphicon-check">
			</i> Approve
          </button>
		  <?php break; default : } ?>
		  <button type="button" id="btn_reject" class="btn btn-warning pull-right" style="margin-right: 5px;" <?php echo ($hdr['statusCode']=='C'?'':'disabled'); ?>>
		  <i class="glyphicon glyphicon-remove">
			</i> Reject
          </button>
          <button type="button" id="btn_verify" class="btn btn-primary pull-right" style="margin-right: 5px;" <?php echo ($hdr['statusCode']=='B'?'':'disabled'); ?> >
            <i class="glyphicon glyphicon-ok"></i> Verify
          </button>   
			<button type="button" id="btn_delete" class="btn btn-danger pull-right" style="margin-right: 5px;" <?php echo ($hdr['statusCode']<>'P'?'':'disabled'); ?> >
            <i class="glyphicon glyphicon-trash"></i> Delete
          </button>
	</div><!-- /.col-md-12 -->
  </div><!-- box-footer -->
</div><!-- /.box -->

<div id="spin"></div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include'footer.php'; ?>
  
  <!--AUDIO-->
	<audio id="audioSuccess" src="..\asset\sound\game-sound-effects-success-cute.wav" type="audio/wav"></audio>  
	
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
// Append and Hide spinner.          
var spinner = new Spinner().spin();
$("#spin").append(spinner.el);
$("#spin").hide();
//           
$('#btn_verify').click (function(e) {				 
	var params = {					
	invNo: $('#invNo').val()				
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Verify ?',accept:'Yes, sure.', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'invoice_verify_ajax.php',
			data: params,
			dataType: 'json'
		}).done(function(data) {
			if (data.success){  
				$.smkAlert({
					text: data.message,
					type: 'success',
					position:'top-center'
				});		
				location.reload();
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
});
//.btn_click

$('#btn_reject').click (function(e) {				 
	var params = {					
	invNo: $('#invNo').val()					
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Reject ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'invoice_reject_ajax.php',
			data: params,
			dataType: 'json'
		}).done(function(data) {
			if (data.success){  
				$.smkAlert({
					text: data.message,
					type: 'success',
					position:'top-center'
				});		
				location.reload();
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
});
//.btn_click

$('#btn_approve').click (function(e) {				 
	var params = {					
	invNo: $('#invNo').val()				
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Approve ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'invoice_approve_ajax.php',
			data: params,
			dataType: 'json'
		}).done(function(data) {
			if (data.success){  
				$.smkAlert({
					text: data.message,
					type: 'success',
					position:'top-center'
				});
				$('#audioSuccess').get(0).play();
				alert('Success.');
				window.location.href = "invoice_view.php?invNo=" + data.invNo;
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
});
//.btn_click

$('#btn_delete').click (function(e) {				 
	var params = {					
	invNo: $('#invNo').val()				
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Delete ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'invoice_delete_ajax.php',
			data: params,
			dataType: 'json'
		}).done(function(data) {
			if (data.success){  
				alert(data.message);
				window.location.href = 'invoice.php';
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
});
//.btn_click

	$("html,body").scrollTop(0);
	$("#statusName").fadeOut('slow').fadeIn('slow').fadeOut('slow').fadeIn('slow');
});
</script>



<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
