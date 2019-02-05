<?php
  include 'phpconnect.php';
  //connect to DB
  $conn = connectToDB();
  session_start();
  $errorText = "";
  //If current category is not set for this session, set it to default
  if(!isset($_SESSION['currentCategory'])){
    $_SESSION['currentCategory'] = 0;
  }
  //if a get has been made for a category, set it as the session category
  if(isset($_GET['category'])){
    $_SESSION['currentCategory'] = $_GET['category'];
  }
  //set a variable equal to the session category
  $currentCategory = $_SESSION['currentCategory'];
  if(!isset($_SESSION['userID'])){
    header('location: index.php');
    die();
  } else {
    $currentUserName = $_SESSION['userID'];
  }
  //get the user data
  $currentUserArray = getUserData($conn, $currentUserName);
  //rank of user
  if($currentUserArray["Premissions"]){$rank = "Admin";}else{$rank = "User";}
  //variables for the currently signed in user
  $userDataID = $currentUserArray["ID"];
  $userDataImageURL = $currentUserArray["userImage"];
  $userDataUserName = $currentUserArray["Username"];
  $profileBtnUserName = $currentUserArray["Username"];// <-- this is used to create the link for the my profile button
  //theme variables:
  if(!isset($_SESSION['primaryTheme'])){
    $_SESSION['primaryTheme'] = 0;
  } if (!isset($_SESSION['secondaryTheme'])){
    $_SESSION['secondaryTheme'] = 0;
  }
  //bost building
  $getPostsFunctionReturnArray = getPostsArray($conn, $currentCategory,$currentUserName, $userDataID, $rank);
  $postsArray = $getPostsFunctionReturnArray;
  include "includes/mainFeed.html";
  $conn->close();
?>
