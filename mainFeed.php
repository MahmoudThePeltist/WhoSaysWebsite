<?php
  include 'phpconnect.php';
  session_start();
  $posts = "";
  $newPost = "";
  $errorText = "";
  if(!isset($_SESSION['currentCategory'])){
    $_SESSION['currentCategory'] = 0;
  }
  $currentCategory = 0;
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
  if($currentUserArray["Premissions"]){$rank = "Admin";}else{$rank = "User";}
  $userBox = '
  <div class="userData">
    <div class="headerUserImageSpace">
      <img class="userImage" id="mainUserImage" name="'.$currentUserArray["ID"].'" src="'.$currentUserArray["userImage"].'">
    </div>
    <h3 class="userDataUserName" id="userDataUserName">'.$currentUserArray["Username"].'</h3><p class="userDataUserRank">'.$rank.'</p>
    <form action="upload.php" class="changeUserImageForm" method="POST" enctype="multipart/form-data">
      <input type="file" class="changeUserImage" onchange="form.submit()" name="fileInput"/>
    </form>
    <form method="POST">
      <button class="logOutBtn" name="logOutBtn">Log Out</button>
    </form>
  </div>';
  if($_GET){
    if(isset($_GET['button0'])) {$_SESSION['currentCategory'] = 0;}
    else if(isset($_GET['button1'])) {$_SESSION['currentCategory'] = 1;}
    else if(isset($_GET['button2'])) {$_SESSION['currentCategory'] = 2;}
    else if(isset($_GET['button3'])) {$_SESSION['currentCategory'] = 3;}
    else if(isset($_GET['button4'])) {$_SESSION['currentCategory'] = 4;}
    else{$_SESSION['currentCategory'] = 5;}
  }
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
  $currentCategory = $_SESSION['currentCategory'];
  $postObject = $conn->query("SELECT postId FROM posttable WHERE category = '$currentCategory'");
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
      $newPost = '
      <div class="postHolder">
        <div class="postTitle">
          <div class="postUserImageSpace">
            <img class="userImage" src="'.$Username["userImage"].'">
          </div>
          '.$trashcan.'
          <h2>'.$Username["Username"].' Posted:</h2>
          <h3 class="categoryLabelInPost">'.$catName["category"].'</h3>
          <h3>'.$timeElapsed.'</h3>
        </div>
        <div class="postText">
          <p>'.$row["text"].'</p>
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
?>

<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, intial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie-edge">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway|Unlock">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <script src="jquery-3.3.1.js"></script>
</head>

<body id="body">

  <header>
    <?php echo $userBox; ?>
    <h1>Who Says?</h1>
    <h4><?php if(isset($catName)){echo $catName['category'];} ?></h4>
    <nav>
      <form method="GET">
        <button class="navButton" name="button0" value="0">General</button>
        <button class="navButton" name="button1" value="1">Sports</button>
        <button class="navButton" name="button2" value="2">Film/TV</button>
        <button class="navButton" name="button3" value="3">Funny</button>
        <button class="navButton" name="button4" value="4">Music</button>
        <button class="navButton" name="button5" value="5">Games</button>
      </form>
    </nav>
  </header>

  <div class="postOpenBtn">
    <button class="generalButton" id="postBtn">Make a Post</button>
  </div>

  <div class="postHolder" id="makePostBox">
    <form method="POST">
      <textarea rows="10" cols="30" class="textInput" name="postText" id="makePostInput" required></textarea>
      <select class="categorySelect" name="postcategory" id="categorySelect">
       <option value="0">General</option>
       <option value="1">Sports</option>
       <option value="2">Film/TV</option>
       <option value="3">Funny</option>
       <option value="4">Music</option>
       <option value="5">Games</option>
      </select>
      <button class="generalButton makePostBtn" name="makePostBtn" id="makePostbtn">Post</button>
      <h5><?php echo $errorText; ?></h5>
    </form>
  </div>

  <?php echo $posts; ?>
  <script type="text/javascript" src="js/mainFeedScripts.js"></script>

</body>

</html>
