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
        $this->Cell(0, 5, 'Return', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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
if( isset($_GET['rtNo']) ){

		$rtNo = $_GET['rtNo'];
		$sql = "SELECT hdr.`rtNo`, hdr.`refNo`, hdr.`returnDate`, hdr.`fromCode`, hdr.toCode, hdr.`statusCode`,  hdr.`remark`
		, hdr.`createTime`, hdr.`createByID`, hdr.`confirmTime`, hdr.`confirmById`, hdr.`approveTime`, hdr.`approveById` 
		, fsl.name as fromName, tsl.name as toName
		, d.userFullname as createByName
		, hdr.confirmTime, cu.userFullname as confirmByName
		, hdr.approveTime, au.userFullname as approveByName
		FROM `rt` hdr
		LEFT JOIN sloc fsl on hdr.fromCode=fsl.code
		LEFT JOIN sloc tsl on hdr.toCode=tsl.code
		left join user d on hdr.createByID=d.userID
		left join user cu on hdr.confirmByID=cu.userID
		left join user au on hdr.approveByID=au.userID
		WHERE 1
		AND hdr.rtNo=:rtNo 					
		ORDER BY hdr.createTime DESC
		LIMIT 1
				
		";
		$stmt = $pdo->prepare($sql);			
		$stmt->bindParam(':rtNo', $rtNo);	
		$stmt->execute();
		$hdr = $stmt->fetch();
		


		$sql = "SELECT dtl.`id`, dtl.`prodItemId`, dtl.`prodId`, dtl.`prodCode`, dtl.`barcode`, dtl.`issueDate`, dtl.`machineId`, dtl.`seqNo`, dtl.`NW`, dtl.`GW`
		, dtl.`qty`, dtl.`packQty`, dtl.`grade`, dtl.`gradeDate`, dtl.`refItemId`, dtl.`itemStatus`, dtl.`remark`, dtl.`problemId`
		, dtl.`returnReasonCode`, dtl.`returnReasonRemark`, dtl.`rtNo` 
		, rrt.name as returnReasonName 
		FROM `rt_detail` dtl	
		LEFT JOIN wh_return_reason_type rrt on rrt.code=dtl.returnReasonCode 
		WHERE 1
		AND dtl.rtNo=:rtNo  
		";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':rtNo', $rtNo);		
		$stmt->execute();
		$rowCount = $stmt->rowCount();

					
						$html ='
							<table class="table table-striped no-margin" >
								  <thead>
								<tr>
									<th style="font-weight: bold;">Return No. :</th>
									<th style="font-weight: bold; text-align: left;">'.$hdr['rtNo'].'</th>
									<th style="font-weight: bold; text-align: right;">From :</th>
									<th>'.$hdr['fromCode'].'-'.$hdr['fromName'].'</th>									
									<th style="font-weight: bold; text-align: right;">Return Date :</th>
									<th>'.$hdr['returnDate'].'</th>
								</tr>
								<tr>
									<th style="font-weight: bold;">Ref. RC No. :</th>
									<th style="text-align: left;">'.$hdr['refNo'].'</th>
									<th style="font-weight: bold; text-align: right;">To :</th>
									<th>'.$hdr['toCode'].'-'.$hdr['toName'].'</th>
									<th colspan="2">
										Remark : '.($hdr['remark']==''?'-':$hdr['remark']).'
									</th>	
									</tr>
								  <tr>
										<th style="font-weight: bold; text-align: center; width: 30px;" border="1">No.</th>
										<th style="font-weight: bold; text-align: center; width: 300px;" border="1">Barcode</th>
										<th style="font-weight: bold; text-align: center; width: 50px;" border="1">Grade</th>
										<th style="font-weight: bold; text-align: center; width: 50px;" border="1">Net<br/>Weight<br/>(kg.)</th>
										<th style="font-weight: bold; text-align: center; width: 50px;" border="1">Gross<br/>Weight<br/>(kg.)</th>										
										<th style="font-weight: bold; text-align: center; width: 50px;" border="1">Qty</th>
										<th style="font-weight: bold; text-align: center; width: 80px;" border="1">Issue Date</th>
									</tr>
								  </thead>
								  <tbody>
							'; 
							
					$row_no = 1; $sumQty=$sumNW=$sumGW=0; while ($row = $stmt->fetch()) { 
						
						
					$html .='<tr>
						<td style="border: 0.1em solid black; text-align: center; width: 30px;">'.$row_no.'</td>
						<td style="border: 0.1em solid black; padding: 10px; width: 300px;">'.$row['barcode'].'<br/><small>'.$row['returnReasonCode'].' : '.$row['returnReasonRemark'].'</small></td>
						<td style="border: 0.1em solid black; text-align: center; width: 50px;">'.$row['grade'].'</td>
						<td style="border: 0.1em solid black; text-align: right; width: 50px;">'.$row['NW'].'</td>
						<td style="border: 0.1em solid black; text-align: right; width: 50px;">'.$row['GW'].'</td>
						<td style="border: 0.1em solid black; text-align: right; width: 50px;">'.number_format($row['qty'],0,'.',',').'</td>
						<td style="border: 0.1em solid black; text-align: center; width: 80px;">'.$row['issueDate'].'</td>
					</tr>';			
												
					$sumQty+=$row['qty'] ; $sumNW+=$row['NW']; $sumGW+=$row['GW'] ;								
					$row_no +=1; }
					//<!--end while div-->	
					
					$html .='<tr>
						<td style="border: 0.1em solid black; text-align: center; width: 30px;"></td>
						<td style="border: 0.1em solid black; text-align: center; padding: 10px; width: 300px;">Total</td>
						<td style="border: 0.1em solid black; text-align: center; width: 50px;"></td>
						<td style="border: 0.1em solid black; text-align: right; width: 50px;">'.number_format($sumNW,2,'.',',').'</td>
						<td style="border: 0.1em solid black; text-align: right; width: 50px;">'.number_format($sumGW,2,'.',',').'</td>
						<td style="border: 0.1em solid black; text-align: right; width: 50px;">'.number_format($sumQty,0,'.',',').'</td>
						<td style="border: 0.1em solid black; text-align: center; width: 80px;"></td>
					</tr>';
					$html .='<tr>
						<td colspan="2"><br/><br/>
							Create by ..............................................................<br/>
							<label style="padding-left: 20px;">'.$hdr['createByName'].' / <small>'.$hdr['createTime'].'</small></label><br/>
							Verify by ..............................................................<br/>
							<label style="padding-left: 20px;">'.$hdr['confirmByName'].' / <small>'.$hdr['confirmTime'].'</small></label><br/>
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
$pdf->Output($rtNo.'_Shelf.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
	?>