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
		Product
        <small>Product management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main</a></li>
        <li class="active">Product</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
      <!-- Your Page Content Here -->
      <a href="product.php" class="btn btn-google">Back</a>
    <div class="box box-primary">
        <div class="box-header with-border">
			<h3 class="box-title">Edit Product</h3>           
        </div><!-- /.box-header -->
		
        <div class="box-body">
           <div class="row">
                <div class="col-md-6">
                    <form id="form1" action="product_edit_ajax.php" method="post" class="form" validate>
						<?php							
							$sql = "SELECT a.*
									FROM product a
									WHERE 1
									AND a.id=".$_GET['id']."
									ORDER BY a.id desc
									";
							$result = mysqli_query($link, $sql);  
							$row = mysqli_fetch_assoc($result);
							//(empty($s_userPicture)? 'default-50x50.gif' : $s_userPicture)
							//if($row['photo']=="")
						?>
						<input type="hidden" name="id" id="id" value="<?= $row['id']; ?>" />
						<div class="form-group">
                            <label for="prodId">Product ID</label>                            
							<div class="input-group">
								<input id="prodId" type="text" class="form-control" name="prodId" value="<?= $row['id']; ?>" data-smk-msg="Require Group" disabled required>							
							</div>
                        </div>
						<div class="form-group">
                            <label for="prodGroup">Product Group</label>                            
							<div class="input-group">
								<input id="prodGroup" type="text" class="form-control" name="prodGroup" value="<?= $row['prodGroup']; ?>" data-smk-msg="Require Group" required>							
							</div>
                        </div>
						<div class="form-group">
                            <label for="prodName">Product Name</label>                            
							<div class="input-group">
								<input id="prodName" type="text" class="form-control" name="prodName" value="<?= $row['prodName']; ?>" data-smk-msg="Require Name" required>							
							</div>
                        </div>
						<div class="form-group">
                            <label for="prodNameNew">Product Name (New)</label>                            
							<div class="input-group">
								<input id="prodNameNew" type="text" class="form-control" name="prodNameNew" value="<?= $row['prodNameNew']; ?>" data-smk-msg="Require Name New" required>							
							</div>
                        </div>
						<div class="form-group">
                            <label for="prodDesc">Product Description</label>                            
							<div class="input-group">
								<input id="prodDesc" type="text" class="form-control" name="prodDesc" value="<?= $row['prodDesc']; ?>" data-smk-msg="Require Description" required>							
							</div>
                        </div>
						<div class="form-group">
                            <label for="prodPrice">Product Price</label>                            
							<div class="input-group">
								<input id="prodPrice" type="text" class="form-control" name="prodPrice" value="<?= $row['prodPrice']; ?>" data-smk-msg="Require Price" value="0.00" required>							
							</div>
                        </div>
						<div class="form-group">
                            <label for="appId">App ID</label>                            
							<div class="input-group">
								<input id="appId" type="text" class="form-control" name="appId" value="<?= $row['appID']; ?>" data-smk-msg="Require App ID" required>							
							</div>
                        </div>
						<div class="form-group">
                            <label for="statusCode">Status</label>
							<div class="input-group">
								<input id="statusCode" name="statusCode" type="checkbox" value="A" <?php if ($row['statusCode']=='A') echo 'checked'; ?> > Active
							</div>							
                        </div>
						<!--<a name="btn_submit" class="btn btn-default">Submit</a>--->
						<button type="submit" name="btn_submit" class="btn btn-default" >Submit</button>
                    
                </div>
				
				<div class="col-md-6">
					<input type="hidden" name="curPhoto" id="curPhoto" value="<?=$row['photo'];?>" />
					<input type="file" name="inputFile" accept="image/*" multiple  onchange="showMyImage(this)" /> <br/>
					<img id="thumbnil" style="width:50%; margin-top:10px;"  src="dist/img/product/<?php echo (empty($row['photo'])? 'default.jpg' : $row['photo']); ?>" alt="image"/>
				</div>
                </form>        
            </div>
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
<!-- Add _.$ jquery coding -->
<!--<script src="assets/underscore-min.js"></script>-->


<script> 
  // to start and stop spiner.  
$( document ).ajaxStart(function() {
        $("#spin").show();
		}).ajaxStop(function() {
            $("#spin").hide();
        });  
		
		
       $(document).ready(function() {    
            $("#title").focus();
            var spinner = new Spinner().spin();
            $("#spin").append(spinner.el);
            $("#spin").hide();
						
				
			$('a[name=btn_submitd]').click(function(){				
				/*var checked='';
				$('input[name=statusCode]:checked').each(function(){
					if(checked.length==0){
						checked=$(this).val();
					}else{
						checked=checked+','+$(this).val();
					}
				});
				var params = {
					id: $('#id').val(),
					prodGroup: $('#prodGroup').val(),
					prodName: $('#prodName').val(),
					prodNameNew: $('#prodNameNew').val(),
					prodDesc: $('#prodDesc').val(),
					prodPrice: $('#prodPrice').val(),
					appId: $('#appId').val(),
					statusCode: checked
				};	*/							
				//alert(params.statusCode);
				$.post({
					url: 'product_edit_ajax.php',
					data: new FormData( this ),
					processData: false,
					contentType: false,
					dataType: 'json'
				}).done(function (data) {					
					 if (data.success){ 
						 $.smkAlert({
							 text: data.message,
							 type: 'success',
							 position:'top-center'
						 });
						 } else {
							 $.smkAlert({
								 text: data.message,
								 type: 'danger'//,
	   //                        position:'top-center'
								 });
						 }
						 $('#form1').smkClear();
						 //$("#title").focus(); 
				}).error(function (response) {
					  alert(response.responseText);
				});    				
			});
			
			$('#form1').on("submit", function(e) {
				if ($('#form1').smkValidate()) {
				/*$.smkAlert({
					text: 'Validate OK',
					type: 'success',
					position:'top-left'
				});*/      

				$.ajax({
					url: 'product_edit_ajax.php',
					type: 'POST',
					data: new FormData( this ),
					processData: false,
					contentType: false,
					dataType: 'json'
					}).done(function (data) {
						if (data.success){          
							$.smkAlert({
								text: data.message,
								type: 'success',
								position:'top-center'
							});
						} else {
							$.smkAlert({
								text: data.message,
								type: 'danger',
							});
						}
						$('#form1')[0].reset();
						$("#userFullname").focus(); 
					});  
					//.ajax
					e.preventDefault();
				}   
				e.preventDefault();
			});
			//form.submit
	});
  </script>
  

















<!-- search modal dialog box. -->
<script>
	var cur_hid_mid_id = "";
	var cur_txt_fullname_id = "";
	var cur_txt_mobile_no_id = "";
	var cur_txt_position_act_name_id = "";	
	var cur_txt_origin_gen_no_id = "";
	$(document).ready(function(){
		$('.fullname').click(function(){
			//.prev() and .next() count <br/> too.
			cur_hid_mid_id = $(this).prev().attr('id');			
			cur_txt_fullname_id = $(this).attr('id');			
			cur_txt_mobile_no_id = 'mobile_no';	
			cur_txt_position_act_name_id = 'position_act_name';
			cur_txt_origin_gen_no_id = 'origin_gen_no';
			//show modal.
			$('#modal_search_person').modal('show');
		});	
		
		$('#modal_search_person').on('shown.bs.modal', function () {
			$('#txt_search_fullname').focus();
		});
		$(document).on("click",'a[data-name="search_person_btn_checked"]',function() {
			$('#'+cur_hid_mid_id).val($(this).attr('attr-id'));
			$('#'+cur_txt_fullname_id).val($(this).closest("tr").find('td.search_td_fullname').text());
			$('#'+cur_txt_mobile_no_id).val($(this).closest('tr').find('td.search_td_mobile_no').text());
			$('#'+cur_txt_position_act_name_id).val($(this).closest('tr').find('td.search_td_position_act_name').text());
			$('#'+cur_txt_origin_gen_no_id).val($(this).closest('tr').find('td.search_td_origin_gen_no').text());
			//hide modal.
			$('#modal_search_person').modal('hide');
		});
		$('#txt_search_fullname').keyup(function(e){
			if(e.keyCode == 13)
			{
				var params = {
					search_org_code: '',
                    search_fullname: $('#txt_search_fullname').val()					
                };
				if(params.search_fullname.length < 3){
					alert('search name surname must more than 3 character.');
					return false;
				}
				/* Send the data using post and put the results in a div */
				  $.ajax({
					  url: "search_person_by_org_code_and_fullname_ajax.php",
					  type: "post",
					  data: params,
					datatype: 'json',
					  success: function(data){	
								if(data.success){
									console.log(data);
									console.log(data.rows);
									//alert(data);
									_.each(data.rows, function(v){										
										$('#tbl_search_person_main tbody').append(										
											'<tr>' +
												'<td>' +
												'	<div class="btn-group">' +
												'	<a href="javascript:void(0);" data-name="search_person_btn_checked" ' +
												'   attr-id="'+v['id']+'" '+
												'	class="btn" title="เลือก"> ' +
												'	<i class="glyphicon glyphicon-ok"></i> เลือก</a> ' +
												'	</div>' +
												'</td>' +
												'<td class="search_td_fullname">'+ v['fullname'] +'</td>' +
												'<td class="search_td_mobile_no">'+ v['mobile_no'] +'</td>' +	
												'<td class="search_td_origin_gen_no">'+ v['origin_gen_no'] +'</td>' +	
												'<td class="search_td_position_act_name">'+ v['position_act_name'] +'</td>' +																							
											'</tr>'
										);			
									}); 
								}else{
									alert('data.success = '+data.success);
								}
								
								
					  }, //success
					  error:function(response){
						  alert('error');
						  alert(response.responseText);
					  }		  
					}); 
			}/* e.keycode=13 */	
		});
	});	
</script>
<!-- search modal dialog box. END -->

<script>
function showMyImage(fileInput) {
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                continue;
            }           
            var img=document.getElementById("thumbnil");            
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
        }    
    }
</script>

	
	
</body>
</html>
