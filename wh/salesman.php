<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php 
	include 'head.php'; 
	include 'inc_helper.php'; 
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
		Salesman
        <small>Salesman management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main</a></li>
        <li class="active">Salesman</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
      <!-- Your Page Content Here -->
      <a href="salesman_add.php" class="btn btn-google">Add Salesman</a>
    <div class="box box-primary">
        <div class="box-header with-border">
        <h3 class="box-title">Salesman List</h3>
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
          <?php
				$search_word="";
				$sqlSearch = "";
				if(isset($_GET['search_word']) and isset($_GET['search_word'])){
					$search_word=$_GET['search_word'];
					$sqlSearch = "and name like '%".$_GET['search_word']."%' or surname like '%".$_GET['search_word']."%' ";
				}
                $sql = "SELECT count(*) as countTotal
						FROM salesman a
						WHERE 1 "
						.$sqlSearch."
						ORDER BY a.name asc 
						";
                $result = mysqli_query($link, $sql);
                $countTotal = mysqli_fetch_assoc($result);
				
				$rows=20;
				$page=0;
				if( !empty($_GET["page"]) and isset($_GET["page"]) ) $page=$_GET["page"];
				if($page<=0) $page=1;
				$total_data=$countTotal['countTotal'];
				$total_page=ceil($total_data/$rows);
				if($page>=$total_page) $page=$total_page;
				$start=($page-1)*$rows;
          ?>
          <span class="label label-primary">Total <?php echo $countTotal['countTotal']; ?> items</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
			<div class="row">
			<div class="col-md-6">					
                    <form id="form1" action="salesman.php" method="get" class="form" novalidate>
						<div class="form-group">
                            <label for="search_word">Salesman search key word.</label>
							<div class="input-group">
								<input id="search_word" type="text" class="form-control" name="search_word" data-smk-msg="Require userFullname."required>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-search"></span>
								</span>
							</div>
                        </div>						
						<input type="submit" class="btn btn-default" value="ค้นหา">
                    </form>
                </div>    
			</div>
           <?php
                $sql = "SELECT a.*
						FROM salesman a
						WHERE 1 "
						.$sqlSearch."
						ORDER BY a.name asc
						LIMIT $start, $rows 
						";
                $result = mysqli_query($link, $sql);                
           ?>             
            <table class="table table-striped">
                <tr>
                    <th>No.</th>
					<th>Fullname</th>
					<th>Position</th>
					<th>Mobile</th>
					<th>Email</th>
					<th>Status</th>
                    <th>#</th>
                </tr>
                <?php $c_row=($start+1); while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td>
                         <?= $c_row; ?>
                    </td>
					<td>
                         <?= $row['name'].'&nbsp;&nbsp;'.$row['surname']; ?>
                    </td>  		
					<td>
                         <?= $row['positionName']; ?>
                    </td> 
					<td>
                         <?= $row['mobileNo']; ?>
                    </td> 
					<td>
                         <?= $row['email']; ?>
                    </td> 
					<td>
                         <?php echo ($row['statusCode']=='A' ? 'Active' : 'Inactive'); ?>
                    </td>					
                    <td>
						<a class="btn btn-success" name="btn_row_edit" href="salesman_edit.php?id=<?= $row['ID']; ?>" >Edit</a>
						<a class="btn btn-danger" name="btn_row_remove" data-id="<?= $row['ID']; ?>" >Remove</a>
                    </td>
                </tr>
                <?php $c_row +=1; } ?>
            </table>
			
			<nav>
			<ul class="pagination">
				<li <?php if($page==1) echo 'class="disabled"'; ?> >
					<a href="salesman.php?search_word=<?= $search_word;?>&=page=<?= $page-1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
				</li>
				<?php for($i=1; $i<=$total_page;$i++){ ?>
				<li <?php if($page==$i) echo 'class="active"'; ?> >
					<a href="salesman.php?search_word=<?= $search_word;?>&page=<?= $i?>" > <?= $i;?></a>			
				</li>
				<?php } ?>
				<li <?php if($page==$total_page) echo 'class="disabled"'; ?> >
					<a href="salesman.php?search_word=<?= $search_word;?>&page=<?=$page+1?>" aria-labels="Next"><span aria-hidden="true">&raquo;</span></a>
				</li>
			</ul>
			</nav>
			
			
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
<!--Deprecation Notice: The jqXHR.success(), jqXHR.error(), and jqXHR.complete() callbacks are removed as of jQuery 3.0. 
    You can use jqXHR.done(), jqXHR.fail(), and jqXHR.always() instead.-->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- smoke validate -->
<script src="bootstrap/js/smoke.min.js"></script>
<!-- Add Spinner feature -->
<script src="bootstrap/js/spin.min.js"></script>


<script> 		
$(document).ready(function() {    
			//.ajaxStart inside $(document).ready to start and stop spiner.  
			$( document ).ajaxStart(function() {
				$("#spin").show();
			}).ajaxStop(function() {
				$("#spin").hide();
			});
			//.ajaxStart inside $(document).ready END
			
            $("#title").focus();
            var spinner = new Spinner().spin();
            $("#spin").append(spinner.el);
            $("#spin").hide();
			
	  
			   
	$('a[name=btn_row_delete]').click(function(){
		var params = {
			id: $(this).attr('data-id')
		};
		$.smkConfirm({text:'คุณแน่ใจที่จะลบรายการนี้ใช่หรือไม่ ?',accept:'ลบรายการ', cancel:'ไม่ลบรายการ'}, function (e){if(e){
			$.post({
				url: 'salesman_del_ajax.php',
				data: params,
				dataType: 'json'
			}).done(function (data) {					
				if (data.success){ 
					$.smkAlert({
						text: data.message,
						type: 'success',
						position:'top-center'
					});
					location.reload();
				} else {
					alert(data.message);
					$.smkAlert({
						text: data.message,
						type: 'danger'//,
					//                        position:'top-center'
					});
				}
			}).error(function (response) {
				alert(response.responseText);
			}); 
		}});
		e.preventDefault();
	});
	
	$('a[name=btn_row_remove]').click(function(){
		var params = {
			id: $(this).attr('data-id')
		};
		$.smkConfirm({text:'คุณแน่ใจที่จะยกเลิกรายการนี้ใช่หรือไม่ ?',accept:'ยกเลิกรายการ', cancel:'ไม่ยกเลิกรายการ'}, function (e){if(e){
			$.post({
				url: 'salesman_remove_ajax.php',
				data: params,
				dataType: 'json'
			}).done(function (data) {					
				if (data.success){ 
					$.smkAlert({
						text: data.message,
						type: 'success',
						position:'top-center'
					});
					location.reload();
				} else {
					alert(data.message);
					$.smkAlert({
						text: data.message,
						type: 'danger'//,
					//                        position:'top-center'
					});
				}
			}).error(function (response) {
				alert(response.responseText);
			}); 
		}});
		e.preventDefault();
	});
});
</script>

</body>
</html>
