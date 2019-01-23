<?php
  include 'phpconnect.php';

  if($_POST){
    //connect to DB
    $conn = connectToDB();
    $postIdCom = $_POST['postIdCom'];
    $userIdCom = $_POST['userIdCom'];
    $commentText = $_POST['commentText'];
    //update table with new comment
    $conn->query("INSERT INTO `commenttable`(`parentID`, `userID`, `commentText`) VALUES ('$postIdCom','$userIdCom','$commentText')");
  }
?>
