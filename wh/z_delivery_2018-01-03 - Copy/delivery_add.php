<?php
  //  include '../db/database.php';
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; /*$s_userFullname = $row_user['userFullname'];
        $s_userPicture = $row_user['userPicture'];
		$s_username = $row_user['userName'];
		$s_userGroupCode = $row_user['userGroupCode'];
		$s_userDept = $row_user['userDept'];*/
		
$rootPage="delivery";	

$sql = "SELECT *
, ct.custName, ct.custAddr
, sm.name as smName 
FROM delivery_header dh 
INNER JOIN customer ct on ct.code=dh.custCode
LEFT JOIN salesman sm on sm.code=dh.smCode 
WHERE dh.statusCode='B' AND dh.createByID=:s_userID 
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':s_userID', $s_userID);	
$stmt->execute();
$hdr = $stmt->fetch();
$doNo = $hdr['doNo'];
$ppNo = $hdr['ppNo'];
$soNo = $hdr['soNo'];
?>
    
<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->	
    <section class="content-header">
		<h1><i class="glyphicon glyphicon-send"></i>
       Delivery Order
        <small>Delivery Order management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=$rootPage;?>.php"><i class="glyphicon glyphicon-list"></i>Delivery Order List</a></li>
		<li><a href="#"><i class="glyphicon glyphicon-edit"></i>Delivery Order</a></li>
      </ol>	  
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
    <div class="box box-primary">
        <div class="box-header with-border">
        <h3 class="box-title">Add Delivery Order No. : <?=$doNo;?></h3>
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
         
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">			
            <div class="row">
				<form id="form1" action="delivery_add_insert.php" method="post" class="form" novalidate>				
                <div class="col-md-12">   
					<div class="row">
						<div class="col-md-3">
							<label for="ppNo" >PP No.</label>
							<div class="form-group row">
								<div class="col-md-9">
									<input type="text" name="ppNo" class="form-control" <?php echo ($doNo==''?'':' value="'.$ppNo.'" disabled '); ?>  />
								</div>
								<div class="col-md-3">
									<a href="#" name="btnSdNo" class="btn btn-primary" <?php echo ($doNo==''?'':' disabled '); ?> ><i class="glyphicon glyphicon-search" ></i></a>								
								</div>
							</div>
							<!--from group-->
																					
                        </div>		
						<!--col-md-6-->		

						<div class="col-md-3">					  
							  <div class="from-group">
								<label for="soNo">SO No.</label>
								<input type="text" id="soNo" name="soNo" value="<?=$hdr['soNo'];?>" class="form-control" disabled>
							</div>
							<!--from group-->
						</div>
						<!-- col-md-->
						
						<div class="col-md-3">					  
							  <div class="from-group">
								<label for="custName">Customer Name</label>
								<input type="text" id="custName" name="custName" value="<?=$hdr['custName'];?>" class="form-control" disabled>
							</div>
							<!--from group-->
						</div>
						<!-- col-md-->
				
				<div class="col-md-3">					  
				  <!-- checkbox -->
					<div class="from-group">
						<label for="smName">Salesman Name</label>
						<input type="text" id="smName" name="smName" value="<?=$hdr['smName'];?>" class="form-control" disabled>
					</div>
					<!--from group-->		  
				</div>
				<!-- col-md-->
				
					</div>	
					<!--row-->
					
		<div class="row">
			<div class="col-md-3">		
				<div class="from-group">
				<label for="deliveryDate">Delivery Date</label>
				<input type="text" id="deliveryDate" name="deliveryDate" class="form-control datepicker" data-smk-msg="Require Order Date." required <?php echo ($doNo==''?'':' disabled '); ?> >
				</div>
				<!--from group-->				
			</div>
			<!--col-md-->
			<div class="col-md-6">	
				<div class="from-group">
					<label for="remark">Remark</label>
					<input type="text" id="remark" name="remark" value="<?=$hdr['remark'];?>" class="form-control" <?php echo ($doNo==''?'':' disabled '); ?> >
				</div>
				<!--from group-->
			</div>
			<!--col-md-->
		</div>
		<!--row-->
		<div class="row" <?php echo ($doNo==''?'':' style="display: none;" '); ?> >
			<div class="col-md-12">					
				<a name="btn_create" href="#" class="btn btn-default"><i class="glyphicon glyphicon-plus" ></i> Create</a>
			</div>
		</div>
		<!--row-->
					
				</div>
				<!-- col-md-6 --> 
						
				
				
				

			
			</form>			
            </div>   
			<!--/.row hdr-->
			
		<div class="row col-md-12"  <?php echo ($doNo!=''?'':' style="display: none;" '); ?> >
			<form id="form2" action="delivery_add_item_submit_ajax.php" method="post" class="form" novalidate>
				<input type="hidden" name="doNo" value="<?=$doNo;?>" />
				<?php
					$sql = "SELECT dd.`id`, dd.`prodCode`, dd.`qty`
					, pd.prodName, pd.prodDesc, pd.salesUom
                    , (SELECT IFNULL(SUM(dd.qty),0) FROM delivery_header dh 
                    	LEFT JOIN delivery_detail dds on dh.doNo=dds.doNo
                       	WHERE dh.soNo=oh.soNo AND dds.prodCode=dd.prodCode and dh.statusCode='P' ) as sentQty
                    , IFNULL(SUM(dd.qty),0) as deliveryQty 
					FROM delivery_detail dd
					INNER JOIN delivery_header dh on dh.doNo=dd.doNo 
					INNER JOIN sale_header oh on dh.soNo=oh.soNo 
					INNER JOIN `sale_detail` od on oh.soNo=od.soNo AND od.prodCode=dd.prodCode 	
					LEFT JOIN product pd on od.prodCode=pd.code 
					WHERE 1
					AND dh.doNo=:doNo 
					
					ORDER BY dd.`id`, dd.`prodCode`, dd.`qty`, pd.prodName, pd.prodDesc, pd.salesUom
							";
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':doNo', $doNo);		
					$stmt->execute();
				?>
				<div class="table-responsive">
				<table id="tbl_items" class="table table-striped">
					<tr>
						<th>No.</th>
						<th>Product Name</th>
						<th>UOM</th>
						<th>Order Qty</th>
						<th>Sent Qty</th>
						<th>Delivery Qty</th>
						<th>#</th>
					</tr>
					<?php $row_no=1; while ($row = $stmt->fetch()) { 
					?>
					<tr>
						<td><?= $row_no; ?></td>
						<td><?= $row['prodName']; ?></br>
							<small><?= $row['prodDesc']; ?></small></td>	
						<td><?= $row['salesUom']; ?></td>	
						<td style="text-align: right;"><?= number_format($row['qty'],0,'.',','); ?></td>
						<td style="text-align: right;"><?= number_format($row['sentQty'],0,'.',','); ?></td>
						<td style="text-align: right;"><?= number_format($row['deliveryQty'],0,'.',','); ?></td>
					</tr>
					<?php $row_no+=1; } ?>
				</table>
				</div>
				<!--/.table-responsive-->
				<a name="btn_submit" href="#" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> Submit</a>
				<a name="btn_view" href="delivery_view.php?doNo=<?=$doNo;?>" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> View</a>
				</form>
			</div>
			<!--/.row dtl-->
		
    </div><!-- /.box-body -->
  <div class="box-footer">
 




<!-- Modal -->
<div id="modal_search_person" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Search Sales Order No. OR Prepare No.</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
			<div class="form-group">	
				<label for="year_month" class="control-label col-md-2">SO NO. or Prepare No.</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="txt_search_fullname" />
				</div>
			</div>
		
		<table id="tbl_search_person_main" class="table">
			<thead>
				<tr bgcolor="4169E1" style="color: white; text-align: center;">
					<td>#Select</td>
					<td>Prepare No.</td>
					<td>Prepare Date</td>
					<td>Pick No.</td>
					<td>Sales Order No.</td>
					<td>Customer</td>
					<td>Salesman</td>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		</form>
		<div id="div_search_person_result">
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>




 
      
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


	//SEARCH Begin
	$('a[name="btnSdNo"]').click(function(){
		//prev() and next() count <br/> too.		
		$btn = $(this).closest("div").prev().find('input');
		curId = $btn.attr('name');
		//curId = $(this).prev().attr('name');
		curTxtFullName = $(this).attr('id');
		if(!$btn.prop('disabled')){
			$('#modal_search_person').modal('show');
		}
		
		//alert(curHidMid+' '+curSlOrgCode+' '+curTxtFullName+' ' +curTxtMobilePhoneNo);
		
	});	
	$('#txt_search_fullname').keyup(function(e){
		if(e.keyCode == 13)
		{
			var params = {
				search_fullname: $('#txt_search_fullname').val()
			};
			if(params.search_fullname.length < 3){
				alert('search name surname must more than 3 character.');
				return false;
			}
			/* Send the data using post and put the results in a div */
			  $.ajax({
				  url: "search_prepare_ajax.php",
				  type: "post",
				  data: params,
				datatype: 'json',
				  success: function(data){	
								$('#tbl_search_person_main tbody').empty();
								$.each($.parseJSON(data), function(key,value){
									$('#tbl_search_person_main tbody').append(
									'<tr>' +
										'<td>' +
										'	<div class="btn-group">' +
										'	<a href="javascript:void(0);" data-name="search_person_btn_checked" ' +
										'	class="btn" title="เลือก"> ' +
										'	<i class="glyphicon glyphicon-ok"></i> เลือก</a> ' +
										'	</div>' +
										'</td>' +
										'<td>'+ value.ppNo +'</td>' +
										'<td>'+ value.prepareDate +'</td>' +
										'<td>'+ value.pickNo +'</td>' +
										'<td>'+ value.soNo +'</td>' +
										'<td>'+ value.custCode+' : '+value.custName+'</td>' +
										'<td>'+ value.smCode+' : '+value.smName+'</td>' +
									'</tr>'
									);			
								});
							
				  }, //success
				  error:function(){
					  alert('error');
				  }   
				}); 
		}/* e.keycode=13 */	
	});
	
	$(document).on("click",'a[data-name="search_person_btn_checked"]',function() {
		
		$('input[name='+curId+']').val($(this).closest("tr").find('td:eq(1)').text());
		//$('#'+curTxtFullName).val($(this).closest("tr").find('td:eq(2)').text());
		//$('#'+curTxtMobilePhoneNo).val($(this).closest('tr').find('td:eq(3)').text());		
		$('#modal_search_person').modal('hide');
	});
	//Search End



	

	$('#form1 a[name=btn_create]').click (function(e) {
		if ($('#form1').smkValidate()){
			$.smkConfirm({text:'Are you sure to Create? ?',accept:'Yes.', cancel:'Cancel'}, function (e){if(e){
				$.post({
					url: 'delivery_add_hdr_insert_ajax.php',
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
			}else{ 
				$.smkAlert({ text: 'Cancelled.', type: 'info', position:'top-center'});	
			}});
			//smkConfirm
		e.preventDefault();
		}//.if end
	});
	//.btn_click

	
	$('#form2 a[name=btn_submit]').click (function(e) {
		if ($('#form2').smkValidate()){
			$.smkConfirm({text:'Are you sure to Submit ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
				$.post({
					url: 'delivery_add_item_submit_ajax.php',
					data: $("#form2").serialize(),
					dataType: 'json'
				}).done(function(data) {
					if (data.success){  
						$.smkAlert({
							text: data.message,
							type: 'success',
							position:'top-center'
						});
						window.location.href = "delivery_view.php?doNo=" + data.doNo;
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
			}else{ 
				$.smkAlert({ text: 'Cancelled.', type: 'info', position:'top-center'});	
			}});
			//smkConfirm
		e.preventDefault();
		}//.if end
	});
	//.btn_click
	
	
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
		});  //กำหนดเป็นวันปัจุบัน
		//กำหนดเป็น วันที่จากฐานข้อมูล
		<?php if($doNo<>''){ ?>
		var queryDate = '<?=$hdr['deliveryDate'];?>',
		dateParts = queryDate.match(/(\d+)/g)
		realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]); 
		$('#deliveryDate').datepicker('setDate', realDate);
		<?php }else{ ?> $('#deliveryDate').datepicker('setDate', '0'); <?php } ?>
		//จบ กำหนดเป็น วันที่จากฐานข้อมูล
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