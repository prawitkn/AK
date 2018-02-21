<?php
include 'inc_helper.php';  
include 'session.php';	
	
try{
	
	$s_userID = $_SESSION['userID'];

	$rtNo = 'RT'.substr(str_shuffle(MD5(microtime())), 0, 8);
	//$rcNo = $_POST['rcNo'];
	$returnDate = $_POST['returnDate'];
	$refNo = $_POST['refNo'];
	$remark = $_POST['remark'];
	
	$returnDate = to_mysql_date($returnDate);
	
	$sql = "INSERT INTO `rt`
	(`rtNo`, `returnDate`, `refNo`, `fromCode`, `toCode`, `remark`,  `statusCode`, `createTime`, `createByID`) 
	SELECT :rtNo,:returnDate,:refNo,rc.toCode,rc.fromCode,:remark,'B',now(),:s_userID 
	FROM receive rc 
	WHERE rc.rcNo=:rcNo 
	";
 	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':rtNo', $rtNo);
	$stmt->bindParam(':returnDate', $returnDate);
	$stmt->bindParam(':refNo', $refNo);
	$stmt->bindParam(':remark', $remark);
	$stmt->bindParam(':s_userID', $s_userID);	
	$stmt->bindParam(':rcNo', $refNo);
	$stmt->execute();
			
	header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'message' => 'Data Inserted Complete.', 'rtNo' => $rtNo));
} 
//Our catch block will handle any exceptions that are thrown.
catch(Exception $e){
	//return JSON
	header('Content-Type: application/json');
	$errors = "Error on Data Verify. Please try again. " . $e->getMessage();
	echo json_encode(array('success' => false, 'message' => $errors));
}
