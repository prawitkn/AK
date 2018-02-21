<?php
	include 'inc_helper.php'; 
    //include 'db.php';
	include 'session.php';

try{	
    $id = $_POST['id'];
	$prodGroup = $_POST['prodGroup'];
	$prodName = $_POST['prodName'];
	$prodNameNew = $_POST['prodNameNew'];
	$prodDesc = $_POST['prodDesc'];
	$prodPrice = $_POST['prodPrice'];
	$appId = $_POST['appId']; 
	$curPhoto = $_POST['curPhoto'];
	$statusCode = $_POST['statusCode'];	 
	$statusCode="";
	if(isset($_POST['statusCode'])) $statusCode='A';
	$new_picture_name=$curPhoto;
	 
	
	/*$fileName = $_FILES['inputFile']['name'];
    //$fileExt = pathinfo($_FILES["inputFile"]["name"], PATHINFO_EXTENSION);
    $filePath = "files/".$fileName;
    if (move_uploaded_file($_FILES["inputFile"]["tmp_name"], $filePath)) {
        echo "Upload success";
    } else {
        echo "Upload failed";
    }*/
	
	
	 // Upload Picture
    if (is_uploaded_file($_FILES['inputFile']['tmp_name'])){
		// If the old picture already exists, delete it
		if (file_exists('dist/img/product/'.$curPhoto)) unlink('dist/img/product/'.$curPhoto);
	
        $new_picture_name = 'prod_'.uniqid().".".pathinfo(basename($_FILES['inputFile']['name']), PATHINFO_EXTENSION);
        $path_upload = "./dist/img/product/".$new_picture_name;
        move_uploaded_file($_FILES['inputFile']['tmp_name'], $path_upload);        
    }  else {		
        //$new_picture_name = "";       
		//if ($curPhoto<>"") $new_picture_name=$curPhoto; 
    }
	

    $sql = "UPDATE `product` SET 
			  `prodGroup`='$prodGroup' 
			, `prodName`='$prodName'
			, `prodNameNew`='$prodNameNew'
			, `prodDesc`='$prodDesc'
			, `prodPrice`='$prodPrice'
			, `appID`='$appId'	
			, `photo`='$new_picture_name'	
			, `statusCode`='$statusCode'	
			WHERE ID=$id 
			";
 
    $result = mysqli_query($link, $sql);
	
	header('Content-Type: application/json');
      echo json_encode(array('success' => true, 'message' => 'Data Update Complete.'.$sql));
}catch(Exception $e){
	header('Content-Type: application/json');
  $errors = "Error on Data Verify. Please try again. " . $e->getMessage();
  echo json_encode(array('success' => false, 'message' => $errors));
} 
?>