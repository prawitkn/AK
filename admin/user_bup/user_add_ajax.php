<?php

    include '../db/database.php';
   
    $userFullname = $_POST['userFullname'];
    $userName = $_POST['userName'];
    $userPassword = $_POST['userPassword'];
    $userEmail = $_POST['userEmail'];
    $userTel = $_POST['userTel'];
	$userGroupCode = $_POST['userGroupCode'];
    
 // Check user name duplication?
    $sql_user = "SELECT userName FROM user WHERE userName='$userName'";
    $result_user = mysqli_query($link, $sql_user);
    $is_user = mysqli_num_rows($result_user);
    if ($is_user >= 1){
      header('Content-Type: application/json');
      $errors = "Error on Data Insertion. Please try new username. " . mysqli_error($link);
      echo json_encode(array('status' => 'danger', 'message' => $errors));  
      exit;    
    }   
    
 // Encript Password
    $salt = "asdadasgfd";
    $hash_userPassword = hash_hmac('sha256', $userPassword, $salt);
    
 // Upload Personal Picture
    if (is_uploaded_file($_FILES['userPicture']['tmp_name'])){
        $new_picture_name = 'user_'.uniqid().".".pathinfo(basename($_FILES['userPicture']['name']), PATHINFO_EXTENSION);
        $path_upload = "./dist/img/".$new_picture_name;
        move_uploaded_file($_FILES['userPicture']['tmp_name'], $path_upload);        
    }  else {
        $new_picture_name = "";
       
    }
    
    $sql = "INSERT INTO `user` (`userName`, `userPassword`, `userFullname`, `userEmail`, `userTel`, `userPicture`, `userGroupCode`, `statusCode`)"
            . " VALUES ('$userName', '$hash_userPassword', '$userFullname', '$userEmail', '$userTel', '$new_picture_name', '$userGroupCode', 'A')";
 
    $result = mysqli_query($link, $sql);
 
    if ($result) {
 //     header("Location: product_type.php");
 //     echo "Finished Insert.";
      header('Content-Type: application/json');
      echo json_encode(array('status' => 'success', 'message' => 'Data Inserted Complete.'));
   } else {
      header('Content-Type: application/json');
      $errors = "Error on Data Insertion. Please try new username. " . mysqli_error($link);
      echo json_encode(array('status' => 'danger', 'message' => $errors));
 //   echo " Cannot Insert.";
 //   echo mysqli_error($link);
}
