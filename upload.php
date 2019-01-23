<?php
  include 'phpconnect.php';
  session_start();
  $conn = connectToDB();
  $userName = $_SESSION['userID'];
  if(isset($_FILES['fileInput'])){
     $errors= array();
     $file_name = $_FILES['fileInput']['name'];
     $file_size = $_FILES['fileInput']['size'];
     $file_tmp = $_FILES['fileInput']['tmp_name'];
     $file_type = $_FILES['fileInput']['type'];
     $file_ext=strtolower(pathinfo($_FILES['fileInput']['name'],PATHINFO_EXTENSION));

     $expensions= array("jpeg","jpg","png");
     if(in_array($file_ext,$expensions)=== false){
        $errors[]="<h1>extension not allowed, please choose a JPEG or PNG file.</h1>";
     }
     if($file_size > 2097152) {
        $errors[]='<h1>File size must be less than 2 MB</h1>';
     }

     if(empty($errors)==true) {
        $date = new DateTime();
        $timeStamp = $date->getTimestamp();
        $noSpaceName = str_replace(' ', '_', $userName);
        $file_location = "userImages/". "user" . $noSpaceName . $timeStamp . "." . $file_ext;
        move_uploaded_file($file_tmp,$file_location);
        $conn->query("UPDATE usertable SET userImage = '$file_location' WHERE Username = '$userName'");
        echo "<h1>Success</h1>";
        header('location: mainFeed.php');
        exit();
     }else{
        print_r($errors);
     }
  }
?>
