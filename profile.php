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
  $userDataArray = getUserData($conn, $profileUserName);
  //rank of user
  if($userDataArray["Premissions"]){$rank = "Admin";}else{$rank = "User";}
  //variables to be used in the profile template's userdata area:
  $profileUserID = $userDataArray["ID"];
  $userDataImageURL = $userDataArray["userImage"];
  $userDataUserName = $userDataArray["Username"];
  //get the profile text
  $profileText = getProfileText($conn, $profileUserID);
  //get all the posts for this spcific user, to be used in the HTML template
  $postsArray = getPostsArray($conn, $currentCategory,$userDataUserName, $profileUserID, $isCurrentUsersPage, $profileUserID);
  //get the HTML template
  include "includes/profile.html";
  $conn->close();
?>
