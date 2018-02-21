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

$rootPage="sale";


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
  <?php $soNo = $_GET['soNo']; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Sales Order Information
        <small>Sales Order management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="sale.php"><i class="fa fa-list"></i>Sales List</a></li>
		<li><a href="sale_item.php?soNo=<?=$soNo;?>"><i class="fa fa-edit"></i>SO No.<?=$soNo;?></a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<?php
			$soNo = $_GET['soNo'];
			$sql = "
			SELECT a.`soNo`, a.`saleDate`,a.`poNo`,a.`piNo`, a.`custId`, a.`smId`, a.`total`, a.`vatAmount`
			, a.`netTotal`, a.`prodGFC`, a.`prodGFM`, a.`prodGFT`, a.`prodSC`, a.`prodCFC`, a.`prodEGWM`
			, a.`prodGT`, a.`prodCSM`, a.`prodWR`
			, a.`deliveryDate`, a.`shipByLcl`, a.`shipByFcl1x20`, a.`shipByFcl1x40`, a.`shippingMarksId`, a.`suppTypeFact`
			, a.`suppTypeImp`, a.`prodTypeOld`, a.`prodTypeNew`, a.`custTypeOld`, a.`custTypeNew`
			, a.`prodStkInStk`, a.`prodStkOrder`, a.`prodStkOther`, a.`prodStkRem`, a.`packTypeAk`
			, a.`packTypeNone`, a.`packTypeOther`, a.`packTypeRem`, a.`priceOnOrder`, a.`priceOnOther`
			, a.`priceOnRem`, a.`remark`, a.`plac2deliCode`, a.`plac2deliCodeSendRem`, a.`plac2deliCodeLogiRem`, a.`payTypeCode`, a.`payTypeCreditDays`
			, a.`isClose`, a.`statusCode`, a.`createTime`, a.`createByID`, a.`updateTime`, a.`updateById`
			, a.shippingMark, a.`remCoa`, a.`remPalletBand`, a.`remFumigate`
			, b.code as custCode, b.name as custName, b.addr1 as custAddr1, b.addr2 as custAddr2, b.addr3 as custAddr3, b.zipcode, b.tel as custTel, b.fax as custFax
			, c.code as smCode, c.name as smName, c.surname as smSurname
			, spm.name as shippingMarksName, IFNULL(spm.filePath,'') as shippingMarksFilePath
			
			, d.userFullname as createByName
			, a.confirmTime, cu.userFullname as confirmByName
			, a.approveTime, au.userFullname as approveByName
			FROM `sale_header` a
			left join customer b on b.id=a.custId 
			left join salesman c on c.id=a.smId 
			left join shipping_marks spm on spm.id=a.shippingMarksId 
			left join user d on a.createById=d.userId
			left join user cu on a.confirmById=cu.userId
			left join user au on a.approveById=au.userId
			WHERE 1
			AND a.soNo=:soNo 					
			ORDER BY a.createTime DESC
			LIMIT 1
			";
			$stmt = $pdo->prepare($sql);			
			$stmt->bindParam(':soNo', $soNo);	
			$stmt->execute();
			$hdr = $stmt->fetch();			
			$soNo = $hdr['soNo'];
	   ?> 
      <!-- Your Page Content Here -->
    <div class="box box-primary">
        <div class="box-header with-border">
			<h3 class="box-title">View Sales Order No : <b><?= $soNo; ?></b></h3>
			<div class="box-tools pull-right">
				<?php $statusName = '<b style="color: red;">Unknown</b>'; switch($hdr['statusCode']){
					case 'A' : $statusName = '<b style="color: red;">Incompleate</b>'; break;
					case 'B' : $statusName = '<b style="color: blue;">Begin</b>'; break;
					case 'C' : $statusName = '<b style="color: blue;">Confirmed</b>'; break;
					case 'P' : $statusName = '<b style="color: green;">Approved</b>'; break;
					default : 
				} ?>
				<h3 class="box-title" id="statusName">Status : <?= $statusName; ?></h3>
			</div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
			<input type="hidden" id="soNo" value="<?= $soNo; ?>" />
            <div class="row">				
					<div class="col-md-3">
						Salesman : <br/>
						<b><?= $hdr['smName'].'&nbsp;&nbsp;'.$hdr['smSurname']; ?></b><br/>
						<?php
							echo ($hdr['isClose']=='Y'?'<h1 style="color: red; font-weight: bold;text-decoration: underline;">Closed</h1>':'<h1 style="color: red; font-weight: bold;text-decoration: underline;">Open</h1>');
						?>
					</div><!-- /.col-md-3-->	
					<div class="col-md-3">
						Customer : <br/>
						<b><?= $hdr['custName']; ?></b><br/>
						<?= $hdr['custAddr1']; ?><br/>
						<?= $hdr['custAddr2']; ?><br/>
						<?= $hdr['custAddr3']; ?>
					</div><!-- /.col-md-3-->	
					<div class="col-md-3">
						Order No : <br/>
						<b><?= $hdr['soNo']; ?></b><br/>
						Order Date : <br/>
						<b><?= $hdr['saleDate']; ?></b><br/>
					</div>	<!-- /.col-md-3-->	
					<div class="col-md-3">
						<i class="fa fa-<?php echo ($hdr['suppTypeFact']==0?'square-o':'check-square-o'); ?>"></i> Factory&nbsp;&nbsp;&nbsp;    <i class="fa fa-<?php echo ($hdr['suppTypeImp']==0?'square-o':'check-square-o'); ?>"></i> Import</br>
						<i class="fa fa-<?php echo ($hdr['prodTypeOld']==0?'square-o':'check-square-o'); ?>"></i> Old Product&nbsp;&nbsp;&nbsp; <i class="fa fa-<?php echo ($hdr['prodTypeNew']==0?'square-o':'check-square-o'); ?>"></i> New Product</br>
						<i class="fa fa-<?php echo ($hdr['custTypeOld']==0?'square-o':'check-square-o'); ?>"></i> Old Customer&nbsp;&nbsp;&nbsp; <i class="fa fa-<?php echo ($hdr['custTypeNew']==0?'square-o':'check-square-o'); ?>"></i> New Customer</br>						
					</div>	<!-- /.col-md-3-->	
			</div> <!-- row add items -->
		
			<div class="row"><!-- row show items -->
				<div class="box-header with-border">
				<h3 class="box-title">Item List</h3>
				<div class="box-tools pull-right">
				  <!-- Buttons, labels, and many other things can be placed here! -->
				  <!-- Here is a label for example -->
				  <?php
					$sql = "
					SELECT COUNT(*) as countTotal 
					FROM `sale_detail` a
					LEFT JOIN product b on a.prodId=b.id
					WHERE 1
					AND a.`soNo`=:soNo 
					ORDER BY a.createTime
					";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':soNo', $hdr['soNo']);
					$stmt->execute();
					$row = $stmt->fetch();
					$countTotal = $row['countTotal'];
				  ?>
				  <span class="label label-primary">Total <?php echo $countTotal; ?> items</span>
				</div><!-- /.box-tools -->
				</div><!-- /.box-header -->
				<div class="box-body">
				   <?php
						$sql = "
						SELECT a.`id`, a.`prodId`, a.`salesPrice`, a.`qty`, a.`total`, a.deliveryDate, 
						a.`discPercent`, a.`discAmount`, a.`netTotal`, a.`soNo`
						, b.code as prodCode, b.name as prodName, b.uomCode as prodUomCode
						, (SELECT IFNULL(SUM(id.qty),0) FROM invoice_detail id 
								INNER JOIN invoice_header ih on ih.invNo=id.invNo										
								INNER JOIN delivery_header dh on dh.doNo=ih.doNo 
								WHERE dh.soNo=a.soNo AND id.prodCode=a.prodId ) as sentQty 
						FROM `sale_detail` a
						LEFT JOIN product b on a.prodId=b.id
						WHERE 1
						AND a.`soNo`=:soNo 
						ORDER BY a.createTime
						";
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':soNo', $hdr['soNo']);
						$stmt->execute();	
				   ?>	
					<table class="table table-striped">
						<tr>
							<th>No.</th>
							<th>Delivery</th>
							<th>Product Name</th>
							<th>Product Code</th>
							<th>Qty</th>
							<th style="color: blue;">Sent Qty</th>
						</tr>
						<?php $row_no=1; while ($row = $stmt->fetch()) { ?>
						<tr>
							<td style="text-align: center;"><?= $row_no; ?></td>
							<td><?= to_thai_date_fdt($row['deliveryDate']); ?></td>					
							<td><?= $row['prodName']; ?></td>					
							<td><?= $row['prodCode']; ?></td>					
							<td style="text-align: right;"><?= number_format($row['qty'],0,'.',',').'&nbsp;'.$row['prodUomCode']; ?></td>
							<td style="text-align: right; color: blue;"><?= number_format($row['sentQty'],0,'.',',').'&nbsp;'.$row['prodUomCode']; ?></td>
						</tr>
						<?php $row_no+=1; } ?>						
					</table>
				</div><!-- /.box-body -->
	</div><!-- /.row add items -->
	
	<div class="row">
		<div class="col-md-2">
			Product :
		</div>
		<div class="col-md-10">
			<i class="fa fa-<?php echo ($hdr['prodGFC']==0?'square-o':'check-square-o'); ?>"></i> Glass Fiber Cloth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-<?php echo ($hdr['prodGFM']==0?'square-o':'check-square-o'); ?>"></i> Glass Fiber Mesh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-<?php echo ($hdr['prodGFT']==0?'square-o':'check-square-o'); ?>"></i> Glass Fiber Tape&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-<?php echo ($hdr['prodSC']==0?'square-o':'check-square-o'); ?>"></i> Silica Cloth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-<?php echo ($hdr['prodCFC']==0?'square-o':'check-square-o'); ?>"></i> Cabon Fiber Cloth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</br>
			<i class="fa fa-<?php echo ($hdr['prodEGWM']==0?'square-o':'check-square-o'); ?>"></i> E-Glass Wool Mat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-<?php echo ($hdr['prodGT']==0?'square-o':'check-square-o'); ?>"></i> Glass Tissue&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-<?php echo ($hdr['prodCSM']==0?'square-o':'check-square-o'); ?>"></i> Chopped Strand Mat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-<?php echo ($hdr['prodWR']==0?'square-o':'check-square-o'); ?>"></i> Woven Roving
		</div>
		
		<div class="col-md-2">
			Stock Status :
		</div>
		<div class="col-md-10">
			<i class="fa fa-<?php echo ($hdr['prodStkInStk']==0?'square-o':'check-square-o'); ?>"></i> In Stock&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			<i class="fa fa-<?php echo ($hdr['prodStkOrder']==0?'square-o':'check-square-o'); ?>"></i> Order&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			<i class="fa fa-<?php echo ($hdr['prodStkOther']==0?'square-o':'check-square-o'); ?>"></i> Other 
			<label class="label label-default"><?php echo $hdr['prodStkRem']; ?></label>
		</div>
		<div class="col-md-2">
			Packing :
		</div>
		<div class="col-md-10">
			<i class="fa fa-<?php echo ($hdr['packTypeAk']==0?'square-o':'check-square-o'); ?>"></i> AK Logo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			<i class="fa fa-<?php echo ($hdr['packTypeNone']==0?'square-o':'check-square-o'); ?>"></i> None AK Logo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			<i class="fa fa-<?php echo ($hdr['packTypeOther']==0?'square-o':'check-square-o'); ?>"></i> Other	
			<label class="label label-default"><?php echo $hdr['packTypeRem']; ?></label>
		</div>
		<div class="col-md-2">
			Delivery / Load Date :
		</div>
		<div class="col-md-10">
			<label class="label label-default"><?php echo $hdr['deliveryDate']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By :
			<?php 
			$shipByStr="";
			if($hdr['shipByLcl']==0){}else{$shipByStr.=($shipByStr==""?"":",  ")."LCL";}
			if($hdr['shipByFcl1x20']==0){}else{$shipByStr.=($shipByStr==""?"":",  ")."FCL 1x20'";}
			if($hdr['shipByFcl1x40']==0){}else{$shipByStr.=($shipByStr==""?"":",  ")."FCL 1x40'";}
			?>
			<label class="label label-default"><?php echo $shipByStr; ?></label>
		</div>
		<div class="col-md-2">
			Pricing on :
		</div>
		<div class="col-md-10">
			<i class="fa fa-<?php echo ($hdr['priceOnOrder']==0?'square-o':'check-square-o'); ?>"></i> Sales Order&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			<i class="fa fa-<?php echo ($hdr['priceOnOther']==0?'square-o':'check-square-o'); ?>"></i> Other
			<label class="label label-default"><?php echo $hdr['priceOnRem']; ?></label>
		</div>
		<div class="col-md-2">
			Remark :
		</div>
		<div class="col-md-10">
			<?php 
			$remStr = '';
			if($hdr['remCoa']==0){}else{$remStr.=($remStr==""?"":",  ")."ขอ COA";}
			if($hdr['remPalletBand']==0){}else{$remStr.=($remStr==""?"":",  ")."PALLET ตีตรา";}
			if($hdr['remFumigate']==0){}else{$remStr.=($remStr==""?"":",  ")."รมยาตู้คอนเทนเนอร์";}
			if($hdr['remark']==""){}else{$remStr.=($remStr==""?"":",  ").$hdr['remark'];}
			?>
			<label class="label label-default"><?php echo $remStr; ?></label>
		</div>		
	</div>
	<!-- /.row -->
	
	<div class="row">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-4">
					Credit :					
				</div>
				<div class="col-md-8">					
					<label class="label label-default"><?php echo $hdr['payTypeCreditDays']; ?></label> Days</br>
					<i class="fa fa-<?php echo ($hdr['payTypeCode']=='CASH'?'check-circle-o':'circle-o'); ?>"></i> Cash</br>
					<i class="fa fa-<?php echo ($hdr['payTypeCode']=='CHEQ'?'check-circle-o':'circle-o'); ?>"></i> Cheque</br>
					<i class="fa fa-<?php echo ($hdr['payTypeCode']=='TRAN'?'check-circle-o':'circle-o'); ?>"></i> Transfer					
				</div>
			</div>			
		</div>
		<div class="col-md-4">
			Place to Delivery :
			<div class="row">
				
				<div class="col-md-2">
				</div>
				<div class="col-md-10">					
					<i class="fa fa-<?php echo ($hdr['plac2deliCode']=='FACT'?'check-circle-o':'circle-o'); ?>"></i> AK Factory</br>
					<i class="fa fa-<?php echo ($hdr['plac2deliCode']=='SEND'?'check-circle-o':'circle-o'); ?>"></i> Factory Sent to
					<label class="label label-default"><?php echo $hdr['plac2deliCodeSendRem']; ?></label></br>
					<i class="fa fa-<?php echo ($hdr['plac2deliCode']=='MAP_'?'check-circle-o':'circle-o'); ?>"></i> Map</br>
					<i class="fa fa-<?php echo ($hdr['plac2deliCode']=='LOGI'?'check-circle-o':'circle-o'); ?>"></i> Logistic</br>
					<label class="label label-default"><?php echo $hdr['plac2deliCodeLogiRem']; ?></label>
				</div>
			</div>			
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
					<label class=""><?php echo to_thai_datetime_fdt($hdr['createTime']); ?></label></br>
					<label class=""><?php echo $hdr['confirmByName']; ?></label></br>
					<label class=""><?php echo to_thai_datetime_fdt($hdr['confirmTime']); ?></label></br>
					<label class=""><?php echo $hdr['approveByName']; ?></label></br>
					<label class=""><?php echo to_thai_datetime_fdt($hdr['approveTime']); ?></label>	
				</div>				
			</div>			
		</div>
	</div>
	<!-- /.row -->
	
	
	
			
			
			
          
    
    </div><!-- /.box-body -->
  <div class="box-footer">
    <div class="col-md-12">
		<?php if($hdr['statusCode']=='P'){ ?>
          <a href="<?=$rootPage;?>_view_pdf.php?soNo=<?=$soNo;?>" target="_blank" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Print</a>
		<?php } ?>
		
		
		
		  <?php switch($s_userGroupCode){ case 'admin' : case 'salesAdmin' : ?>
		<?php if($hdr['statusCode']=='P'){ ?>
          <button type="button" id="btn_close_so" class="btn btn-danger pull-right" <?php echo (($hdr['statusCode']=='P' AND $hdr['isClose']=='Y')?'disabled':''); ?>>
		 <i class="glyphicon glyphicon-ok-sign">
			</i> Close Sales Order
          </button>
		  <?php } ?>
		  <?php break; default : } ?>
		  
		  <?php switch($s_userGroupCode){ case 'admin' : case 'salesAdmin' : ?>
          <button type="button" id="btn_approve" class="btn btn-success pull-right" style="margin-right: 5px;" <?php echo ($hdr['statusCode']=='C'?'':'disabled'); ?> >
		 <i class="glyphicon glyphicon-check">
			</i> Approve
          </button>
		  
		  <button type="button" id="btn_reject" class="btn btn-warning pull-right" style="margin-right: 5px;" <?php echo ($hdr['statusCode']=='C'?'':'disabled'); ?>>
		  <i class="glyphicon glyphicon-remove">
			</i> Reject
          </button>
		  <?php break; default : } ?>
		  
          <button type="button" id="btn_verify" class="btn btn-primary pull-right" style="margin-right: 5px;" <?php echo ($hdr['statusCode']=='B'?'':'disabled'); ?> >
            <i class="glyphicon glyphicon-ok"></i> Confirm
          </button>      
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
	soNo: $('#soNo').val(),
	hdrTotal: $('#hdrTotal').val(),
	hdrVatAmount: $('#hdrVatAmount').val(),
	hdrNetTotal: $('#hdrNetTotal').val()					
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Confirm ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'sale_confirm_ajax.php',
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
	soNo: $('#soNo').val()					
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Reject ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'sale_reject_ajax.php',
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
	soNo: $('#soNo').val()				
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Approve ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'sale_approve_ajax.php',
			data: params,
			dataType: 'json'
		}).done(function(data) {
			if (data.success){  
				$.smkAlert({
					text: data.message,
					type: 'success',
					position:'top-center'
				});
				window.location.href = "sale_view.php?soNo=" + data.soNo;
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

$('#btn_close_so').click (function(e) {				 
	var params = {					
	soNo: '<?=$soNo;?>'			
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Close ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'sale_close_ajax.php',
			data: params,
			dataType: 'json'
		}).done(function(data) {
			if (data.success){  
				$.smkAlert({
					text: data.message,
					type: 'success',
					position:'top-center'
				});
				window.location.href = "sale_view.php?soNo=" + data.soNo;
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
	soNo: $('#soNo').val()				
	};
	//alert(params.hdrID);
	$.smkConfirm({text:'Are you sure to Delete ?', accept:'Yes', cancel:'Cancel'}, function (e){if(e){
		$.post({
			url: 'sale_delete_ajax.php',
			data: params,
			dataType: 'json'
		}).done(function(data) {
			if (data.success){  
				alert(data.message);
				window.location.href = 'sale.php';
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
