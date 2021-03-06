<?php
	include 'session.php'; /*$s_userFullname = $row_user['userFullname'];
        $s_userPicture = $row_user['userPicture'];
		$s_username = $row_user['userName'];
		$s_userGroupCode = $row_user['userGroupCode'];
		$s_userDeptCode = $row_user['userDeptCode'];
		$s_userID=$_SESSION['userID'];*/

	$search_fullname = $_POST['search_fullname'];
	$sql = "SELECT hdr.`rtNo`, hdr.`returnDate`, hdr.`fromCode`, hdr.`toCode`, hdr.`remark`, hdr.`statusCode`	
	, fsl.name as fromName, tsl.name as toName 
	FROM `rt` hdr
	LEFT JOIN sloc fsl on hdr.fromCode=fsl.code
	LEFT JOIN sloc tsl on hdr.toCode=tsl.code
	WHERE 1 
	AND hdr.statusCode='P' 
	AND (hdr.rcNo IS NULL OR hdr.rcNo='') 
	AND hdr.rtNo like :search_word ";
	switch($s_userGroupCode){ 
		case 'whOff' :
		case 'whSup' :
		case 'pdOff' :
		case 'pdSup' :
			$sql .= "AND hdr.toCode=:s_userDeptCode ";
			break;
		default :	// it, admin 
	}	
	$sql .= "ORDER BY hdr.createTime DESC";
	//$result = mysqli_query($link, $sql);
	$stmt = $pdo->prepare($sql);
	$search_fullname = '%'.$search_fullname.'%';
	$stmt->bindParam(':search_word', $search_fullname);
	switch($s_userGroupCode){ 
		case 'whOff' :
		case 'whSup' :
		$a='8';
			$stmt->bindParam(':s_userDeptCode', $a);
			break;
		case 'pdOff' :
		case 'pdSup' :
			$stmt->bindParam(':s_userDeptCode', $s_userDeptCode);
			break;
		default :	// it, admin 
	}	
	$stmt->execute();

	$jsonData = array();
	while ($array = $stmt->fetch()) {
		$jsonData[] = $array;
	}
 					   
	echo json_encode($jsonData);
	
?>


