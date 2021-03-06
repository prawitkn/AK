<?php

include 'session.php'; /*$s_userID=$_SESSION['userID'];
		$s_userFullname = $row_user['userFullname'];
        $s_userPicture = $row_user['userPicture'];
		$s_username = $row_user['userName'];
		$s_userGroupCode = $row_user['userGroupCode'];
		$s_userDept = $row_user['userDept'];*/

//Check user roll.
switch($s_userGroupCode){
	case 'it' : case 'admin' : case 'whAdmin' : 
		break;
	default : 
		//return JSON
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'Access Denied.'));
		exit();
}

$docNo = $_POST['docNo'];

//We will need to wrap our queries inside a TRY / CATCH block.
//That way, we can rollback the transaction if a query fails and a PDO exception occurs.
try{
	//We start our transaction.
	$pdo->beginTransaction();
	//Query 1: Check Status for not gen running No.
	$sql = "SELECT docNo FROM inv_ret WHERE docNo=:docNo AND statusCode='C' LIMIT 1";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':docNo', $docNo);
	$stmt->execute();
	$row_count = $stmt->rowCount();	
	if($row_count != 1 ){
		//return JSON
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'Status incorrect.'));
		exit();
	}
	$hdr=$stmt->fetch();
	//$fromCode = $hdr['fromCode'];
	//$toCode = $hdr['toCode'];
	
	//Query 1: GET Next Doc No.
	$year = date('Y'); $name = 'custRet'; $prefix = 'CR'.date('y'); $cur_no=1;
	$sql = "SELECT prefix, cur_no FROM doc_running WHERE year=? and name=? LIMIT 1";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($year, $name));
	$row_count = $stmt->rowCount();	
    if($row_count == 0){
		$sql = "INSERT INTO doc_running (year, name, prefix, cur_no) VALUES (?,?,?,?)";
		$stmt = $pdo->prepare($sql);		
		$stmt->execute(array($year, $name, $prefix, $cur_no));
	}else{
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$prefix = $row['prefix'];
		$cur_no = (int)$row['cur_no']+1;		
	}
	$next_no = '00000'.(string)$cur_no;
	$noNext = $prefix . substr($next_no, -6);
	
	//Query 1: UPDATE DATA
	$sql = "UPDATE inv_ret SET statusCode='P'
	, docNo=:noNext  
	, approveTime=now()
	, approveById=:approveById
	WHERE docNo=:docNo  
	AND statusCode='C' 
	";
    $stmt = $pdo->prepare($sql);
	$stmt->bindParam(':noNext', $noNext);
	$stmt->bindParam(':approveById', $s_userID);
	$stmt->bindParam(':docNo', $docNo);
    $stmt->execute();
		
	//Query 3: UPDATE DATA
	$sql = "UPDATE inv_ret_detail SET docNo=? WHERE docNo=? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($noNext,$docNo));
	
    //Query 4:  UPDATE doc running.
	$sql = "UPDATE doc_running SET cur_no=? WHERE year=? and name=?";
	$stmt = $pdo->prepare($sql);		
	$stmt->execute(array($cur_no, $year, $name));	
	




	/*
	//Query 5: UPDATE STK BAl
	$sql = "		
	UPDATE stk_bal sb,
	( SELECT prodCode, sum(qty)  as sumQty
		   FROM rt_detail WHERE rtNo=:rtNo GROUP BY prodCode) as s
	SET sb.send=sb.send+s.sumQty
	, sb.balance=sb.balance-s.sumQty 
	WHERE sb.prodCode=s.prodCode
	AND sb.sloc=:fromCode
	";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rtNo', $noNext);
	$stmt->bindParam(':fromCode', $fromCode);
    $stmt->execute();
		
	//Query 6: UPDATE STK BAl
	$sql = "INSERT INTO stk_bal (prodCode, sloc, send, balance) 
	SELECT sd.prodCode, :fromCode, SUM(sd.qty), -1*SUM(sd.qty) FROM rt_detail sd 
	WHERE sd.rtNo=:rtNo 
	AND sd.prodCode NOT IN (SELECT sb2.prodCode FROM stk_bal sb2 WHERE sb2.sloc=:fromCode2)
	GROUP BY sd.prodCode
	";
    $stmt = $pdo->prepare($sql);
	$stmt->bindParam(':rtNo', $noNext);
    $stmt->bindParam(':fromCode', $fromCode);
	$stmt->bindParam(':fromCode2', $fromCode);
    $stmt->execute();
	
	//Query 5: UPDATE STK BAl
	$sql = "		
	UPDATE stk_bal sb,
	( SELECT prodCode, sum(qty)  as sumQty
		   FROM rt_detail WHERE rtNo=:rtNo GROUP BY prodCode) as s
	SET sb.onway=sb.onway+s.sumQty
	WHERE sb.prodCode=s.prodCode
	AND sb.sloc=:toCode
	";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rtNo', $noNext);
	$stmt->bindParam(':toCode', $toCode);
    $stmt->execute();
	
	//Query 6: UPDATE STK BAl
	$sql = "INSERT INTO stk_bal (prodCode, sloc, onway) 
			SELECT sd.prodCode, :toCode, SUM(sd.qty) FROM rt_detail sd 
			WHERE sd.rtNo=:rtNo 
			AND sd.prodCode NOT IN (SELECT sb2.prodCode FROM stk_bal sb2 WHERE sb2.sloc=:toCode2)
			GROUP BY sd.prodCode
			";
    $stmt = $pdo->prepare($sql);
	$stmt->bindParam(':rtNo', $noNext);
    $stmt->bindParam(':toCode', $toCode);
	$stmt->bindParam(':toCode2', $toCode);
    $stmt->execute();
	
	
	
	//Query 3: UPDATE Shelf
	$sql = "DELETE wsi 
	FROM wh_sloc_map_item wsi
	INNER JOIN receive_detail rcDtl ON rcDtl.id=wsi.recvProdId  
	INNER JOIN rt_detail rtDtl ON rtDtl.prodItemId=rcDtl.prodItemId AND rtDtl.rtNo=:rtNo
	";
    $stmt = $pdo->prepare($sql);
	$stmt->bindParam(':rtNo', $noNext);
    $stmt->execute();
	
	//Query 3: UPDATE Receive Detail 
	$sql = "UPDATE receive_detail rcDtl 
	INNER JOIN rt_detail rtDtl ON rtDtl.prodItemId=rcDtl.prodItemId AND rtDtl.rtNo=:rtNo
	SET rcDtl.isReturn='Y', shelfCode='' 
	";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rtNo', $noNext);
    $stmt->execute();
	
	*/
	
	
			
	//We've got this far without an exception, so commit the changes.
    $pdo->commit();
	
    //return JSON
	header('Content-Type: application/json');
	echo json_encode(array('success' => true, 'message' => 'Data approved', 'docNo' => $noNext));	
} 
//Our catch block will handle any exceptions that are thrown.
catch(Exception $e){
	//Rollback the transaction.
    $pdo->rollBack();
	//return JSON
	header('Content-Type: application/json');
	$errors = "Error on Data Approval. Please try again. " . $e->getMessage();
	echo json_encode(array('success' => false, 'message' => $errors));
}
?>     

