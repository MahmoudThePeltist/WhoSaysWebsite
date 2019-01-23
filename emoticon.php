<?php
  include 'phpconnect.php';
  if($_POST){
    //connect to DB
    $conn = connectToDB();
    $userId = $_POST['userId'];
    $postId = $_POST['postId'];
    $btnType = $_POST['btnType'];
    //update DB with emoticon
    $btnObject = $conn->query("SELECT `$btnType` FROM `posttable` WHERE `postId` = '$postId'");
    $btnValue = $btnObject->fetch_assoc();
    $checkObject = $conn->query("SELECT * FROM `emotitable` WHERE `userId` = '$userId' AND `postId` = '$postId'");
    $checkData = $checkObject->fetch_assoc();
    if($checkData[$btnType] == 0){
      $newValue = $btnValue["$btnType"] + 1;
      if ($checkData) {
        $conn->query("UPDATE `emotitable` SET `$btnType` = '1' WHERE `userId` = '$userId' AND `postId` = '$postId'");
        echo "1";
      } else {
        $conn->query("INSERT INTO `emotitable` (`userId`, `postId`, `$btnType`) VALUES ('$userId', '$postId', '1')");
        echo "1";
      }
    } elseif($checkData[$btnType] == 1) {
      $newValue = $btnValue["$btnType"] - 1;
      if ($checkData) {
        $conn->query("UPDATE `emotitable` SET `$btnType` = '0' WHERE `userId` = '$userId' AND `postId` = '$postId'");
        echo "0";
      }
    } else {
      echo "error: " . $checkData[$btnType];
    }
    $conn->query("UPDATE `posttable` SET `$btnType` = '$newValue' WHERE `postId` = '$postId'");
  }
?>
