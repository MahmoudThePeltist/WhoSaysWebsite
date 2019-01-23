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
  //Make sure the page belongs to this specific user
  if($profileUserName === $currentUserName){
    $isCurrentUsersPage = True;
  } else {
    $isCurrentUsersPage = False;
  }
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

  $postObject = $conn->query("SELECT postId FROM posttable WHERE userID = '$profileUserID'");
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
      //check to see if current post was made by user, if true add trash can
      if($Username["Username"] == $currentUserName){
        $trashcan = '<form method="POST"><button class="trashBtn" name="trashBtn" value="'.$value.'"><i class="fa fa-trash"></i></button></form>';
      }
      else{$trashcan = '';}
      //check if current user is admin, if true place trash cans on all posts
      if($currentUserArray["Premissions"] == 1){
        $trashcan = '<form method="POST"><button class="trashBtn" name="trashBtn" value="'.$value.'"><i class="fa fa-trash"></i></button></form>';
      }
      //create the comment html code
      $comments = "";
      $commentsObject = $conn->query("SELECT commentId FROM commenttable WHERE parentID = '$value'");
      while($comTabRow = $commentsObject->fetch_assoc()){
        foreach ($comTabRow as $comkey => $comvalue) {
          //get the data in the row for this specific comment
          $thisCommentObject = $conn->query("SELECT * FROM commenttable WHERE commentId = '$comvalue'");
          $thisComment = $thisCommentObject->fetch_assoc();
          //get commenter data based on the ID in the comment table
          $commentervalue = $thisComment["userID"];
          $thisCommenterObject = $conn->query("SELECT * FROM usertable WHERE ID = '$commentervalue'");
          $thisCommenter = $thisCommenterObject->fetch_assoc();
          //get the time elapsed
          $commentTimeElapsed = timeAgo($thisComment["commentDate"]);
          $comment = '
          <div class="commentContainer">
            <div class="commentUserImageSpace">
              <img class="userImage commentUserImage" src="'.$thisCommenter["userImage"].'">
            </div>
            <div class="commentContainerContainer">
              <div class="commentUserName">'.$thisCommenter["Username"].'</div>
              <div class="commentDate">'.$commentTimeElapsed.'</div><br>
              <hr>'.'<div class="commentText"><b>'.$thisComment["commentText"].'</b></div>'.'
            </div>
          </div>';
          $comments = $comment . $comments;
        }
      }
      //create the html code using the html template
      $timeElapsed = timeAgo($row["date"]);
      $userImageURL = $Username["userImage"];
      $userName = $Username["Username"];
      $postCatagory = $catName["category"];
      $postText = $row["text"];
      $newPost = '
      <div class="postHolder profilePost">
        <div class="postTitle">
          <div class="postUserImageSpace">
            <img class="userImage" src="'.$userImageURL.'">
          </div>
          '.$trashcan.'
          <h2>'.$userName.' Posted:</h2>
          <h3 class="categoryLabelInPost">'.$postCatagory.'</h3>
          <h3>'.$timeElapsed.'</h3>
        </div>
        <div class="postText">
          <p>'.$postText.'</p>
        </div>
        <div class="buttonHolder">
          <button class="emotiBtns" id="likes" name="1" value="'.$value.'">👍<sup class="emotiBtnText">'.$row["likes"].'</sup></button>
          <button class="emotiBtns" id="hates" name="2" value="'.$value.'">👎<sup class="emotiBtnText">'.$row["hates"].'</sup></button>
          <button class="emotiBtns" id="angers" name="3" value="'.$value.'">🙊<sup class="emotiBtnText">'.$row["angers"].'</sup></button>
          <button class="emotiBtns" id="deads" name="4" value="'.$value.'">🤣<sup class="emotiBtnText">'.$row["deads"].'</sup></button>
          <button class="commentBtn" name="commentBtn" name="5" value="'.$value.'">💬</button>
          <div class="commentsSection" id="commentsSect'.$value.'">
            <div class="commentsCreation">
              <input class="commentInput" id="commentInput'.$value.'" type="input" required>
              <button class="commentSubmitBtn" id="submitComment'.$value.'" value="'.$value.'" name="submitComment">Submit</button>
            </div>
            <div id="commentsHolder'.$value.'">
              '.$comments.'
            </div>
          </div>
        </div>
      </div>';
      $posts = $newPost . $posts;
      $newPost = "";
    }
  }
  include "includes/profile.html"
?>
