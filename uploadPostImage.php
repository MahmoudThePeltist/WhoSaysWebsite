<?php
  include 'phpconnect.php';
  session_start();
  $errors= array();
  $file_name = $_FILES['file']['name'];
  $file_size = $_FILES['file']['size'];
  $file_tmp = $_FILES['file']['tmp_name'];
  $file_type = $_FILES['file']['type'];
  $userName = $_SESSION['userID'];
  $file_ext=strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));

  $expensions= array("jpeg","jpg","png");
  if(in_array($file_ext,$expensions)=== false){
    echo "extension not allowed, please choose a JPEG or PNG file.";
  }
  if($file_size > 12097152) {
    echo 'File size must be less than 12 MB';
  }

  if(empty($errors)==true) {
    $date = new DateTime();
    $timeStamp = $date->getTimestamp();
    $noSpaceName = str_replace(' ', '_', $userName);
    //directory to save the file
    $directory = "postImages/". "user_" . $noSpaceName;
    //create directory if it does not exist
    if(!file_exists($directory)){
      mkdir($directory, 0777, true);
    }
    //specific file location
    $file_location = $directory . "/" . $timeStamp . "." . $file_ext;
    //move file to it's location
    move_uploaded_file($file_tmp,$file_location);
    echo $file_location;
  }else{
    echo "Error.";
  }
?>
