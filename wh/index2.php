<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php 	
	//$year = date('Y');
	//$month = "0";//date('m');
	//if(isset($_GET['year'])) $year = $_GET['year'];
	//if(isset($_GET['month'])) $month = $_GET['month'];
?>
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
        Welcome to Marketing Department
        <small>Mr. <?php echo $s_userFullname; ?> [ ID: <?php echo $_SESSION['userID']; ?>] </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main Page</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12">
               <div class="info-box">
                   <span class="info-box-icon bg-green"><i class="fa fa-cart-plus"></i></span>
                   <div class="info-box-content"> 
                       <?php
                        $sql ="SELECT COUNT(*) as countOrder
									FROM order_header
									WHERE statusCode='P'
									AND year(orderDate)=:year 							
									";
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':year', $year);
						$stmt->execute();
                        $row = $stmt->fetch();
                        ?>
                       <span class="info-box-text"> Number of Orders </span>
                       <span class="info-box-number"><?= number_format($row['countOrder'], 0, '.', ','); ?> <small> Orders</small>.</span>
                       
                   </div><!-- /.info-box-content -->
               </div> <!-- /.info-box -->
            </div> <!-- /.col --> 
            
            <div class="col-md-3 col-sm-6 col-xs-12">
               <div class="info-box">
                   <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
                   <div class="info-box-content"> 
                       <?php
                        $sql ="SELECT SUM(b.total) AS total 
									FROM order_header a
									inner join order_detail b on a.orderNo=b.orderNo
									WHERE statusCode='P'
									AND year(orderDate)=:year 		
									";
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':year', $year);
						$stmt->execute();
                        $row = $stmt->fetch();
                        ?>
                       <span class="info-box-text"> Amount of Orders </span>
                       <span class="info-box-number"><?= number_format($row['total'], 2, '.', ','); ?> <small> Baht</small></span>
                   </div><!-- /.info-box-content -->
               </div> <!-- /.info-box -->
            </div> <!-- /.col --> 
            
            <div class="clearfix visible-sm-block"></div>
            
             <div class="col-md-3 col-sm-6 col-xs-12">
               <div class="info-box">
                   <span class="info-box-icon bg-yellow"><i class="fa fa-calendar"></i></span>
                   <div class="info-box-content"> 
                       <?php						
						$sql ="SELECT IFNULL(SUM(forecast),1) as sumForecast
									FROM target_cust
									WHERE year=:year ".($month<>"0"?"and month=:month":"")."
									";
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':year', $year);
						($month<>"0"?$stmt->bindParam(':month', $month):"");
						$stmt->execute();
                        $row = $stmt->fetch();
						$sumForecast=$row['sumForecast'];
						
						$sql ="SELECT IFNULL(sum(actual),0) as sumActual
									FROM target_cust
									WHERE year=:year ".($month<>"0"?"and month=:month":"")."
									";					
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':year', $year);
						($month<>"0"?$stmt->bindParam(':month', $month):"");
						$stmt->execute();
                        $row = $stmt->fetch();
						$sumActual=$row['sumActual'];
						
						$sql ="SELECT IFNULL(sum(oh.total),0) as sumActualOrder
									FROM order_header oh
									WHERE statusCode='P' 
									AND year(oh.orderDate)=:year ".($month<>"0"?"and month(oh.orderDate)=:month":"")."
									";					
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':year', $year);
						($month<>"0"?$stmt->bindParam(':month', $month):"");
						$stmt->execute();
                        $row = $stmt->fetch();
						$sumActualOrder=$row['sumActualOrder'];
						$sumActual = $sumActual+$sumActualOrder
                        ?>
                       <span class="info-box-text"> Forcast Monthly</span>
					   <span class="info-box-number"><?= number_format($sumForecast, 0, '.', ','); ?> <small> Baht</small></span>
                       <span class="info-box-number"><?= number_format(($sumActual/$sumForecast)*100, 2, '.', ','); ?>%</span>
                   </div><!-- /.info-box-content -->
               </div> <!-- /.info-box -->
			</div> <!-- /.col --> 
			
			<div class="col-md-3 col-sm-6 col-xs-12">
			   <div class="info-box">
                   <span class="info-box-icon bg-red"><i class="fa fa-line-chart"></i></span>
                   <div class="info-box-content"> 
                       <?php
                        $sql ="SELECT IFNULL(SUM(forecast),1) as sumForecast
									FROM target_cust
									WHERE year=:year 
									";
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':year', $year);
						$stmt->execute();
                        $row = $stmt->fetch();
						$sumForecast=$row['sumForecast'];
						
						$sql ="SELECT IFNULL(sum(actual),0) as sumActual
									FROM target_cust
									WHERE year=:year 
									";					
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':year', $year);
						$stmt->execute();
                        $row = $stmt->fetch();
						$sumActual=$row['sumActual'];
						
						$sql ="SELECT IFNULL(sum(oh.total),0) as sumActualOrder
									FROM order_header oh
									WHERE statusCode='P' 
									AND year(oh.orderDate)=:year 
									";					
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':year', $year);
						$stmt->execute();
                        $row = $stmt->fetch();
						$sumActualOrder=$row['sumActualOrder'];
						$sumActual = $sumActual+$sumActualOrder
                        ?>
                       <span class="info-box-text"> Forcast Yearly</span>
					   <span class="info-box-number"><?= number_format($sumForecast, 0, '.', ','); ?> <small> Baht</small></span>
                       <span class="info-box-number"><?= number_format(($sumActual/$sumForecast)*100, 2, '.', ','); ?>%</span>
                   </div><!-- /.info-box-content -->
               </div> <!-- /.info-box -->
            </div> <!-- /.col --> 
            			
         </div> <!-- /.row -->   
  

	<div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Monthly Recap Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
			  
				
                <div class="col-md-8">
					
                  <div id="container" style="width:100%; height:400px;">
				  
					</div>
				  <!-- /.container -->
				  
                </div>
                <!-- /.col -->
				
				
				
				
                <div class="col-md-4">
					<div id="container2" style="width:100%; height:400px;">
				  
					</div>
                  <!-- /.container -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>  
<!-- Day8 00:05:45-->            
    
	
	
	<!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <!-- MAP & BOX PANE -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Visitors Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div id="container3" style="width:100%; height:400px;">
			  
				</div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="row">
            <div class="col-md-6">
              <!-- DIRECT CHAT -->
              <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Direct Chat</h3>

                  <div class="box-tools pull-right">
                    <span data-toggle="tooltip" title="3 New Messages" class="badge bg-yellow">3</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts"
                            data-widget="chat-pane-toggle">
                      <i class="fa fa-comments"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages">
                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">Alexander Pierce</span>
                        <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        Is this template really for free? That's unbelievable!
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->

                    <!-- Message to the right -->
                    <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right">Sarah Bullock</span>
                        <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        You better believe it!
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->

                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">Alexander Pierce</span>
                        <span class="direct-chat-timestamp pull-right">23 Jan 5:37 pm</span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        Working with AdminLTE on a great new app! Wanna join?
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->

                    <!-- Message to the right -->
                    <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right">Sarah Bullock</span>
                        <span class="direct-chat-timestamp pull-left">23 Jan 6:10 pm</span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        I would love to.
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->

                  </div>
                  <!--/.direct-chat-messages-->

                  <!-- Contacts are loaded here -->
                  <div class="direct-chat-contacts">
                    <ul class="contacts-list">
                      <li>
                        <a href="#">
                          <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Image">

                          <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Count Dracula
                                  <small class="contacts-list-date pull-right">2/28/2015</small>
                                </span>
                            <span class="contacts-list-msg">How have you been? I was...</span>
                          </div>
                          <!-- /.contacts-list-info -->
                        </a>
                      </li>
                      <!-- End Contact Item -->
                      <li>
                        <a href="#">
                          <img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Image">

                          <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Sarah Doe
                                  <small class="contacts-list-date pull-right">2/23/2015</small>
                                </span>
                            <span class="contacts-list-msg">I will be waiting for...</span>
                          </div>
                          <!-- /.contacts-list-info -->
                        </a>
                      </li>
                      <!-- End Contact Item -->
                      <li>
                        <a href="#">
                          <img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Image">

                          <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Nadia Jolie
                                  <small class="contacts-list-date pull-right">2/20/2015</small>
                                </span>
                            <span class="contacts-list-msg">I'll call you back at...</span>
                          </div>
                          <!-- /.contacts-list-info -->
                        </a>
                      </li>
                      <!-- End Contact Item -->
                      <li>
                        <a href="#">
                          <img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Image">

                          <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Nora S. Vans
                                  <small class="contacts-list-date pull-right">2/10/2015</small>
                                </span>
                            <span class="contacts-list-msg">Where is your new...</span>
                          </div>
                          <!-- /.contacts-list-info -->
                        </a>
                      </li>
                      <!-- End Contact Item -->
                      <li>
                        <a href="#">
                          <img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Image">

                          <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  John K.
                                  <small class="contacts-list-date pull-right">1/27/2015</small>
                                </span>
                            <span class="contacts-list-msg">Can I take a look at...</span>
                          </div>
                          <!-- /.contacts-list-info -->
                        </a>
                      </li>
                      <!-- End Contact Item -->
                      <li>
                        <a href="#">
                          <img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Image">

                          <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Kenneth M.
                                  <small class="contacts-list-date pull-right">1/4/2015</small>
                                </span>
                            <span class="contacts-list-msg">Never mind I found...</span>
                          </div>
                          <!-- /.contacts-list-info -->
                        </a>
                      </li>
                      <!-- End Contact Item -->
                    </ul>
                    <!-- /.contatcts-list -->
                  </div>
                  <!-- /.direct-chat-pane -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <form action="#" method="post">
                    <div class="input-group">
                      <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                      <span class="input-group-btn">
                            <button type="button" class="btn btn-warning btn-flat">Send</button>
                          </span>
                    </div>
                  </form>
                </div>
                <!-- /.box-footer-->
              </div>
              <!--/.direct-chat -->
            </div>
            <!-- /.col -->

            <div class="col-md-6">
              <!-- USERS LIST -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Sales Member</h3>					
                  <div class="box-tools pull-right">
					<?php
						$sql_smc = "
									SELECT IFNULL(COUNT(*),0) AS countSM FROM `salesman` WHERE `statusCode`='A'
									";
						$result_smc = mysqli_query($link, $sql_smc);
						$row = mysqli_fetch_assoc($result_smc)
					?>
                    <span class="label label-danger"><?= $row['countSM']; ?> Members</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
					<?php
						$sql_smm = "
									SELECT `ID`, `name`, `surname`, `photo`, `positionName`, `mobileNo` 
									FROM `salesman` WHERE `statusCode`='A'
									";
						$result_smm = mysqli_query($link, $sql_smm);
						
					?>
                  <ul class="users-list clearfix">
					<?php
						$countSalesmanLi = 0;
						while($row = mysqli_fetch_assoc($result_smm)){		
						?>
							<li>
							  <img src="dist/img/<?= $row['photo']; ?>" alt="User Image">
							  <a class="users-list-name" href="salesman_view.php?id=<?= $row['ID']; ?>"><?= $row['name'].' '.$row['surname']; ?></a>
							  <a class="users-list-name" href="#"><?= $row['positionName']; ?></a>
							  <!--<span class="users-list-date"><?= $row['positionName']; ?></span>-->
							</li>
						<?php
							$countSalesmanLi += 1;
							if($countSalesmanLi == 8){ break; }
						}						
					?>
                  </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="salesmans_view.php" class="uppercase">View All Sales</a>
                </div>
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			<?php
					$sql = "SELECT a.`id`, a.`orderNo`, a.`orderDate`, a.`custCode`, a.`smCode`, a.`statusCode`, a.`createTime`
							,b.custName
							,c.name as smName
							FROM `order_header` a 
							INNER JOIN `customer` b on a.`custCode`= b.code
							INNER JOIN `salesman` c on a.`smCode`= c.code
							WHERE 1

							ORDER BY a.`createTime` DESC
							LIMIT 10
							";
					$result = mysqli_query($link, $sql);
					
				?>
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Item</th>
					<th>Salesman</th>
                    <th>Status</th>
                    <th>Create Time</th>
                  </tr>
                  </thead>
                  <tbody>
				   <?php while ($row = mysqli_fetch_assoc($result)) { 
					$statusName = '<label class="label label-info">Being</label>';
					switch($row['statusCode']){
						case 'C' : $statusName = '<label class="label label-primary">Confirmed</label>'; break;
						case 'P' : $statusName = '<label class="label label-success">Approved</label>'; break;
						default : 						
					}
					?>
                  <tr>
                    <td><a href="order_view.php?id=<?=$row['id'];?>&orderNo=<?=$row['orderNo'];?>" target="_blank"><?= $row['id']; ?></a></td>
					<td><?= $row['custName']; ?></td>
					<td><?= $row['smName']; ?></td>
					<td>
						<?=$statusName;?>
					</td>
					<td><a href="order_view.php?id=<?=$row['id'];?>&orderNo=<?=$row['orderNo'];?>" target="_blank"><?= $row['createTime']; ?></a></td>
                </tr>
                <?php  } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4">
          <!-- TOP 10 PRODUCT LIST -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Top 10 Products</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<?php	
					$sql = "SELECT 
							od.prodCode as id, pd.`prodNameNew` as prodName
							,IFNULL(SUM(od.qty),0) as qty
							,IFNULL(SUM(od.netTotal),0) as netTotal		
							, pd.prodDesc, pd.prodPrice, pd.photo
							FROM order_detail od
							left join product pd on od.prodCode=pd.code 
							inner join order_header oh on od.orderNo=oh.orderNo and oh.statusCode='P'							
								AND year(oh.orderDate)=:year ".
								($month<>"0"?"and month(oh.orderDate)=:month":"")."
							GROUP BY od.prodCode, pd.prodNameNew
							ORDER BY 4 DESC 
							LIMIT 10							
							";
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':year', $year);
					($month<>"0"?$stmt->bindParam(':month', $month):"");
					$stmt->execute();	
				?>
		
				<div class="table-responsive">
				<table class="table no-margin">
				  <thead>
				  <tr>
					<th>No.</th>
					<th>Item Name</th>
					<th>Qty</th>
					<th>Amount</th>
				  </tr>
				  </thead>
				  <tbody>
				  <?php $row_code = 1; while ($row = $stmt->fetch()) { ?>
				  <tr>
					<td>
						 <?= $row_code; ?>
					</td>
					<td>
						 <a target="_blank" href="product_view.php?id=<?= $row['id'];?>" ><?= $row['prodName']; ?></a>
					</td>
					<td>
						 <?= number_format($row['qty'],0,'.',','); ?>
					</td>
					<td>
						 <?= number_format($row['netTotal'],2,'.',','); ?>
					</td>
				</tr>
				<?php $row_code+=1; } ?>
				  </tbody>
				</table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
		  
		  
		  <!-- TOP 10 PRODUCT LIST -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Top 10 Products</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<?php	
					$sql = "SELECT 
							c.id, oh.custCode, c.custName
							,IFNULL(SUM(oh.total),0) as netTotal		
							FROM order_header oh 
							INNER JOIN customer c on oh.custCode=c.code
							WHERE oh.statusCode='P'							
							AND year(oh.orderDate)=:year ".
							($month<>"0"?"and month(oh.orderDate)=:month":"")."
							GROUP BY oh.custCode, c.custName
							ORDER BY 4 DESC 
							LIMIT 10							
							";
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':year', $year);
					($month<>"0"?$stmt->bindParam(':month', $month):"");
					$stmt->execute();	
				?>
		
				<div class="table-responsive">
				<table class="table no-margin">
				  <thead>
				  <tr>
					<th>No.</th>
					<th>Customer Name</th>
					<th>Amount</th>
				  </tr>
				  </thead>
				  <tbody>
				  <?php $row_code = 1; while ($row = $stmt->fetch()) { ?>
				  <tr>
					<td>
						 <?= $row_code; ?>
					</td>
					<td>
						 <a target="_blank" href="customer_view.php?id=<?= $row['id'];?>" ><?= $row['custName']; ?></a>
					</td>
					<td>
						 <?= number_format($row['netTotal'],2,'.',','); ?>
					</td>
				</tr>
				<?php $row_code+=1; } ?>
				  </tbody>
				</table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
		  
		  
		  

          <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recently Added Products</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
			<div class="box-body">
				<ul class="products-list product-list-in-box">
				<?php	
					$sql = "SELECT 
							c.ID, c.`prodNameNew` as prodName, prodDesc, prodPrice, photo
							FROM product c where ID > 2000
							LIMIT 5							
							";
					$result = mysqli_query($link, $sql);  
					$row = mysqli_fetch_assoc($result);
				?>
				  <?php $row_code = 1; while ($row = mysqli_fetch_assoc($result)) { ?>
				  <li class="item">
                  <div class="product-img">
                    <img src="dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title"><?= $row['prodName']; ?>
                      <span class="label label-warning pull-right"><?= $row['prodPrice']; ?></span></a>
                    <span class="product-description">
                          <?= $row['prodDesc']; ?>
                        </span>
                  </div>
                </li>
				<!-- /.item -->
				<?php $row_code+=1; } ?>
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row second box col8 & col 4 -->
	  
	  
	
	
	
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
<!-- Hightchart -->
<script src="plugins/highcharts-5.0.12/code/highcharts.js"></script>
<script src="plugins/highcharts-5.0.12/code/modules/exporting.js"></script>




<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>


 <?php
        $sql_rpt = "SELECT abb_eng as `month`,sum(`forecast`) as forecast, sum(`actual`) as actual 
					FROM month a
					left join `target_cust` b on a.id=b.month
					WHERE 1
					and b.`year`=".$year." 
					group by `month`
					";
        $result_rpt = mysqli_query($link, $sql_rpt);
        $arrMonth = array();
        $arrForecast = array();
        $arrActual = array();
        while($row = mysqli_fetch_assoc($result_rpt)){
            $arrMonth[] = $row['month'];
            $arrForecast[] = $row['forecast'];
			$arrActual[] = $row['actual'];
        }
  ?>
<script>
    $(function () { 
    var myChart = Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
		credits: {
			enabled: false
		},
        data: {
            decimalPoint: "."
        },
        title: {
            text: 'Forecast VS Actual '+<?php echo $year; ?>
        },
		tooltip: {
			pointFormat: '{series.name} <b>{point.y:,.0f}</b> Baht'
		},
		plotOptions: {
			area: {
				//pointStart: 1940,
				marker: {
					enabled: false,
					symbol: 'circle',
					radius: 2,
					states: {
						hover: {
							enabled: true
						}
					}
				}
			}
		},
        xAxis: {
            
            //categories: ['Apples', 'Bananas', 'Oranges'],
            categories: [<?php echo "'" . implode("','", $arrMonth) . "'"; ?>]
                        //'prod5','prod6','prod7'
        },
        yAxis: {
            title: {
                text: 'บาท (1,000)'
            }
        },
        series: [{
            name: 'Forecast',
            data: [<?php echo implode(",", $arrForecast); ?>],
            //data: [1, 0, 4]
            dataLabels: {
                //enabled: true,
                //format: '{y} ชิ้น'
            }
        },
             {
                name: 'Actual',
                data: [<?php echo implode(",", $arrActual); ?>],
             }
        ]
    });
});
</script>

<?php
        $sql = "SELECT a.name, IFNULL(sum(b.forecast),0) as forecast
					, (SELECT IFNULL(sum(oh.total),0) FROM order_header oh WHERE statusCode='P'
							AND oh.smCode= a.code) as actual
					FROM salesman a
					inner join customer cm on cm.smCode=a.code 
					inner join target_cust b on cm.code=b.custCode 
					
					and b.year=:year  
					group by a.name
					";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':year', $year);
		//($month<>""?$stmt->bindParam(':month', $month):"");
		$stmt->execute();
        $xForecast = array();
		$xActual = array();
		$xActualPercent = array();
        $ySalesman = array();
        while($row = $stmt->fetch()){
			$fc = $row['forecast'];
			$at = $row['actual'];
			$atper = $at/$fc;
            $xForecast[] = $row['forecast'];
			$xActual[] = $row['actual'];
			$xActualPercent[] = $row['actual']/$row['forecast']*100;
            $ySalesman[] = $row['name'];
        }
  ?>
<script>
    $(function () { 
    var myChart2 = Highcharts.chart('container2', {
        chart: {
            type: 'bar'
        },
		credits: {
			enabled: false
		},
        data: {
            decimalPoint: "."
        },
        title: {
            text: 'Salesman Actual (%) '+<?php echo $year; ?>
        },
		tooltip: {
			pointFormat: '{categories} <b>{point.y:,.0f}</b> %'
		},
		legend: {
			enabled: false,
		},
        xAxis: {
            categories: [<?php echo "'" . implode("','", $ySalesman) . "'"; ?>]
        },
        yAxis: {
            title: {
                text: '%'
            },
			visible: false
        },
        series: [					
					{
					name: 'PerActual',
					data: [<?php echo implode(",", $xActualPercent); ?>],
					dataLabels: {
							enabled: true,
							format: '{point.x.name} {y:,.2f} %'
						}
					}
				]
    });
});
</script>




<?php
        $sql_rpt = "SELECT 
					a.`name`, count(*) as countVisit
					FROM `salesman` a 
					left join `visit_customer` b on a.code=b.smCode
					where 1
					and year(visitDate) = ".$year."
					and a.statusCode='A'
					group by a.`name`
					";
        $result_rpt = mysqli_query($link, $sql_rpt);
        $xSalesman = array();
		$yCountVisit = array();
        while($row = mysqli_fetch_assoc($result_rpt)){			
            $xSalesman[] = $row['name'];
			$yCountVisit[] = $row['countVisit'];
        }
  ?>
<script>
    $(function () { 
    var myChart3 = Highcharts.chart('container3', {
        chart: {
            type: 'column'
        },
		credits: {
			enabled: false
		},

        data: {
            decimalPoint: "."
        },
        title: {
            text: 'Visitor Customer '+<?php echo $year; ?>
        },
		tooltip: {
			pointFormat: '{categories} Visit <b>{point.y}</b> time(s)'
		},
		legend: {
			enabled: false,
		},
        xAxis: {
            categories: [<?php echo "'" . implode("','", $xSalesman) . "'"; ?>]
        },
        yAxis: {
            title: {
                text: 'Visit count'
            },
			visible: true
        },
        series: [					
					{
					name: 'CountVisit',
					data: [<?php echo implode(",", $yCountVisit); ?>],
					dataLabels: {
							enabled: true,
							format: '{point.x.name} {y} times.'
						}
					}
				]
    });
});
</script>


</html>