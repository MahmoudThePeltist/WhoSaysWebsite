<?php
  include 'phpconnect.php';
  //connect to DB
  $conn = connectToDB();

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
  //get the data of the currently logged in user.
  $currentUserPostObject = $conn->query("SELECT * FROM usertable where Username = '$currentUserName'");
  $currentUserArray = $currentUserPostObject->fetch_assoc();
  //rank of user
  if($currentUserArray["Premissions"]){$rank = "Admin";}else{$rank = "User";}
  //variables for the currently signed in user
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
        $postText = formValidate($postText);
        $conn->query("INSERT INTO `posttable`(`userId`,`text`, `category`) VALUES ('$userDataID','$postText','$postcategory')");
      }
    }
  }
  //theme variables:
  if(!isset($_SESSION['primaryTheme'])){
    $_SESSION['primaryTheme'] = 0;
  } if (!isset($_SESSION['secondaryTheme'])){
    $_SESSION['secondaryTheme'] = 0;
  }
  //bost building
  $getPostsFunctionReturnArray = getPostsArray($conn, $currentCategory,$currentUserName, $userDataID, $rank);
  $postsArray = $getPostsFunctionReturnArray;
  // //testing data
  // foreach($postsArray as $key => $value){
  //   print_r($value);
  //   echo "<br><br>";
  // }
  include "includes/mainFeed.html";
?>
