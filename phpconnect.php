<?php

function connectToDB(){
    //cridentials
    $dbName = "socialmediadb";
    $name = "Mahmoud";
    $password = "mahmoud1996";
    $connect =  new mysqli("localhost",$name,$password,$dbName);
    if ($connect->connect_error){
      echo "<h2>Database error: " . mysqli_connect_error() . "</h2>";
    }
    return $connect;
  }

function formValidate($textItem){
      $noSpaceText = str_replace(' ', '~', $textItem);
      $noBracketTextR = str_replace(')', '\)', $noSpaceText);
      $noBracketTextL = str_replace('(', '\(', $noBracketTextR);
      $noSpaceNoSignText = preg_replace('/[^A-Za-z0-9\-\!\?\~\(\)]/', '', $noBracketTextL);
      $noSignText = str_replace('~', ' ', $noSpaceNoSignText);
      return $noSignText;
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
      $postCatagory = $catName["category"];
      $postText = $row["text"];
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
      $uerEmotiArray = array($userLikes,$userHates,$userAngers,$userDeads);
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
        $uerEmotiArray,
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
