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
		Report
        <small>Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main</a></li>
        <li class="active">Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
      <!-- Your Page Content Here -->
    <div class="box box-primary">
        <div class="box-header with-border">
        <h3 class="box-title">Product Stock Report</h3>		
		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
          <?php
				$sloc = (isset($_GET['sloc'])?$_GET['sloc']:'8');
				$catCode = (isset($_GET['catCode'])?$_GET['catCode']:'');
				$marketCode = (isset($_GET['marketCode'])?$_GET['marketCode']:'');
				$search_word="";
				$sqlSearch = "";
				$url="rpt_prod_stk.php";
				if(isset($_GET['search_word']) and isset($_GET['search_word'])){
					$search_word=$_GET['search_word'];
					$sqlSearch = "and (prodName like '%".$_GET['search_word']."%' OR prodNameNew like '%".$_GET['search_word']."%') ";
				}
				if($marketCode<>""){ $sqlSearch .= " AND appId='$marketCode' ";	}
				if($catCode<>""){ $sqlSearch .= " AND prodCat='$catCode' ";	}
				
                $sql = "SELECT count(*) as countTotal
				FROM stk_bal sb 
				INNER JOIN product a on sb.prodCode=a.code 
				WHERE 1 ";
				($sloc==''?'':$sql.=' AND sb.sloc='.$sloc.' ');
				$sql.=$sqlSearch."
				"; //echo $sql;
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
				if($page==0) $start=0;
          ?>
		  
          <span class="label label-primary">Total <?php echo $countTotal['countTotal']; ?> items</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
			<div class="row" style="margin-bottom: 5px;">
			<!--<div class="col-md-12">
				<ul class="nav nav-tabs">
					<li class="active" ><a href="#divSearch" toggle="tab">Search</a></li>
					<li class="" ><a href="#divResult" toggle="tab">Result</a></li>
				</ul>
				
				<div class="tab-content clearfix">
				<div class="tab-pane active" id="divSearch">a
				</div>
				<div class="tab-pane" id="divResult">b
				</div>
				</div>
				
			</div>-->
			<div class="col-md-12">					
                    <form id="form1" action="<?=$url;?>" method="get" class="form-inline" novalidate>
						<label>SLOC : </label>
					<select name="sloc" class="form-control">
						<option value="" <?php echo ($sloc==""?'selected':''); ?> >--All--</option>
						<?php
						$sql = "SELECT `code`, `name` FROM sloc WHERE statusCode='A'	ORDER BY code ASC ";
						$stmt = $pdo->prepare($sql);
						$stmt->execute();					
						while ($row = $stmt->fetch()){
							$selected=($sloc==$row['code']?'selected':'');						
							echo '<option value="'.$row['code'].'" '.$selected.'>'.$row['code'].' : '.$row['name'].'</option>';
						}
						?>
					</select>	
					
						<label>Cat : </label>
					<select name="catCode" class="form-control">
						<option value="" <?php echo ($catCode==""?'selected':''); ?> >--All--</option>
						<?php
						$sql = "SELECT `code`, `name` FROM product_category WHERE statusCode='A'	ORDER BY code ASC ";
						$stmt = $pdo->prepare($sql);
						$stmt->execute();					
						while ($row = $stmt->fetch()){
							$selected=($catCode==$row['code']?'selected':'');						
							echo '<option value="'.$row['code'].'" '.$selected.'>'.$row['code'].' : '.$row['name'].'</option>';
						}
						?>
					</select>				
					
						<div class="form-group">
                            <label for="search_word">Product search key word.</label>
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
				<!--col-md-12-->
				
			</div>
			<!--row-->
			
			<div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
					<th>No.</th>
                    <th>prodName</th>
					<th>SLOC</th>
					<th>Category</th>
					<th>Sales Cat.</th>
                    <th>Open</th>
					<th>Produce</th>
					<th>On Way</th>
					<th>Receive</th>
					<th>Send</th>
					<th>Sales</th>
					<th>Delivery</th>
					<th>Balance</th>
                  </tr>
                  </thead>
                  <tbody>
					<?php
						$sql = "SELECT a.*
						,sb.sloc, sb.`open`, sb.`produce`, sb.`onway`, sb.`receive`, sb.`send`, sb.`sales`, sb.`delivery`, sb.`balance` 
						FROM product a
						LEFT JOIN stk_bal sb on sb.prodCode=a.code 
						WHERE 1 ";
						($sloc==''?'':$sql.=' AND sb.sloc='.$sloc.' ');
						$sql.=$sqlSearch."
						ORDER BY a.code desc
						LIMIT $start, $rows 
						";
						$result = mysqli_query($link, $sql);                
				   ?>             
					
					
						<?php $c_row=($start+1); while ($row = mysqli_fetch_assoc($result)) { 
							//$img = 'dist/img/product/'.(empty($row['photo'])? 'default.jpg' : $row['photo']);
					?>
                  <tr>
					<td><?= $c_row; ?></td>
                    <td><a href="product_view_stk.php?code=<?=$row['code'];?>" ><?= $row['prodName']; ?></a></td>
					<td><?= $row['sloc']; ?></td>
					<td><?= $row['prodCat']; ?></td>
					<td><?= $row['appId']; ?></td>
					<td><?= number_format($row['open'],0,'.',','); ?></td>
					<td><?= number_format($row['produce'],0,'.',','); ?></td>
					<td><?= number_format($row['onway'],0,'.',','); ?></td>
					<td><?= number_format($row['receive'],0,'.',','); ?></td>
					<td><?= number_format($row['send'],0,'.',','); ?></td>
					<td><?= number_format($row['sales'],0,'.',','); ?></td>
					<td><?= number_format($row['delivery'],0,'.',','); ?></td>
					<td><?= number_format($row['balance'],0,'.',','); ?></td>
                </tr>
                 <?php $c_row +=1; } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
			  
				
                
               
			
			<nav>
			<ul class="pagination">
				<li <?php if($page==1) echo 'class="disabled"'; ?> >
					<a href="<?=$url;?>?search_word=<?= $search_word;?>&catCode=<?= $catCode;?>&marketCode=<?= $marketCode;?>&=page=<?= $page-1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
				</li>
				<?php for($i=1; $i<=$total_page;$i++){ ?>
				<li <?php if($page==$i) echo 'class="active"'; ?> >
					<a href="<?=$url;?>?search_word=<?= $search_word;?>&catCode=<?= $catCode;?>&marketCode=<?= $marketCode;?>&page=<?= $i?>" > <?= $i;?></a>			
				</li>
				<?php } ?>
				<li <?php if($page==$total_page) echo 'class="disabled"'; ?> >
					<a href="<?=$url;?>?search_word=<?= $search_word;?>&catCode=<?= $catCode;?>&marketCode=<?= $marketCode;?>&page=<?=$page+1?>" aria-labels="Next"><span aria-hidden="true">&raquo;</span></a>
				</li>
			</ul>
			</nav>
			
			
        </div><!-- /.box-body -->
  <div class="box-footer">
      <a href="rpt_prod_stk_pdf.php?sloc=<?=$sloc;?>&catCode=<?=$catCode;?>&search_word=<?=$search_word;?>" class="btn btn-default pull-left"><i class="glyphicon glyphicon-print"></i> Print</a>
    
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
				url: 'product_del_ajax.php',
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
				url: 'product_remove_ajax.php',
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
