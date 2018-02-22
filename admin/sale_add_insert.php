<?php
include 'inc_helper.php';  
include 'session.php';	
	
try{
	$saleDate = $_POST['saleDate'];
	$poNo = $_POST['poNo'];    
	$piNo = $_POST['piNo'];    
    $smId = $_POST['smId'];
    $custId = $_POST['custId'];
	$deliveryDate = $_POST['deliveryDate'];
	$shippingMarksId = $_POST['shippingMarksId'];
	//$deliveryRem = $_POST['deliveryRem'];
	$shipByLcl = (isset($_POST['shipByLcl'])? 1 : 0 );
	$shipByFcl1x20 = (isset($_POST['shipByFcl1x20'])? 1 : 0 );
	$shipByFcl1x40 = (isset($_POST['shipByFcl1x40'])? 1 : 0 );
	$remCoa = (isset($_POST['remCoa'])? 1 : 0 );
	$remPalletBand = (isset($_POST['remPalletBand'])? 1 : 0 );
	$remFumigate = (isset($_POST['remFumigate'])? 1 : 0 );
	$remark = $_POST['remark'];
	$prodGFC = (isset($_POST['prodGFC'])? 1 : 0 );
	$prodGFM = (isset($_POST['prodGFM'])? 1 : 0 );
	$prodGFT = (isset($_POST['prodGFT'])? 1 : 0 );
	$prodSC = (isset($_POST['prodSC'])? 1 : 0 );
	$prodCFC = (isset($_POST['prodCFC'])? 1 : 0 );
	$prodEGWM = (isset($_POST['prodEGWM'])? 1 : 0 );
	$prodGT = (isset($_POST['prodGT'])? 1 : 0 );
	$prodCSM = (isset($_POST['prodCSM'])? 1 : 0 );
	$prodWR = (isset($_POST['prodWR'])? 1 : 0 );
	$suppTypeFact = (isset($_POST['suppTypeFact'])? 1 : 0 );
	$suppTypeImp = (isset($_POST['suppTypeImp'])? 1 : 0 );
	$prodTypeOld = (isset($_POST['prodTypeOld'])? 1 : 0 );
	$prodTypeNew = (isset($_POST['prodTypeNew'])? 1 : 0 );
	$custTypeOld = (isset($_POST['custTypeOld'])? 1 : 0 );
	$custTypeNew = (isset($_POST['custTypeNew'])? 1 : 0 );
	$prodStkInStk = (isset($_POST['prodStkInStk'])? 1 : 0 );
	$prodStkOrder = (isset($_POST['prodStkOrder'])? 1 : 0 );
	$prodStkOther = (isset($_POST['prodStkOther'])? 1 : 0 );
	$prodStkRem = $_POST['prodStkRem'];
	$packTypeAk = (isset($_POST['packTypeAk'])? 1 : 0 );
	$packTypeNone = (isset($_POST['packTypeNone'])? 1 : 0 );
	$packTypeOther = (isset($_POST['packTypeOther'])? 1 : 0 );
	$packTypeRem = $_POST['packTypeRem'];
	$priceOnOrder = (isset($_POST['priceOnOrder'])? 1 : 0 );
	$priceOnOther = (isset($_POST['priceOnOther'])? 1 : 0 );
	$priceOnRem = $_POST['priceOnRem'];
	$plac2deliCode = (isset($_POST['plac2deliCode'])? $_POST['plac2deliCode'] : '' );
	$plac2deliCodeSendRem = $_POST['plac2deliCodeSendRem'];
	$plac2deliCodeLogiRem = $_POST['plac2deliCodeLogiRem'];
	//$plac2deliRem = $_POST['plac2deliRem'];
	$payTypeCreditDays = $_POST['payTypeCreditDays'];
	$payTypeCode = (isset($_POST['payTypeCode'])? $_POST['payTypeCode'] : '' );
	
	$soNo = 'SO-'.substr(str_shuffle(MD5(microtime())), 0, 7);
	
	$saleDate = to_mysql_date($saleDate);
	$deliveryDate = to_mysql_date($deliveryDate);

	//$pdo->beginTransaction();
	
	$sql = "INSERT INTO `sale_header`
	(`soNo`, `saleDate`, `poNo`, `piNo`, `custId`, `shipToId`, `smId`
	, `prodGFC`, `prodGFM`, `prodGFT`, `prodSC`, `prodCFC`, `prodEGWM`, `prodGT`, `prodCSM`, `prodWR`
	, `deliveryDate`, `shipByLcl`, `shipByFcl1x20`, `shipByFcl1x40`, `shippingMarksId`
	, `suppTypeFact`, `suppTypeImp`, `prodTypeOld`, `prodTypeNew`, `custTypeOld`, `custTypeNew`
	, `prodStkInStk`, `prodStkOrder`, `prodStkOther`, `prodStkRem`
	, `packTypeAk`, `packTypeNone`, `packTypeOther`, `packTypeRem`
	, `priceOnOrder`, `priceOnOther`, `priceOnRem`
	, `remCoa`, `remPalletBand`, `remFumigate`, `remark`
	, `plac2deliCode`, `plac2deliCodeSendRem`, `plac2deliCodeLogiRem`, `payTypeCode`, `payTypeCreditDays`
	, `statusCode`, `createTime`, `createById`) 
	VALUES 
	(:soNo, :saleDate, :poNo,:piNo, :custId, :shipToId,  :smId
	, :prodGFC, :prodGFM, :prodGFT, :prodSC, :prodCFC, :prodEGWM, :prodGT, :prodCSM, :prodWR
	, :deliveryDate,:shipByLcl,:shipByFcl1x20,:shipByFcl1x40, :shippingMarksId
	, :suppTypeFact, :suppTypeImp, :prodTypeOld, :prodTypeNew, :custTypeOld, :custTypeNew
	, :prodStkInStk, :prodStkOrder, :prodStkOther, :prodStkRem
	, :packTypeAk, :packTypeNone, :packTypeOther, :packTypeRem
	, :priceOnOrder, :priceOnOther, :priceOnRem
	, :remCoa, :remPalletBand, :remFumigate, :remark
	, :plac2deliCode, :plac2deliCodeSendRem, :plac2deliCodeLogiRem, :payTypeCode, :payTypeCreditDays
	, 'A', now(), :s_userId) 
	";
 
    //$result = mysqli_query($link, $sql);
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':soNo', $soNo);
	$stmt->bindParam(':saleDate', $saleDate);
	$stmt->bindParam(':poNo', $poNo);
	$stmt->bindParam(':piNo', $piNo);
	$stmt->bindParam(':custId', $custId);
	$stmt->bindParam(':shipToId', $custId);
	$stmt->bindParam(':smId', $smId);
	$stmt->bindParam(':prodGFC', $prodGFC);
	$stmt->bindParam(':prodGFM', $prodGFM);
	$stmt->bindParam(':prodGFT', $prodGFT);
	$stmt->bindParam(':prodSC', $prodSC);
	$stmt->bindParam(':prodCFC', $prodCFC);
	$stmt->bindParam(':prodEGWM', $prodEGWM);
	$stmt->bindParam(':prodGT', $prodGT);
	$stmt->bindParam(':prodCSM', $prodCSM);
	$stmt->bindParam(':prodWR', $prodWR);
	$stmt->bindParam(':deliveryDate', $deliveryDate);
	$stmt->bindParam(':shipByLcl', $shipByLcl);
	$stmt->bindParam(':shipByFcl1x20', $shipByFcl1x20);
	$stmt->bindParam(':shipByFcl1x40', $shipByFcl1x40);
	$stmt->bindParam(':shippingMarksId', $shippingMarksId);
	$stmt->bindParam(':suppTypeFact', $suppTypeFact);
	$stmt->bindParam(':suppTypeImp', $suppTypeImp);
	$stmt->bindParam(':prodTypeOld', $prodTypeOld);
	$stmt->bindParam(':prodTypeNew', $prodTypeNew);
	$stmt->bindParam(':custTypeOld', $custTypeOld);
	$stmt->bindParam(':custTypeNew', $custTypeNew);
	$stmt->bindParam(':prodStkInStk', $prodStkInStk);
	$stmt->bindParam(':prodStkOrder', $prodStkOrder);
	$stmt->bindParam(':prodStkOther', $prodStkOther);
	$stmt->bindParam(':prodStkRem', $prodStkRem);
	$stmt->bindParam(':packTypeAk', $packTypeAk);
	$stmt->bindParam(':packTypeNone', $packTypeNone);
	$stmt->bindParam(':packTypeOther', $packTypeOther);
	$stmt->bindParam(':packTypeRem', $packTypeRem);
	$stmt->bindParam(':priceOnOrder', $priceOnOrder);
	$stmt->bindParam(':priceOnOther', $priceOnOther);
	$stmt->bindParam(':priceOnRem', $priceOnRem);
	$stmt->bindParam(':remark', $remark);
	$stmt->bindParam(':remCoa', $remCoa);
	$stmt->bindParam(':remPalletBand', $remPalletBand);
	$stmt->bindParam(':remFumigate', $remFumigate);
	$stmt->bindParam(':plac2deliCode', $plac2deliCode);
	$stmt->bindParam(':plac2deliCodeSendRem', $plac2deliCodeSendRem);
	$stmt->bindParam(':plac2deliCodeLogiRem', $plac2deliCodeLogiRem);
	$stmt->bindParam(':payTypeCode', $payTypeCode);
	$stmt->bindParam(':payTypeCreditDays', $payTypeCreditDays);
	$stmt->bindParam(':s_userId', $s_userId);	
	$stmt->execute();
	
	/*$id = $pdo->lastInsertId();
	$soNo = substr('0000000000'.(string)$id,-10);	
	$sql = "UPDATE `order_header` SET soNo=:soNo WHERE id=:id ";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':soNo', $soNo);	
	$stmt->bindParam(':id', $id);	
	$stmt->execute();*/
	
	//$pdo->commit();
	
	header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'message' => 'Data Inserted Complete.', 'soNo' => $soNo));
} 
//Our catch block will handle any exceptions that are thrown.
catch(Exception $e){
    //Rollback the transaction.
    $pdo->rollBack();
	//return JSON
	header('Content-Type: application/json');
	$errors = "Error on Data Verify. Please try again. " . $e->getMessage();
	echo json_encode(array('success' => false, 'message' => $errors));
}
