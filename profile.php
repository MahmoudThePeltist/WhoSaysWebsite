<?php
  include 'phpconnect.php';
  session_start();
  $posts = "";
  $newPost = "";
  $errorText = "";

  if(!isset($_SESSION['userID'])){
    header('location: index.php');
    die();
  } else {
    $currentUserName = $_SESSION['userID'];
    $profileUserName = $_GET["username"];
  }
  $currentCategory = 0;
  if(isset($_GET['category'])){
    $currentCategory = $_GET['category'];
  }
  //Make sure the page belongs to this specific user
  if($profileUserName === $currentUserName){
    $isCurrentUsersPage = True;
  } else {
    $isCurrentUsersPage = False;
  }
  //theme variables:
  if(!isset($_SESSION['primaryTheme'])){
    $_SESSION['primaryTheme'] = 0;
  } if (!isset($_SESSION['secondaryTheme'])){
    $_SESSION['secondaryTheme'] = 0;
  }
  //connect to db
  $conn = connectToDB();
  $currentUserObject = $conn->query("SELECT * FROM usertable WHERE Username = '$profileUserName'");
  $currentUserArray = $currentUserObject->fetch_assoc();
  $profileUserID = $currentUserArray["ID"];
  //rank of user
  if($currentUserArray["Premissions"]){$rank = "Admin";}else{$rank = "User";}
  //variables to be used in the profile template's userdata area:
  $userDataID = $currentUserArray["ID"];
  $userDataImageURL = $currentUserArray["userImage"];
  $userDataUserName = $currentUserArray["Username"];

  $getPostsFunctionReturnArray = getPostsArray($conn, $currentCategory,$userDataUserName, $userDataID, $isCurrentUsersPage, $profileUserID);
  $postsArray = $getPostsFunctionReturnArray;
  include "includes/profile.html"
?>
