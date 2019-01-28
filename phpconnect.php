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
}

class userClass {
  public $conn;

  public function __construct($connToDB){
    $this->conn = $connToDB;
  }

  public function loginUser($loginUsername,$loginPassword){
    $passObject = $this->conn->query("SELECT `Password` FROM `usertable` WHERE `Username` = '$loginUsername'");
    $pass = $passObject->fetch_assoc();
    $hashedPassword = $pass['Password'];
    if(password_verify($loginPassword, $hashedPassword)){
      $whatHappened = "<b class='inputLabel'>Logged in as " . $loginUsername . "</b><br>";
      //set user id for this session and go to mainfeed
      $_SESSION['userID'] = $loginUsername;
      header('location: mainFeed.php');
      exit();
    } else {
      $whatHappened = "<b class='inputLabel'>Wrong Username or Password.</b><br>";
    }
  }

  public function registerUser($registerUsername,$registerEmail,$registerPassword1,$registerPassword2){
    $defaultPicture = 'userImages/userDefault.png';
    $userCheckObject = $this->conn->query("SELECT `Username` FROM `usertable` WHERE `Username` = '$registerUsername'");
    $userCheck = $userCheckObject->fetch_assoc();
    if(!isset($userCheck)){
      if ($registerPassword1 == $registerPassword2){
        $hashedPassword = password_hash($registerPassword1, PASSWORD_DEFAULT);
        $this->conn->query("INSERT INTO `usertable`(`ID`, `Username`, `Email`, `Password`,`userImage`,`Premissions`) VALUES (NULL,'$registerUsername','$registerEmail','$hashedPassword','$defaultPicture','0')");
        //create default profile
        $registerIdObj = $this->conn->query("SELECT `ID` from `usertable` WHERE `username` = '$registerUsername'");
        $registerIdFetch = $registerIdObj->fetch_assoc();
        $registerId = $registerIdFetch['ID'];
        $this->conn->query("INSERT INTO `userprofiletable`(`userId`, `profileText`) VALUES ('$registerId','Welcome to my profile!')");
        //set user id for this session and go to mainfeed
        $_SESSION['firstLaunch'] = 1;
        $_SESSION['userID'] = $registerUsername;
        header('location: mainFeed.php');
        exit();
      } else {
        $whatHappened = "<b class='inputLabel'>Make sure both passwords are the same!</b>";
      }
    } else {
      $whatHappened = "<b class='inputLabel'>That username is taken!</b>";
    }
  }

  function logOut(){
    //log out
    session_start();
    $_SESSION['userID'] = NULL;
    header('location: index.php');
  }
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
    return 1;
  } else {
    return 0;
  }
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
  $postGetQuery = "SELECT postId FROM posttable";
  if($currentCategory == 0){//if category is "all" select all the data
    $postGetQuery .= "";
    if ($specificUserID > 0){//if a specific user is selected
     $postGetQuery .= " WHERE userId = '$specificUserID'";
   }
  } else {
    $postGetQuery .= " WHERE category = '$currentCategory'";
    if ($specificUserID > 0){//if a specific user is selected
     $postGetQuery .= " AND userId = '$specificUserID'";
   }
  }
  $postObject = $conn->query($postGetQuery);
  while($row = $postObject->fetch_assoc()){
    foreach($row as $key => $value){
      //get all the post data from the row
      $innerPostObject = $conn->query("SELECT * FROM posttable where postId = '$value'");
      $row = $innerPostObject->fetch_assoc();
      //use the category id gotten from post table to get the name of the category name from category table
      $catValue = $row["category"];
      $categoryPostObject = $conn->query("SELECT category FROM catagorytable where categoryId = '$catValue'");
      $catName = $categoryPostObject->fetch_assoc();
      //use the user id gotten from post table to get the name of the user name from category table
      $userValue = $row["userId"];
      //get the name of the user behind this post
      $usernamePostObject = $conn->query("SELECT * FROM usertable where ID = '$userValue'");
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
      //add this post's data to the posts array
      $postsArray[] = $postArray;
    }
  }
  if ($postsArray){
    return $postsArray;
  } else {
    $postsArray = "";
    return $postsArray;
  }
}

?>
