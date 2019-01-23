<?php
  include 'phpconnect.php';

  if($_POST){
    //connect to DB
    $conn = connectToDB();
    //fetch variables
    $userIdCom = $_POST['userIdCom'];
    $postType = $_POST['postType'];
    $postText = $_POST['postText'];
    $postImage = $_POST['postImage'];
    $postcategory = $_POST['postcategory'];

    //perform form validation
    $postText = formValidate($postText);
    //update table with new post
    $conn->query("INSERT INTO `posttable`(`userId`,`postType`,`text`,`imageURL`,`category`) VALUES ('$userIdCom','$postType','$postText','$postImage','$postcategory')");
    //get category of new postText
    $catGetObj = $conn->query("SELECT `category` FROM `catagorytable`
      WHERE `categoryId` = '$postcategory'");
    $catGet = $catGetObj->fetch_assoc();
    //get ID of new post
    $idGetObj = $conn->query("SELECT `postId` FROM `posttable`
      WHERE `userId` = '$userIdCom'
      AND `postType` = '$postType'
      AND `text` = '$postText'
      AND `imageURL` = '$postImage'");
    $idGet = $idGetObj->fetch_assoc();
    $returnArray = [$idGet['postId'],$catGet['category']];
    echo json_encode($returnArray);
  }
?>
