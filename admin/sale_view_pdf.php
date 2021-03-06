<?php

include('session.php');
//include('prints_function.php');
// include('inc_helper.php');

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
			$this->SetFont('Times', '', 10, '', true);
			$this->Cell(0, 5, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
			//$this->Cell(0, 5, '- '.$this->getAliasNumPage().' -', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		//}
		 // Logo
        //$image_file = '../asset/img/logo-asia-kangnam.jpg';		
		//$img = file_get_contents('img\logo-asia-kangnam.jpg');
        //$this->Image($image_file, 10, 10, 15, 15, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
		
		/*$this->SetFont('Times', 'B', 16, '', true);		
		$this->SetY(11);	
		$this->Cell(0, 5, 'Asia Kungnum Co.,Ltd.', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(7);
		$this->SetFont('Times', 'B', 14, '', true);	
        $this->Cell(0, 5, 'Sales Order', 0, false, 'C', 0, '', 0, false, 'M', 'M');*/
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
		$this->Cell(0, 10,'FM-MS-003; rev.01', 0, false, 'L', 0, '', 0, false, 'T', 'M');
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
if( isset($_GET['soNo']) ){			
			$pdf->SetTitle($_GET['soNo']);
						
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
			, b.code as custCode, b.name as custName, b.addr1 as custAddr1, b.addr2 as custAddr2, b.addr3 as custAddr3, b.zipcode, b.tel as custTel, b.fax as custFax, b.locationCode as custLocCode 
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
	   		
			$sql = "
			SELECT COUNT(*) as countTotal 
			FROM `sale_detail` a
			LEFT JOIN product b on b.id=a.prodId 
			WHERE 1
			AND a.`soNo`=:soNo 
			ORDER BY a.createTime
			";
			$stmt = $pdo->prepare($sql);	
			$stmt->bindParam(':soNo', $hdr['soNo']);
			$stmt->execute();
			$row = $stmt->fetch();
			$countTotal = $row['countTotal'];
			
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
			
			
			
			
			//Loop all item
			$iRow=0;
			$row_no = 1;  while ($row = $stmt->fetch()) { 
				if($iRow==0){
					//head 
					$pdf->AddPage('P');
			
					$image_file = '../asset/img/logo-asia-kangnam.jpg';	
					$img = file_get_contents($image_file);
					$pdf->Image('@' . $img);
					
					$pdf->SetFont('THSarabun', '', 10, '', true);
					
					//$pdf->RadioButton('drink', 5, array('readonly' => 'true'), array(), 'Water');
					$pdf->Cell(120, 0, '');
					$pdf->RadioButton('sourceType', 5, array(), array(), 'ที่มาสินค้า', ($hdr['suppTypeFact']==0?false:true));
					$pdf->Cell(30, 5, 'สินค้าผลิตในโรงงาน');
					$pdf->Ln(4);
					
					$pdf->Cell(120, 0, '');			
					$pdf->RadioButton('sourceType', 5, array(), array(), 'ที่มาสินค้า', ($hdr['suppTypeImp']==0?false:true));
					$pdf->Cell(30, 5, 'สินค้านำเข้าจากต่างประเทศ');
					$pdf->Ln(4);
					
					$pdf->Cell(100, 0, '');
					$pdf->RadioButton('productType', 5, array(), array(), 'สินค้าเก่าใหม่', ($hdr['prodTypeOld']==0?false:true));
					$pdf->Cell(40, 5, 'สินค้าเก่า (Current Product)');
					$pdf->RadioButton('productType', 5, array(), array(), 'สินค้าเก่าใหม่', ($hdr['prodTypeNew']==0?false:true));
					$pdf->Cell(40, 5, 'สินค้าใหม่ (New Product)');
					$pdf->Ln(4);
					
					$pdf->Cell(100, 0, '');
					$pdf->RadioButton('customerType', 5, array(), array(), 'ลูกค้าเก่าใหม่', ($hdr['custTypeOld']==0?false:true));
					$pdf->Cell(40, 5, 'ลูกค้าเก่า (Current Customer)');
					$pdf->RadioButton('customerType', 5, array(), array(), 'ลูกค้าเก่าใหม่', ($hdr['custTypeNew']==0?false:true));
					$pdf->Cell(40, 5, 'ลูกค้าใหม่ (New Customer)');
					$pdf->Ln(10);
					
					$pdf->SetFont('THSarabun', '', 12, '', true);
					$pdf->SetFillColor(255,255,255); //255,255,255 white
					
					$pdf->Cell(50, 0, 'SALES ORDER FORM (ใบสั่งขาย)', 1, $ln=0, 'C', 0, '', 0, false, 'T', 'B');
					$pdf->Cell(50, 0, '');
					$pdf->Cell(45, 0, 'รหัสลูกค้า (Customer Code) : ', 0, $ln=0, 'L', 0, '', 0, false, 'T', 'B');			
					$pdf->Cell(30, 0, $hdr['custCode'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
					$pdf->Ln(8);
					
					$pdf->Cell(45, 0, 'ชื่อลูกค้า (Customer Name) : ', 0, 0, 'L', 0, '', 0, false, 'T', 'B');
					$pdf->Cell(55, 0, $hdr['custName'], 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
					$pdf->Cell(25, 0, 'วันที่ (Date) : ', 0, $ln=0, 'L', 0, '', 0, false, 'T', 'B');
					$pdf->Cell(50, 0, $hdr['saleDate'], 'B', 0, 'L', 1, 'B', 0, false, 'T', 'B');
					$pdf->Ln(6);
					
					$pdf->Cell(45, 0, 'ที่อยู่เปิด Invoice (Destination) : ', 0, 0, 'L', 0, '', 0, false, 'T', 'B');
					$pdf->Cell(55, 0, '', 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
					$pdf->Cell(25, 0, 'SO No. : ', 0, $ln=0, 'L', 0, '', 0, false, 'T', 'B');
					$pdf->Cell(50, 0, $hdr['soNo'], 'B', 0, 'L', 1, 'B', 0, false, 'T', 'B');
					$pdf->Ln(6);
											
					$pdf->Cell(100, 0, $hdr['custAddr1'], 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
					$pdf->Cell(25, 0, 'PO No. : ', 0, $ln=0, 'L', 0, '', 0, false, 'T', 'B');
					$pdf->Cell(50, 0, $hdr['poNo'], 'B', 0, 'L', 1, 'B', 0, false, 'T', 'B');
					$pdf->Ln(6);
					
					$pdf->Cell(100, 0, $hdr['custAddr2'].$hdr['custAddr3'], 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
					$pdf->Cell(25, 0, 'PI No. : ', 0, $ln=0, 'L', 0, '', 0, false, 'T', 'B');
					$pdf->Cell(50, 0, $hdr['piNo'], 'B', 0, 'L', 1, 'B', 0, false, 'T', 'B');
					$pdf->Ln(6);
					
					$pdf->Cell(25, 0, 'สินค้า (Product) : ', 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
					$pdf->CheckBox('productType', 5, ($hdr['prodGFC']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'GLASS FIBER CLOTH');
					$pdf->CheckBox('productType', 5, ($hdr['prodGFM']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'GLASS FIBER MESH');
					$pdf->CheckBox('productType', 5, ($hdr['prodGFT']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'GLASS FIBER TAPE');
					$pdf->Ln(6);
					
					$pdf->Cell(25, 0, '');
					$pdf->CheckBox('productType', 5, ($hdr['prodSC']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'SILICA CLOTH');
					$pdf->CheckBox('productType', 5, ($hdr['prodCFC']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'CABON FIBER CLOTH');
					$pdf->CheckBox('productType', 5, ($hdr['prodEGWM']==0?false:true),array(), array());
					$pdf->Cell(40, 5, 'E-GLASS WOOL MAT');
					$pdf->Ln(6);
					
					$pdf->Cell(25, 0, '');
					$pdf->CheckBox('productType', 5, ($hdr['prodGT']==0?false:true),array(), array());
					$pdf->Cell(40, 5, 'GLASS TISSUE');
					$pdf->CheckBox('productType', 5, ($hdr['prodCSM']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'CHOPPED STRAND MAT');
					$pdf->CheckBox('productType', 5, ($hdr['prodWR']==0?false:true),array(), array());
					$pdf->Cell(40, 5, 'WOVEN ROVING');
					$pdf->Ln(6);
					
					$html ='
							<table class="table table-striped no-margin" style="width:100%;"  >
								<thead>	
									<tr>
										<th style="font-weight: bold; text-align: center; width: 150px;" border="1">Product Name</th>
										<th style="font-weight: bold; text-align: center; width: 150px;" border="1">Product Code</th>
										<th style="font-weight: bold; text-align: center; width: 150px;" border="1">Specification</th>								
										<th style="font-weight: bold; text-align: center; width: 150px;" border="1">Qty</th>								
									</tr>
								</thead>
								  <tbody>
							'; 
				}
				$html .='<tr>
							<td style="border: 0.1em solid black; padding: 10px; width: 150px;"> '.$row['prodName'].'</td>
							<td style="border: 0.1em solid black; padding: 10px; width: 150px;"> '.$row['prodCode'].'</td>
							<td style="border: 0.1em solid black; padding: 10px; width: 150px;"> '.''.'</td>
							<td style="border: 0.1em solid black; text-align: right; width: 150px;">'.number_format($row['qty'],0,'.',',').' '.$row['prodUomCode'].'</td>						
						</tr>';	
				if($iRow==8){
					//foot document.
					$html .='</tbody></table>';
					$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
					
					$pdf->CheckBox('stockType', 5, ($hdr['prodStkInStk']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'สินค้ามีในสต๊อกทั้งหมด / บางส่วน');
					$pdf->CheckBox('stockType', 5, ($hdr['prodStkOrder']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'สินค้าสั่งผลิต');
					$pdf->CheckBox('stockType', 5, ($hdr['prodStkOther']==0?false:true), array(), array());
					$pdf->Cell(40, 5, 'อื่นๆ');
					$pdf->Ln(6);
					
					
					$iRow=0;
				}
				//Loop item per page
				$iRow+=1;
			}//end loop all item
			
			if($iRow<>9){
				for($iRowRemain=$iRow; $iRowRemain<=8; $iRowRemain++){
					$html .='<tr>
							<td style="border: 0.1em solid black; padding: 10px; width: 150px;"> '.''.'</td>
							<td style="border: 0.1em solid black; padding: 10px; width: 150px;"> '.''.'</td>
							<td style="border: 0.1em solid black; padding: 10px; width: 150px;"> '.''.'</td>
							<td style="border: 0.1em solid black; text-align: right; width: 150px;">'.''.'</td>						
						</tr>';	
				}
			}
			
			$html .='</tbody></table>';
			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
			//$pdf->Ln(2);
			
			$pdf->CheckBox('stockType', 5, ($hdr['prodStkInStk']==0?false:true), array(), array());
			$pdf->Cell(40, 5, 'สินค้ามีในสต๊อกทั้งหมด / บางส่วน');
			$pdf->CheckBox('stockType', 5, ($hdr['prodStkOrder']==0?false:true), array(), array());
			$pdf->Cell(40, 5, 'สินค้าสั่งผลิต');
			$pdf->CheckBox('stockType', 5, ($hdr['prodStkOther']==0?false:true), array(), array());
			$pdf->Cell(10, 5, 'อื่นๆ');
			$pdf->Cell(30, 0, $hdr['prodStkRem'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			//การบรรจุ (Packing) : 	□ มี LOGO AK	□ ไม่มี LOGO AK	□ อื่นๆ (Other) ____________________________
			$pdf->Cell(25, 5, 'การบรรจุ (Packing) : ', 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
			$pdf->CheckBox('packingType', 5, ($hdr['packTypeAk']==0?false:true), array(), array());
			$pdf->Cell(40, 5, 'มี LOGO AK');
			$pdf->CheckBox('packingType', 5, ($hdr['packTypeNone']==0?false:true), array(), array());
			$pdf->Cell(40, 5, 'ไม่มี LOGO');
			$pdf->CheckBox('packingType', 5, ($hdr['packTypeOther']==0?false:true), array(), array());
			$pdf->Cell(20, 5, 'อื่นๆ (Other)');
			$pdf->Cell(30, 5, $hdr['packTypeRem'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			//กรณีส่งในประเทศ (Domestic) วันที่ รับ – ส่ง สินค้า (Delivery Date)
			
			$pdf->Cell(40, 5, 'กรณีส่งในประเทศ (Domestic)');
			$pdf->Cell(45, 5, 'วันที่ รับ – ส่ง สินค้า (Delivery Date) : ');
			$pdf->Cell(30, 5, ($hdr['custLocCode']=='L'?$hdr['deliveryDate']:''), 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			//กรณีส่งต่างประเทศ (Export)   วันที่ Load _______________  by   □ LCL	□ FCL : 1x20’ 	□ FCL : 1x40’
			$pdf->Cell(40, 5, 'กรณีส่งต่างประเทศ (Export)');
			$pdf->Cell(15, 5, 'วันที่ Load : ');
			$pdf->Cell(30, 5, ($hdr['custLocCode']=='E'?$hdr['deliveryDate']:''), 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Cell(5, 5, ' by ');
			$shipByStr = '';
			if($hdr['shipByLcl']==0){}else{$shipByStr.=($shipByStr==""?"":",  ")."LCL";}
			if($hdr['shipByFcl1x20']==0){}else{$shipByStr.=($shipByStr==""?"":",  ")."FCL 1x20'";}
			if($hdr['shipByFcl1x40']==0){}else{$shipByStr.=($shipByStr==""?"":",  ")."FCL 1x40'";}
			$pdf->Cell(50, 5, $shipByStr, 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			$pdf->Cell(40, 5, '');
			$pdf->Cell(23, 5, 'Shipping Mark : ');
			if($hdr['shippingMarksFilePath']==""){				
				$pdf->Cell(110, 5, '', 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
			}else{	
				$pdf->Cell(110, 5, $hdr['shippingMarksName'], 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
				
				$image_file = '../asset/img/shippingMarks/'.$hdr['shippingMarksFilePath'];
				$img = file_get_contents($image_file);
				// Image example with resizing
				//$pdf->Image('images/image_demo.jpg', $x, $y, $w, $h, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
				//$pdf->Image('@'.$img);
				//image width=150px;
				$pdf->Image('@' . $img,80,185,'JPG');//, '', '', false, 300, '', false, false, 0, '', false, false);
				//$pdf->Cell(110, 5, 'Bfd', 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
			}
			$pdf->Ln(45);
						
			$pdf->Cell(25, 5, 'ราคา (Price) : ');
			$pdf->CheckBox('priceType', 5, ($hdr['priceOnOrder']==0?false:true), array(), array());
			$pdf->Cell(40, 5, 'ตามใบสั่งซื้อ');
			$pdf->CheckBox('priceType', 5, ($hdr['priceOnOther']==0?false:true), array(), array());
			$pdf->Cell(10, 5, 'อื่นๆ');
			$pdf->Cell(88, 5, $hdr['priceOnRem'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			$pdf->Cell(30, 5, 'ผู้เสนอขาย (Sales) : ');
			$pdf->Cell(30, 0, $hdr['smName'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			$remStr = '';
			if($hdr['remCoa']==0){}else{$remStr.=($remStr==""?"":",  ")."ขอ COA";}
			if($hdr['remPalletBand']==0){}else{$remStr.=($remStr==""?"":",  ")."PALLET ตีตรา";}
			if($hdr['remFumigate']==0){}else{$remStr.=($remStr==""?"":",  ")."รมยาตู้คอนเทนเนอร์";}
			if($hdr['remark']==""){}else{$remStr.=($remStr==""?"":",  ").$hdr['remark'];}
			$pdf->Cell(20, 5, 'หมายเหตุ : ');
			$pdf->Cell(30, 0, $remStr, 'B', 0, 'L', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(9);
			
			
			//a.`remCoa`, a.`remPalletBand`, a.`remFumigate`
			
			
			
			//box
			$pdf->Cell(45, 35, '', 1, $ln=0, 'L', 0, '', 0, false, 'T', 'T');
			$pdf->Cell(65, 35, 'สถานที่ส่งสินค้า (Place to Delivery)', 1, $ln=0, 'L', 0, '', 0, false, 'T', 'T');
			$pdf->Cell(65, 35, '', 1, $ln=0, 'C', 0, '', 0, false, 'T', 'T');
			$pdf->Ln(4);
			
			//in box
			$pdf->Cell(20, 5, 'เครดิต (Credit)');
			$pdf->Cell(10, 5, $hdr['payTypeCreditDays'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Cell(15, 5, 'วัน (Days)');
			$pdf->Cell(5, 5, '');
			$pdf->RadioButton('payTypeCode', 5, array(), array(), 'สินค้านำเข้าจากต่างประเทศ', ($hdr['plac2deliCode']=='FACT'?true:false));
			$pdf->Cell(55, 5, 'ลูกค้ามารับที่โรงงาน AK');
			$pdf->Cell(35, 5, 'จัดทำโดย (Issue By) : ');
			$pdf->Cell(30, 5, $hdr['createByName'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			$pdf->Cell(5, 5, '');
			$pdf->RadioButton('payTypeCode', 5, array(), array(), 'สินค้านำเข้าจากต่างประเทศ', ($hdr['payTypeCode']=='CASH'?true:false));
			$pdf->Cell(40, 5, 'เก็บเงินสด');
			$pdf->RadioButton('payTypeCode', 5, array(), array(), 'สินค้านำเข้าจากต่างประเทศ', ($hdr['plac2deliCode']=='SEND'?true:false));
			$pdf->Cell(30, 5, 'ส่งสินค้าจากโรงงาน AK ที่');
			$pdf->Cell(25, 5, $hdr['plac2deliCodeSendRem'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Cell(35, 5, 'วันที่ (Date) : ');
			$pdf->Cell(30, 5, $hdr['createTime'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			$pdf->Cell(5, 5, '');
			$pdf->RadioButton('payTypeCode', 5, array(), array(), 'สินค้านำเข้าจากต่างประเทศ',  ($hdr['payTypeCode']=='CHEQ'?true:false));
			$pdf->Cell(40, 5, 'เก็บเช็คล่วงหน้า');
			$pdf->RadioButton('payTypeCode', 5, array(), array(), 'สินค้านำเข้าจากต่างประเทศ', ($hdr['plac2deliCode']=='MAPS'?true:false));
			$pdf->Cell(55, 5, 'ตามแผนที่');
			$pdf->Cell(35, 5, 'ตรวจสอบโดย (ผู้ขาย) ');
			$pdf->Cell(30, 5, $hdr['confirmByName'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			$pdf->Cell(5, 5, '');
			$pdf->RadioButton('payTypeCode', 5, array(), array(), 'สินค้านำเข้าจากต่างประเทศ',  ($hdr['payTypeCode']=='TRAN'?true:false));
			$pdf->Cell(40, 5, 'ลูกค้าโอนเงินเข้าบัญชี');
			$pdf->RadioButton('payTypeCode', 5, array(), array(), 'สินค้านำเข้าจากต่างประเทศ', ($hdr['plac2deliCode']=='LOGI'?true:false));
			$pdf->Cell(10, 5, 'ขนส่ง');
			$pdf->Cell(45, 5, $hdr['plac2deliCodeLogiRem'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Cell(35, 5, 'ผู้อนุมัติ (Approved by) ');
			$pdf->Cell(30, 5, $hdr['approveByName'], 'B', 0, 'C', 1, 'B', 0, false, 'T', 'C');
			$pdf->Ln(6);
			
			$pdf->Cell(5, 5, '');
			$pdf->Cell(55, 5, '');
			$pdf->Cell(65, 5, '(เก็บเงินปลายทาง)');
			$pdf->Ln(6);
			
					



					}
					//<!--if isset $_GET['from_date']-->
		
		 
		   

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($soNo.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
	?>