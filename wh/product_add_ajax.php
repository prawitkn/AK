<?php
	include 'inc_helper.php'; 
    //include 'db.php';
	include 'session.php';
						
	$prodGroup = $_POST['prodGroup'];
	$prodName = $_POST['prodName'];
	$prodNameNew = $_POST['prodNameNew'];
	$prodDesc = $_POST['prodDesc'];
	$prodPrice = $_POST['prodPrice'];
	$appId = $_POST['appId']; 
	 
	 //Check Duplicate
	 $sql = "SELECT * FROM `m_product` WHERE `prodName`='$prodName' OR `prodNameNew`='$prodNameNew' LIMIT 1 "; 
    $result = mysqli_query($link, $sql);
	if(mysqli_num_rows($result)>=1){
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'Duplicate data.'));
		exit;
	}
		
    $sql = "INSERT INTO `m_product`(`prodGroup`, `prodName`, `prodNameNew`, `prodPrice`, `prodDesc`, `appID`, `statusCode`)  "  
         . " VALUES ('$prodGroup', '$prodName', '$prodNameNew', '$prodPrice', '$prodDesc', '$appID','A')";
 
    $result = mysqli_query($link, $sql);
 
    if ($result) {
      header('Content-Type: application/json');
      echo json_encode(array('success' => true, 'message' => 'Data Inserted Complete.'));
   } else {
      header('Content-Type: application/json');
      $errors = "Error on Data Insertion. Please try new username. " . mysqli_error($link);
      echo json_encode(array('success' => false, 'message' => $errors));
	}
?>