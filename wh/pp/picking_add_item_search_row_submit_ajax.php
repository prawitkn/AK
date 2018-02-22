<?php
include 'session.php';	
include 'inc_helper.php';	
	
try{
	$s_userID = $_SESSION['userID']; 

	$pickNo = $_POST['pickNo'];
    $prodCode = $_POST['prodCode'];	
	$issueDate = $_POST['issueDate'];	
	$grade = $_POST['grade'];	
	$pickQty = $_POST['pickQty'];	
	
	//$issueDate = to_mysql_date($issueDate);
	
	$pdo->beginTransaction();
	
	$sql = "INSERT INTO `picking_detail` 
	(`pickNo`, `prodCode`, `issueDate`, `grade`, `qty`) 
	VALUES
	(:pickNo, :prodCode,:issueDate,:grade,:pickQty)";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':pickNo', $pickNo);	
	$stmt->bindParam(':prodCode', $prodCode);	
	$stmt->bindParam(':issueDate', $issueDate);	
	$stmt->bindParam(':grade', $grade);	
	$stmt->bindParam(':pickQty', $pickQty);	
	$stmt->execute();
			
	$pdo->commit();
	
	header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'message' => 'Data Inserted Complete.', 'pickNo' => $pickNo));
} 
//Our catch block will handle any exceptions that are thrown.
catch(Exception $e){
    //Rollback the transaction.
    $pdo->rollBack();
	//return JSON
	header('Content-Type: application/json');
	$errors = "Error on Data Insert. Please try again. " . $e->getMessage();
	echo json_encode(array('success' => false, 'message' => $errors.$t));
}


