<?php
include 'session.php';
include 'inc_helper.php'; 

require_once '../phpexcel/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Prawit Khamnet")
        ->setTitle("Report")
        ->setSubject("Sales Order Pending by Product Report")
        ->setDescription("Sales Order Pending by Product Report2")
        ->setKeywords("Sales Order Pending by Product Report3")
        ->setCategory("Sales Order Pending by Product Report4");
		
$dateFrom = (isset($_GET['dateFrom'])?$_GET['dateFrom']:'');
$dateTo = (isset($_GET['dateTo'])?$_GET['dateTo']:'');

$dateFromYmd=$dateToYmd="";
if($dateFrom<>""){ $dateFromYmd = to_mysql_date($_GET['dateFrom']);	}
if($dateFrom<>""){ $dateToYmd = to_mysql_date($_GET['dateTo']);	}


							
 $sql = "SELECT hdr.soNo
, dtl.prodId, prd.code as prodCode
, sum(dtl.qty) as sumQty
, (SELECT IFNULL(sum(doDtl.qty),0) FROM delivery_header doHdr
	INNER JOIN delivery_detail doDtl ON doDtl.doNo=doHdr.doNo
	INNER JOIN product_item itm ON itm.prodItemId=doDtl.prodItemId 
	WHERE 1=1
	AND doHdr.soNo=hdr.soNo
	AND itm.prodId=dtl.prodId) as sumSentDtl
FROM `sale_header` hdr
INNER JOIN sale_detail dtl ON dtl.soNo=hdr.soNo
LEFT JOIN product prd ON prd.id=dtl.prodId ";				

$sql .= "WHERE 1 
AND hdr.statusCode='P' 
AND hdr.isClose='N' ";
if($dateFrom<>""){ $sql .= " AND hdr.saleDate>='$dateFromYmd' ";	}
if($dateTo<>""){ $sql .= " AND hdr.saleDate<='$dateToYmd' ";	}				
$sql .= "
group by hdr.soNo, dtl.prodId ";

$result = mysqli_query($link, $sql);
$countTotal = mysqli_num_rows($result);

if($countTotal>0){
	// Add Header
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Product Code')
		->setCellValue('B1', 'SO No.')
		->setCellValue('C1', 'Order Qty')
		->setCellValue('D1', 'Sent Qty');
		
	$sql = "SELECT hdr.soNo
	, dtl.prodId, prd.code as prodCode
	, sum(dtl.qty) as sumQty
	, (SELECT IFNULL(sum(doDtl.qty),0) FROM delivery_header doHdr
		INNER JOIN delivery_detail doDtl ON doDtl.doNo=doHdr.doNo
		INNER JOIN product_item itm ON itm.prodItemId=doDtl.prodItemId 
		WHERE 1=1
		AND doHdr.soNo=hdr.soNo
		AND itm.prodId=dtl.prodId) as sumSentDtl
	FROM `sale_header` hdr
	INNER JOIN sale_detail dtl ON dtl.soNo=hdr.soNo
	LEFT JOIN product prd ON prd.id=dtl.prodId ";				
	
	$sql .= "WHERE 1 
	AND hdr.statusCode='P' 
	AND hdr.isClose='N' ";
	if($dateFrom<>""){ $sql .= " AND hdr.saleDate>='$dateFromYmd' ";	}
	if($dateTo<>""){ $sql .= " AND hdr.saleDate<='$dateToYmd' ";	}				
	$sql .= "
	group by hdr.soNo, dtl.prodId ";
	$sql.="ORDER BY soNo desc ";
	$result = mysqli_query($link, $sql);   
					
	$iRow=2; while($row = mysqli_fetch_assoc($result) ){
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)		
		->setCellValue('A'.$iRow, $row['prodCode'])
		->setCellValue('B'.$iRow, $row['soNo'])
		->setCellValue('C'.$iRow, $row['sumQty'])
		->setCellValue('D'.$iRow, $row['sumSentDtl']);
		
		$iRow+=1;
	}
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Data');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="salesOrderPendingByProduct.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter->save('php://output');
exit;