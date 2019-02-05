<?php

function connectToDB(){
    //credentials
    $dbName = "socialmediadb";
    $name = "Mahmoud";
    $password = "mahmoud1996";
    $connect =  new mysqli("localhost",$name,$password,$dbName);
    if ($connect->connect_error){
      echo "<h2>Database error: " . mysqli_connect_error() . "</h2>";
    }
    return $connect;
  }

if($_POST){
  //if we clicked log out
  if(isset($_POST['logOutBtn'])){
    include "includes/php/userClass.php";
    $userObj = new userClass();
    $userObj->logOut();
  }
  //if we clicked delete
  else if(isset($_POST['trashBtn'])){
    deletePost($_POST['postId']);
  }
  //if we change theme
  else if(isset($_POST['setTheme'])){
    changeTheme($_POST['themeA'],$_POST['themeB']);
  }
  //if we change profile text
  else if(isset($_POST['editProfileText'])){
    updateProfileText($_POST['newProfileText'], $_POST['profileUserId']);
  }
  //toggleFollow
  else if(isset($_POST['toggleFollow'])){
    toggleFollow($_POST['toggleFollow'],$_POST['currentUserId'],$_POST['profileUserId']);
  }
  //Getting value of "search" variable from "script.js".
  else if (isset($_POST['search'])) {
    searchUsers($_POST['search']);
  }
  //post a comment
  else if (isset($_POST['postComment'])) {
    postComment($_POST['postIdCom'], $_POST['userIdCom'], $_POST['commentText']);
  }
  //post a post
  else if (isset($_POST['postPost'])) {
    postPost($_POST['userIdCom'],$_POST['postType'],$_POST['postText'],$_POST['postImage'],$_POST['postcategory']);
  }
  //post an emoticon
  else if (isset($_POST['postEmoticon'])) {
    postEmoticon($_POST['userId'],$_POST['postId'],$_POST['btnType']);
  }

  //upload user image:
  if(isset($_FILES['fileInput'])){
    session_start();
    $userName = $_SESSION['userID'];
    $FR = $_FILES['fileInput'];
    uploadUserImage($userName,$FR['name'],$FR['size'],$FR['tmp_name'],$FR['type'],$FR['name']);
  }

  //upload user image:
  else if(isset($_FILES['file'])){
    session_start();
    $userName = $_SESSION['userID'];
    $FR = $_FILES['file'];
    uploadImage($userName,$FR['name'],$FR['size'],$FR['tmp_name'],$FR['type'],$FR['name']);
  }
}

function uploadUserImage($userName,$file_name,$file_size,$file_tmp,$file_type,$file_name){
  $conn = connectToDB();
  $errors= array();
  $file_ext=strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
  $expensions= array("jpeg","jpg","png");
  if(in_array($file_ext,$expensions)=== false){
    $errors[]="<h1>extension not allowed, please choose a JPEG or PNG file.</h1>";
  }
  if($file_size > 2097152) {
    $errors[]='<h1>File size must be less than 2 MB</h1>';
  }

  if(empty($errors)==true) {
    $date = new DateTime();
    $timeStamp = $date->getTimestamp();
    $noSpaceName = str_replace(' ', '_', $userName);
    $file_location = "userImages/". "user" . $noSpaceName . $timeStamp . "." . $file_ext;
    move_uploaded_file($file_tmp,$file_location);
    $conn->query("UPDATE usertable SET userImage = '$file_location' WHERE Username = '$userName'");
    echo $file_location;
    exit();
  }else{
    print_r($errors);
  }
  $conn->close();
}

function uploadImage($userName,$file_name,$file_size,$file_tmp,$file_type,$file_name){
  $errors= array();
  $file_ext=strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
  $expensions= array("jpeg","jpg","png");
  if(in_array($file_ext,$expensions)=== false){
    echo "extension not allowed, please choose a JPEG or PNG file.";
  }
  if($file_size > 12097152) {
    echo 'File size must be less than 12 MB';
  }

  if(empty($errors)==true) {
    $date = new DateTime();
    $timeStamp = $date->getTimestamp();
    $noSpaceName = str_replace(' ', '_', $userName);
    //directory to save the file
    $directory = "postImages/". "user_" . $noSpaceName;
    //create directory if it does not exist
    if(!file_exists($directory)){
      mkdir($directory, 0777, true);
    }
    //specific file location
    $file_location = $directory . "/" . $timeStamp . "." . $file_ext;
    //move file to it's location
    move_uploaded_file($file_tmp,$file_location);
    echo $file_location;
  }else{
    echo "Error.";
  }
}

function postComment($postIdCom,$userIdCom,$commentText){
  //connect to DB
  $conn = connectToDB();
  $insertedComment = formValidate($commentText);
  //update table with new comment
  $conn->query("INSERT INTO `commenttable`(`parentID`, `userID`, `commentText`) VALUES ('$postIdCom','$userIdCom','$insertedComment')");
  $conn->close();
}

function postPost($userIdCom,$postType,$postText,$postImage,$postcategory){
  //connect to DB
  $conn = connectToDB();
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
  $conn->close();
}

function postEmoticon($userId,$postId,$btnType){
    //connect to DB
    $conn = connectToDB();
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
    $conn->close();
}

function getUserData($conn, $userName){
  $userDataObj = $conn->query("SELECT * FROM `usertable` WHERE `Username` = '$userName'");
  $userDataArray = $userDataObj->fetch_assoc();
  return $userDataArray;
}

function getProfileText($userId){
  $conn = connectToDB();
  $getProfTxtObj = $conn->query("SELECT `profileText` FROM `userprofiletable` WHERE `userId` = '$userId'");
  $getProfTxtFetch = $getProfTxtObj->fetch_assoc();
  if($getProfTxtFetch['profileText']){
    $profileText = $getProfTxtFetch['profileText'];
    return $profileText;
  } else {
    $profileText = "Welcome to my profile :)";
    $conn->query("INSERT INTO `userprofiletable`(`userId`, `profileText`) VALUES ('$userId','$profileText')");
    return $profileText;
  }
  $conn->close();
}

function updateProfileText($newProfileText, $userId){
  $conn = connectToDB(); //connect to database
  $conn->query("UPDATE `userprofiletable` SET `profileText` = '$newProfileText' WHERE `userId` = '$userId'");
  echo $newProfileText;
  $conn->close();
}

function changeTheme($themeA, $themeB){
  session_start();
  $_SESSION['primaryTheme'] = $themeA;
  $_SESSION['secondaryTheme'] = $themeB;

  $retContent = "
    <link rel='stylesheet' type='text/css' href='CSS/theme" . $_SESSION['primaryTheme'] . ".css'>" .
    "<link rel='stylesheet' type='text/css' href='CSS/themeD" . $_SESSION['secondaryTheme'] . ".css'>";
  echo $retContent;
}

function toggleFollow($action, $followerId, $followedId){
  $conn = connectToDB();
  if($action == 1){
    $conn->query("INSERT INTO `userfollowtable`(`followerId`, `followedId`) VALUES ('$followerId','$followedId')");
  } else if ($action == 2){
    $conn->query("DELETE FROM `userfollowtable` WHERE `followerId` = '$followerId' AND `followedId` = '$followedId'");
  }
  $conn->close();
}

function followStats($profileUserId){
  //get the data where the user is the follower and the followed from the database
  $conn = connectToDB();
  $followerObj = $conn->query("SELECT * FROM `userfollowtable` WHERE `followerId` = '$profileUserId'");
  $followedObj = $conn->query("SELECT * FROM `userfollowtable` WHERE `followedId` = '$profileUserId'");
  $conn->close();
  //get the number of followers and followed from the returned data
  $numberOfFollowed = 0;
  $numberOfFollowers = 0;
  while($followerFetch = $followerObj->fetch_assoc()){
    $numberOfFollowed += 1;
  }
  while($followedFetch = $followedObj->fetch_assoc()){
    $numberOfFollowers  += 1;
  }
  return [$numberOfFollowers,$numberOfFollowed];
}

function followerAuthenticate($conn,$followerId,$followedId){
  //check if two users are actually in a follower->followed relationship
  $query = "SELECT * FROM `userfollowtable` WHERE `followerId` = '$followerId' AND `followedId` = '$followedId'";
  $valueObject = $conn->query($query);
  $value = $valueObject->fetch_assoc();
  //if follower->followed relationship exists return true, else return false
  if($value){
    return "Unfollow";
  } else {
    return "Follow";
  }
}

function followIDs($profileUserId){
  //get the data where the user is the follower and the followed from the database
  $conn = connectToDB();
  $followerObj = $conn->query("SELECT * FROM `userfollowtable` WHERE `followerId` = '$profileUserId'");
  $followedObj = $conn->query("SELECT * FROM `userfollowtable` WHERE `followedId` = '$profileUserId'");
  $conn->close();
  //get the number of followers and followed from the returned data
  $followedIDs = array();
  $followerIDs = array();
  while($followerFetch = $followerObj->fetch_assoc()){
    $followedIDs[] = $followerFetch['followedId'];
  }
  while($followedFetch = $followedObj->fetch_assoc()){
    $followerIDs[] = $followedFetch['followerId'];
  }
  return [$followerIDs,$followedIDs];
}

function getFollowerPosts($conn,$followerIdsArray,$currentUserId){
  $postsArray = [];
  //going over IDs array
  for ($i = 0; $i < sizeof($followerIdsArray); $i++){
    //post array
    $postArray = getSpecificPost($conn,0,$followerIdsArray[$i],$currentUserId, "", "User");
    //add the array to the posts array
    if ($postArray != 0){
        $postsArray[] = $postArray;
    }
  }
  return $postsArray;
}

function getCategoryData($conn){
  //categorys gotten from db
  $categoryArray = array();
  $categoriesArray = array();
  $categoryObj = $conn->query("SELECT `categoryId`, `category` FROM `catagorytable` WHERE 1");
  while($catRow = $categoryObj->fetch_assoc()){
    foreach($catRow as $catKey => $catValue){
        $categoryArray[] = $catValue;
    }
    $categoriesArray[] = $categoryArray;
    $categoryArray = array();
  }
  //return the data as json
  return json_encode($categoriesArray);
}

function deletePost($postId){
  //delete post based on ID
  $conn = connectToDB();
  $conn->query("DELETE FROM `posttable` WHERE postId = '$postId'");
  echo "DELETED";
  $conn->close();
}

function formValidate($textItem){
      $noSpaceText = str_replace(' ', '~', $textItem);
      $noBracketTextR = str_replace(')', '\)', $noSpaceText);
      $noBracketTextL = str_replace('(', '\(', $noBracketTextR);
      $noSpaceNoSignText = preg_replace('/[^A-Za-z0-9\-\!\?\~\(\)]/', '', $noBracketTextL);
      $noSignText = str_replace('~', ' ', $noSpaceNoSignText);
      return $noSignText;
  }

function searchUsers($Name){
  $conn = connectToDB();
  $ExecQuery = $conn->query("SELECT Username FROM usertable where Username LIKE '%$Name%' LIMIT 5");
  //Creating unordered list to display result.
  echo '<ul class="searchList">';
  //Fetching result from database.
  while ($Result = $ExecQuery->fetch_array()) {
    //Creating unordered list items that connect to js function to fill the search bar.
    echo '
    <li class="searchListItem" onclick="fill(\''.$Result['Username'].'\')">
     <a href="profile.php?username='.$Result['Username'].'">'.$Result['Username'].'</a>
    </li>';
 }
 echo '</ul>';
 $conn->close();
}

function timeAgo($time_ago){
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
  }

function getCommentsArray($postId, $conn){
  //create the comment html code
  $commentsArray = array();
  $commentsObject = $conn->query("SELECT commentId FROM commenttable WHERE parentID = '$postId'");
  while($comTabRow = $commentsObject->fetch_assoc()){
    foreach ($comTabRow as $comkey => $comvalue) {
      //get the data in the row for this specific comment
      $thisCommentObject = $conn->query("SELECT * FROM commenttable WHERE commentId = '$comvalue'");
      $thisComment = $thisCommentObject->fetch_assoc();
      //get commenter data based on the ID in the comment table
      $commentervalue = $thisComment["userID"];
      $thisCommenterObject = $conn->query("SELECT * FROM usertable WHERE ID = '$commentervalue'");
      $thisCommenter = $thisCommenterObject->fetch_assoc();
      //populate an array with the comment data including the time elapsed since the post was made
      $commentArray = array(
        timeAgo($thisComment["commentDate"]),
        $thisCommenter["userImage"],
        $thisCommenter["Username"],
        $thisComment["commentText"],
      );
      $commentsArray[] =  $commentArray;
    }
  }
  return $commentsArray;
}

function getPostsArray($conn, $currentCategory, $currentUserName, $currentUserId, $currentUserRank, $specificUserID = 0){
  $postsArray = [];
  //guery to get the ids of all the wanted posts
  $postGetQuery = "SELECT `postId` FROM `posttable`";
  if($currentCategory == 0){//if category is "all" select all the data
    $postGetQuery .= "";
    if ($specificUserID > 0){//if a specific user is selected
     $postGetQuery .= " WHERE userId = '$specificUserID'";
   }
  } else {
    $postGetQuery .= " WHERE `category` = '$currentCategory'";
    if ($specificUserID > 0){//if a specific user is selected
     $postGetQuery .= " AND `userId` = '$specificUserID'";
   }
  }
  $postObject = $conn->query($postGetQuery);
  while($row = $postObject->fetch_assoc()){
    foreach($row as $key => $value){
      $postArray = getSpecificPost($conn,1,$value,$currentUserId,$currentUserName,$currentUserRank);
      //add this post's data to the posts array
      if($postArray){
          $postsArray[] = $postArray;
      }
    }
  }
  if ($postsArray){
    return $postsArray;
  } else {
    $postsArray = "";
    return $postsArray;
  }
}

function getSpecificPost($conn,$choiceType,$value,$currentUserId,$currentUserName = "",$currentUserRank = "User"){
  //are we going to choose based on user ID or post
  if($choiceType) {
    //get all the post data based on the postID
    $innerPostObject = $conn->query("SELECT * FROM `posttable` where `postId` = '$value'");
  } else {
    //get all the post data based on the user ID
    $innerPostObject = $conn->query("SELECT * FROM `posttable` where `userId` = '$value'");
  }
  $row = $innerPostObject->fetch_assoc();

  if (!isset($row['text'])){
      return NULL;
  }
  //use the category id gotten from post table to get the name of the category name from category table
  $catValue = $row["category"];
  $categoryPostObject = $conn->query("SELECT `category` FROM `catagorytable` where `categoryId` = '$catValue'");
  $catName = $categoryPostObject->fetch_assoc();
  //use the user id gotten from post table to get the name of the user name from category table
  $userValue = $row["userId"];
  //get the name of the user behind this post
  $usernamePostObject = $conn->query("SELECT * FROM `usertable` where `ID` = '$userValue'");
  $Username = $usernamePostObject->fetch_assoc();

  if($Username["Username"] == $currentUserName){
    //check to see if current post was made by user, if true add trash can
    $canHaveTrashcan = 1;
  } else if($currentUserRank == "Admin"){
    //check if current user is admin, if true place trash cans on all posts
    $canHaveTrashcan = 1;
  } else{
    $canHaveTrashcan = 0;
  }
  //collect variables to be used in the post creation
  $postTimeElapsed = timeAgo($row["date"]);
  $postUserImageURL = $Username["userImage"];
  $postUserName = $Username["Username"];
  $postText = $row["text"];
  $postCatagory = $catName["category"];
  //post type image or text
  $postType = $row['postType'];
  //post image if type contains image
  $postImage = $row['imageURL'];
  //collect emoti variables
  $likes = $row["likes"];
  $hates = $row["hates"];
  $angers = $row["angers"];
  $deads = $row["deads"];
  //create array for emotis
  $emotiArray = array($likes,$hates,$angers,$deads);
  //get if user has emotis from emotisTable
  $emotisObject = $conn->query("SELECT * FROM `emotitable` WHERE `userId`= '$currentUserId' AND `postId` = '$value'");
  $emotis = $emotisObject->fetch_assoc();
  $userLikes = $emotis["likes"];
  $userHates = $emotis["hates"];
  $userAngers = $emotis["angers"];
  $userDeads = $emotis["deads"];
  //create array for userEmotis
  $userEmotiArray = array($userLikes,$userHates,$userAngers,$userDeads);
  //get the comments as an array
  $commentsArray = getCommentsArray($value, $conn);
  //create an array for this post
  $postArray = array(
    $value,
    $postTimeElapsed,
    $postUserImageURL,
    $postUserName,
    $canHaveTrashcan,
    $postCatagory,
    $postText,
    $emotiArray,
    $commentsArray,
    $userEmotiArray,
    $postType,
    $postImage,
  );
  return $postArray;
}

?>
