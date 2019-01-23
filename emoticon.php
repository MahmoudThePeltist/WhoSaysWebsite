<?php
  include 'phpconnect.php';

  if($_POST){
    $newValue = $_POST['newValue'];
    $btnType = $_POST['btnType'];
    $postId = $_POST['postId'];
    //connect to DB
    $conn = connectToDB();
    //update DB with emoticon
    $conn->query("UPDATE posttable SET $btnType = '$newValue' WHERE postId = '$postId'");
  }
?>
