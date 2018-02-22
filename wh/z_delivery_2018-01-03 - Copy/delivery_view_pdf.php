<?php

include('session.php');
//include('prints_function.php');
//include('inc_helper.php');

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
		// Set font
		$this->SetFont('THSarabun', '', 16, '', true);
		// Title
        
		//$this->SetY(11);			
		//if($this->page != 1){
			$this->Cell(0, 5, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
			//$this->Cell(0, 5, '- '.$this->getAliasNumPage().' -', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		//}
		 // Logo
        //$image_file = '../asset/img/logo-asia-kangnam.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		$this->SetY(11);	
		$this->Cell(0, 5, 'Asia Kungnum CO.,LTD', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(5);
        $this->Cell(0, 5, 'Delivery Order', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        ///$this->SetY(-15);
        // Set font
        $this->SetFont('THSarabun', '', 14, '', true);
        // Page number
		$tmp = date('Y-m-d H:i:s');
		//$tmp = to_thai_short_date_fdt($tmp);
		$this->Cell(0, 10,'Print : '. $tmp, 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Prawit Khamnet');
$pdf->SetTitle('PDF');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

//remove header
//$pdf->setPrintHeader(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins (left, top, right)
//$pdf->SetMargins(24, 26, 30);	//หน้า ๓ บนถึงตูดเลขหน้า ๒ ตูดเลขหน้าถึงตูดบรรทัดแรก ๑.๕
$pdf->SetMargins(20, 20, 10);	//หน้า ๓ บนถึงตูดเลขหน้า ๒ ตูดเลขหน้าถึงตูดบรรทัดแรก ๑.๕
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('THSarabun', '', 14, '', true);













// Set some content to print
if( isset($_GET['doNo']) ){
	$doNo = $_GET['doNo'];
	
	$pdf->SetTitle($doNo);
	
$sql = "
SELECT dh.`doNo`, dh.`soNo`, dh.`ppNo`, oh.`poNo`
, dh.`deliveryDate`, dh.`remark`
, dh.`statusCode`, dh.`createTime`, dh.`createByID`, dh.`updateTime`, dh.`updateById`
, dh.`confirmTime`, dh.`confirmById`, dh.`approveTime`, dh.`approveById`
, oh.`custCode`, oh.`smCode`
, ct.custName, ct.custAddr
, concat(sm.name, '  ', sm.surname) as smFullname 
, uca.userFullname as createByName, ucf.userFullname as confirmByName, uap.userFullname as approveByName
FROM delivery_header dh 
INNER JOIN prepare pp on pp.ppNo=dh.ppNo 
INNER JOIN picking pk on pk.pickNo=pp.pickNo 
INNER JOIN sale_header oh on pk.soNo=oh.soNo 
LEFT JOIN customer ct on ct.code=oh.custCode
LEFT JOIN salesman sm on sm.code=oh.smCode
LEFT JOIN user uca on uca.userID=dh.createByID					
LEFT JOIN user ucf on ucf.userID=dh.confirmById
LEFT JOIN user uap on uap.userID=dh.approveById
WHERE 1
AND dh.doNo=:doNo
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':doNo', $doNo);	
$stmt->execute();
$hdr = $stmt->fetch();
$doNo = $hdr['doNo'];
$ppNo = $hdr['ppNo'];
$soNo = $hdr['soNo'];


$sql = "SELECT COUNT(id) as rowCount FROM delivery_detail
		WHERE doNo=:doNo 
			";						
$stmt = $pdo->prepare($sql);	
$stmt->bindParam(':doNo', $hdr['doNo']);
$stmt->execute();	
$rowCount = $stmt->fetch(PDO::FETCH_ASSOC);


$sql = "
SELECT dd.`id`, dd.`prodCode`, dd.`qty`
, pd.prodName, pd.prodDesc, pd.salesUom
, IFNULL(SUM(sd.qty),0) as salesQty 
, (SELECT IFNULL(SUM(dd.qty),0) FROM delivery_header dh 
	LEFT JOIN delivery_detail dds on dh.doNo=dds.doNo
   	WHERE dh.soNo=oh.soNo AND dds.prodCode=dd.prodCode and dh.statusCode='P' ) as sentQty
, IFNULL(SUM(dd.qty),0) as deliveryQty 
FROM delivery_detail dd
INNER JOIN delivery_header dh on dh.doNo=dd.doNo 
INNER JOIN sale_header oh on dh.soNo=oh.soNo 
INNER JOIN `sale_detail` sd on oh.soNo=sd.soNo AND sd.prodCode=dd.prodCode 	
LEFT JOIN product pd on dd.prodCode=pd.code 
WHERE 1
AND dh.doNo=:doNo 

ORDER BY dd.`id`, dd.`prodCode`, dd.`qty`, pd.prodName, pd.prodDesc, pd.salesUom
";
$stmt = $pdo->prepare($sql);	
$stmt->bindParam(':doNo', $hdr['doNo']);
$stmt->execute();

					
						$html ='
							<table class="table table-striped no-margin" >
								  <thead>									
								  <tr>
									<th style="font-weight: bold;">Customer :</th>
									<th style="font-weight: bold; text-align: left;">'.$hdr['custCode'].':'.$hdr['custName'].'</th>
									<th style="font-weight: bold;">Ref No. :</th>
									<th style="text-align: left;">'.$hdr['soNo'].'/'.$hdr['poNo'].'</th>
									<th style="font-weight: bold; text-align: right;">Delivery Date :</th>
									<th>'.$hdr['deliveryDate'].'</th>
								</tr>
								<tr>
									<th style="font-weight: bold;">Address :</th>
									<th style="text-align: left;">'.$hdr['custAddr'].'</th>
									<th style="font-weight: bold; text-align: right;">Customer :</th>
									<th>'.$hdr['custCode'].'-'.$hdr['custName'].'</th>
									<th style="font-weight: bold; text-align: right;">Salesman :</th>
									<th>'.$hdr['smFullname'].'</th>
								</tr>
								<tr>
									<th colspan="6">
										Remark : '.($hdr['remark']==''?'-':$hdr['remark']).'
									</th>	
								</tr>
								  <tr>
										<th style="font-weight: bold; text-align: center; width: 30px;" border="1">No.</th>
										<th style="font-weight: bold; text-align: center; width: 250px;" border="1">Product</th>
										<th style="font-weight: bold; text-align: center; width: 50px;" border="1">Order Qty</th>
										<th style="font-weight: bold; text-align: center; width: 50px;" border="1">Delivery sumQty</th>
										<th style="font-weight: bold; text-align: center; width: 80px;" border="1">Remark</th>
									</tr>
								  </thead>
								  <tbody>
							'; 
							
					$row_no = 1; $sumQty=$sumNW=$sumGW=0; while ($row = $stmt->fetch()) { 
						
						
					$html .='<tr>
						<td style="border: 0.1em solid black; text-align: center; width: 30px;">'.$row_no.'</td>
						<td style="border: 0.1em solid black; padding: 10px; width: 250px;">'.$row['prodCode'].'</td>
						<td style="border: 0.1em solid black; text-align: center; width: 50px;">'.number_format($row['salesQty'],2,'.',',').'</td>
						<td style="border: 0.1em solid black; text-align: right; width: 50px;">'.number_format($row['deliveryQty'],2,'.',',').'</td>
						<td></td>
					</tr>';									
					$row_no +=1; }
					//<!--end while div-->	
					$html .='<tr>
						<td colspan="2"><br/><br/>
							Create by ..............................................................<br/>
							<label style="padding-left: 20px;">'.$hdr['createByName'].' / <small>'.$hdr['createTime'].'</small></label><br/>
							Verify by ..............................................................<br/>
							<label style="padding-left: 20px;">'.$hdr['confirmByName'].' / <small>'.$hdr['confirmTime'].'</small></label><br/>
							Driver by ..............................................................<br/>
							<label style="padding-left: 20px;">.............................................................. <small></small></label><br/>
						</td>
						
						<td colspan="6" style="text-align: left;"><br/><br/>							
							Approve by ..............................................................<br/>
							<label style="padding-left: 20px;">'.$hdr['approveByName'].' / <small>'.$hdr['approveTime'].'</small></label><br/>
						</td>
						
					</tr>';
					
					$html .='</tbody></table>';
						
					$pdf->AddPage('P');
					$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
					}
					//<!--if isset $_GET['from_date']-->
		
		 
		   

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($ppNo.'_Shelf.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
	?>