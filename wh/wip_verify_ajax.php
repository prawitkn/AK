<?php	
include 'session.php'; //Global Var => $s_userID,$s_username,$s_userFullname,$s_userGroupCode

try{
    $wipNo = $_POST['wipNo'];	
	
	//We start our transaction.
	$pdo->beginTransaction();
	
	//Query 1: Check Status for not gen running No.
	$sql = "SELECT wipNo FROM wip WHERE wipNo=:wipNo AND statusCode='B' LIMIT 1";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':wipNo', $wipNo);
	$stmt->execute();
	$row_count = $stmt->rowCount();	
	if($row_count != 1 ){		
		//return JSON
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'Status incorrect.'));
		exit();
	}	
		
	//Query 1: UPDATE DATA
	$sql = "UPDATE `wip` SET statusCode='C'
			, confirmTime=now()
			, confirmById=:s_userID 
			WHERE wipNo=:wipNo";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':wipNo', $wipNo);		
	$stmt->bindParam(':s_userID', $s_userID);	
	$stmt->execute();
		
	//We've got this far without an exception, so commit the changes.
    $pdo->commit();
	
    //return JSON
	header('Content-Type: application/json');
	echo json_encode(array('success' => true, 'message' => 'Data confirmed', 'wipNo' => $wipNo));	
} 
//Our catch block will handle any exceptions that are thrown.
catch(Exception $e){
	//return JSON
	header('Content-Type: application/json');
	$errors = "Error on data confirmation. Please try again. " . $e->getMessage();
	echo json_encode(array('success' => false, 'message' => $errors));
}

