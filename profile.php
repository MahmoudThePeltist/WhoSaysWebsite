<?php
  include 'phpconnect.php';
  session_start();

  $errorText = "";

  if(!isset($_SESSION['userID'])){
    header('location: index.php');
    die();
  } else {
    $currentUserName = $_SESSION['userID'];
    $profileUserName = $_GET["username"];
  }
  //get the category
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
  $currentUserDataArray = getUserData($conn, $currentUserName);
  $userDataArray = getUserData($conn, $profileUserName);
  //profile btn variables
  $profileBtnUserName = $currentUserDataArray['Username'];
  //is a follower?
  $followBtnTxt = followerAuthenticate($conn,$currentUserDataArray["ID"],$userDataArray["ID"]);
  //number of followers and Following
  $profileUserID = $userDataArray["ID"];
  $followStatsArray = followStats($profileUserID);

  //rank of user
  if($userDataArray["Premissions"]){$rank = "Admin";}else{$rank = "User";}
  //variables to be used in the profile template's userdata area:
  $userDataImageURL = $userDataArray["userImage"];
  $userDataUserName = $userDataArray["Username"];
  //get the profile text
  $profileText = getProfileText($profileUserID);

  //if the "profileView" get variable is set change whether the profile shows the
  //user's own posts or those of their followers
  if(isset($_GET['profileView']) && $_GET['profileView'] == "MyPosts"){
    $profileView = 1;
  } else {
    if($isCurrentUsersPage){
      $profileView = 0;
    } else {
      $profileView = 1;
    }
  }
  if($profileView){
    //get all the posts for this spcific user, to be used in the HTML template
    $postsArray = getPostsArray($conn, $currentCategory,$userDataUserName, $profileUserID, $isCurrentUsersPage, $profileUserID);
  } else {
    //get all the posts made by people this user follows
    $followerIds = followIDs($profileUserID);
    $postsArray = getFollowerPosts($conn, $followerIds[1], $profileUserID);
  }

  //get the HTML template
  include "includes/profile.html";
  $conn->close();
?>
