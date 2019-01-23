<?php
  include 'phpconnect.php';

  if($_POST){
    //connect to DB
    $conn = connectToDB();
    $changeType = $_POST['changeType'];
    $btnType = $_POST['btnType'];
    $postId = $_POST['postId'];
    //update DB with emoticon
    $btnObject = $conn->query("SELECT $btnType FROM posttable WHERE postId = '$postId'");
    $btnValue = $btnObject->fetch_assoc();
    if($changeType == 'add'){
      $newValue = $btnValue["$btnType"] + 1;
    } else {
      $newValue = $btnValue["$btnType"] - 1;
    }
    $conn->query("UPDATE posttable SET $btnType = '$newValue' WHERE postId = '$postId'");
  }
?>
