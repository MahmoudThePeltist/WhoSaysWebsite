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
  $userBox = '<div class="userData">
    <div class="postUserImageSpace"><img class="postUserImage" src="'.$currentUserArray["userImage"].'"></div>
    <h3>'.$currentUserArray["Username"].'</h3>
    <p style="font-size:12pt;">'.$rank.'</p><form method="POST"><button class="logOutBtn" name="logOutBtn">Log Out</button></form>
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
  $postObject = $conn->query("SELECT postid FROM posttable WHERE category = '$currentCategory'");
  while($row = $postObject->fetch_assoc()){
    foreach($row as $key => $value){
      //get all the post data from the row
      $innerPostObject = $conn->query("SELECT * FROM posttable where postid = '$value'");
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
      if($Username["Username"] == $currentUserName){$trashcan = '<form method="POST"><button class="trashBtn" name="trashBtn" value="'.$value.'"><i class="fa fa-trash"></i></button></form>';}
      else{$trashcan = '';}
      //check if current user is admin, if true place trash cans on all posts
      if($currentUserArray["Premissions"] == 1){$trashcan = '<form method="POST"><button class="trashBtn" name="trashBtn" value="'.$value.'"><i class="fa fa-trash"></i></button></form>';}
      //create the html code using the html template
      $newPost .= '<div class="postHolder">';
      $newPost .= '<div class="postTitle"><div class="postUserImageSpace"><img class="postUserImage" src="'.$Username["userImage"].'"></div>';
      $newPost .= $trashcan . '<h2>'.$Username["Username"].' Posted:</h2><h3 class="categoryLabelInPost">'.$catName["category"].'<h3>';
      $newPost .= '<h3>'.$row["date"].'<h3></div><div class="postText"><p>'.$row["text"].'</p></div><div class="buttonHolder">';
      $newPost .= '<button class="emotiBtns" id="likes" value="'.$row["postId"].'">👍<sup class="emotiBtnText">'.$row["likes"].'</sup></button>';
      $newPost .= '<button class="emotiBtns" id="hates" value="'.$row["postId"].'">🤬<sup class="emotiBtnText">'.$row["hates"].'</sup></button>';
      $newPost .= '<button class="emotiBtns" id="angers" value="'.$row["postId"].'">🙊<sup class="emotiBtnText">'.$row["angers"].'</sup></button>';
      $newPost .= '<button class="emotiBtns" id="deads" value="'.$row["postId"].'"> 💀<sup class="emotiBtnText">'.$row["deads"].'</sup></button></div></div>';
      $posts = $newPost . $posts;
      $newPost = "";
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, intial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie-edge">
  <link href="https://fonts.googleapis.com/css?family=Raleway|Unlock" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="jquery-3.3.1.js"></script>
  <link rel="stylesheet" type="text/css" href="styles.css">
<style></style>
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

  <script>
    //display box:
    var userName = "<?php echo $currentUserName; ?>";
    var inputActive = false;
    document.getElementById("postBtn").addEventListener("click",showBox);
    function showBox(){
      if (inputActive == false){
        document.getElementById('makePostBox').style.display = 'block';
        for (var i=0; i<100;i++){
          setTimeout(function(){document.getElementById('makePostBox').style.opacity = i/100},100);
        }
        inputActive = true;
      }else{
        document.getElementById('makePostBox').style.display = 'none';
  			document.getElementById('makePostBox').style.opacity = 0;
        inputActive = false;
      }
    }

    //Reaction buttons:
    var emotiBtns = document.getElementsByClassName("emotiBtns");
    for(let i = 0; i < emotiBtns.length; i++){
      emotiBtns[i].addEventListener("click",addPoint);
    }
    function addPoint(e){
      textElement = e.target.children[0];
      value = textElement.innerHTML;
      textElement.innerHTML = ++value;
      //New values
      var newValueVar = e.target.children[0].innerHTML;
      var btnTypeVar = e.target.id;
      var postIdVar = e.target.value;
      //ajax operation
      $(document).ready(function(){
        $.ajax({
           url: 'emoticon.php',
           type: 'POST',
           data: {
             newValue: newValueVar,
             btnType: btnTypeVar,
             postId: postIdVar,
           }
         });
      });
     }
  </script>
</body>

</html>
