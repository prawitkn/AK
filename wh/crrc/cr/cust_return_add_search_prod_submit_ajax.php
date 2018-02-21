<?php
include 'session.php';	
include 'inc_helper.php';	
	
try{
	$t = "";
	$s_userID = $_SESSION['userID']; 
	
    $rtNo = $_POST['rtNo'];
	
	$pdo->beginTransaction();
	
	if(!empty($_POST['prodItemId']) and isset($_POST['prodItemId']))
    {
		//$arrProdItems=explode(',', $prodItems);
        foreach($_POST['prodItemId'] as $index => $item )
        {	
			$sql = "INSERT INTO `rt_detail`
			(`prodItemId`, `prodId`, `prodCode`, `barcode`, `issueDate`, `machineId`, `seqNo`, `NW`, `GW`
			, `qty`, `packQty`, `grade`, `gradeDate`, `refItemId`, `itemStatus`, `remark`, `problemId`
			, `returnReasonCode`, `returnReasonRemark`, `rtNo`)
			SELECT rc.`prodItemId`, rc.`prodId`, rc.`prodCode`, rc.`barcode`, rc.`issueDate`, rc.`machineId`, rc.`seqNo`, rc.`NW`, rc.`GW`
			,rc.`qty`, rc.`packQty`, rc.`grade`, rc.`gradeDate`, rc.`refItemId`, rc.`itemStatus`, rc.`remark`, rc.`problemId`
			,:returnReasonCode, :returnReasonRemark, :rtNo 
			FROM receive_detail rc 
			WHERE rc.id=:id 
			";						
			$stmt = $pdo->prepare($sql);	
			$stmt->bindParam(':returnReasonCode', $_POST['returnReasonCode'][$index]);	
			$stmt->bindParam(':returnReasonRemark', $_POST['returnReasonRemark'][$index]);	
			$stmt->bindParam(':rtNo', $rtNo);	
			$stmt->bindParam(':id', $item);		
			$stmt->execute();			
        }
    }
	
	
	
		
	$pdo->commit();
	
	header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'message' => 'Data Inserted Complete.', 'rtNo' => $rtNo));
} 
//Our catch block will handle any exceptions that are thrown.
catch(Exception $e){
    //Rollback the transaction.
    $pdo->rollBack();
	//return JSON
	header('Content-Type: application/json');
	$errors = "Error on Data Verify. Please try again. " . $e->getMessage();
	echo json_encode(array('success' => false, 'message' => $errors.$t));
}


