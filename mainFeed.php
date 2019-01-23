<?php
  include 'phpconnect.php';
  session_start();
  $posts = "";
  $newPost = "";
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
  //connect to DB
  $conn = connectToDB();
  //get the data of the currently logged in user.
  $currentUserPostObject = $conn->query("SELECT * FROM usertable where Username = '$currentUserName'");
  $currentUserArray = $currentUserPostObject->fetch_assoc();
  //rank of user
  if($currentUserArray["Premissions"]){$rank = "Admin";}else{$rank = "User";}
  //variables to be used in the mainFeed template's userdata area:
  $userDataID = $currentUserArray["ID"];
  $userDataImageURL = $currentUserArray["userImage"];
  $userDataUserName = $currentUserArray["Username"];

  if($_POST){
    if(isset($_POST['makePostBtn'])){
      $postText = $_POST['postText'];
      if($postText == ""){
        $errorText = "please enter some text";
      }else{
        $postcategory = $_POST['postcategory'];
        $userIdObject = $conn->query("SELECT ID FROM usertable WHERE Username = '$currentUserName'");
        $userIdArray = $userIdObject->fetch_assoc();
        $userId = $userIdArray["ID"];
        $postText = formValidate($postText);
        $conn->query("INSERT INTO `posttable`(`userId`,`text`, `category`) VALUES ('$userId','$postText','$postcategory')");
      }
    } else if(isset($_POST['logOutBtn'])){
      $_SESSION['userID'] = NULL;
      header('location: index.php');
    } else if(isset($_POST['trashBtn'])){
      $deletedPostId = $_POST['trashBtn'];
      $conn->query("DELETE FROM `posttable` WHERE postId = '$deletedPostId'");
    }
  }
  $getPostsFunctionReturnArray = getPostsArray($conn, $currentCategory,$currentUserName, $rank);
  $postsArray = $getPostsFunctionReturnArray;
  include "includes/mainFeed.html";
?>
