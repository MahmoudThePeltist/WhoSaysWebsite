<?php
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
    $userName = $_SESSION['userID'];
  }
  if(!isset($_SESSION['tableConnection'])){
    $dbName = "socialmediadb";
    $name = "Mahmoud";
    $password = "mahmoud1996";
    $conn =  new mysqli("localhost",$name,$password,$dbName);
  } else {
    $conn = $_SESSION['tableConnection'];
  }
  if($_GET){
    if(isset($_GET['button0'])) {$_SESSION['currentCategory'] = 0;}
    else if(isset($_GET['button1'])) {$_SESSION['currentCategory'] = 1;}
    else if(isset($_GET['button2'])) {$_SESSION['currentCategory'] = 2;}
    else if(isset($_GET['button3'])) {$_SESSION['currentCategory'] = 3;}
    else if(isset($_GET['button4'])) {$_SESSION['currentCategory'] = 4;}
    else{$_SESSION['currentCategory'] = 5;}
  }
  if($_POST){
    $postText = $_POST['postText'];
    if($postText == ""){
      $errorText = "please enter some text";
    }else{
      $postcategory = $_POST['postcategory'];
      $userIdObject = $conn->query("SELECT ID FROM usertable WHERE Username = '$userName'");
      $userIdArray = $userIdObject->fetch_assoc();
      $userId = $userIdArray["ID"];
      $conn->query("INSERT INTO `posttable`(`userId`,`text`, `category`) VALUES ('$userId','$postText','$postcategory')");
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
      $usernamePostObject = $conn->query("SELECT Username FROM usertable where ID = '$userValue'");
      $Username = $usernamePostObject->fetch_assoc();
      //create the html code using the html template
      $newPost .= '<div class="postHolder">';
      $newPost .= '<div class="postTitle"><div class="postUserImageSpace"><img class="postUserImage" src="userImages/userDefault.png">';
      $newPost .= '</div><h2>'.$Username["Username"].' Posted:</h2><h3 class="categoryLabelInPost">'.$catName["category"].'<h3><h3>'.$row["date"].'<h3></div>';
      $newPost .= '<div class="postText"><p>'.$row["text"].'</p></div><div class="buttonHolder">';
      $newPost .= '<button class="emotiBtns">👍<sup class="emotiBtnText">'.$row["likes"].'</sup></button>';
      $newPost .= '<button class="emotiBtns">🤬<sup class="emotiBtnText">'.$row["hates"].'</sup></button>';
      $newPost .= '<button class="emotiBtns">🙊<sup class="emotiBtnText">'.$row["angers"].'</sup></button>';
      $newPost .= '<button class="emotiBtns"> 💀<sup class="emotiBtnText">'.$row["deads"].'</sup></button></div></div>';
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
  <link rel="stylesheet" type="text/css" href="styles.css">
<style></style>
</head>

<body id="body">

  <header>
    <h1>Who Says?</h1>
    <h3>Welcome <?php echo $userName; ?></h3>
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
       <option value="General">General</option>
       <option value="Sports">Sports</option>
       <option value="Film/TV">Film/TV</option>
       <option value="Funny">Funny</option>
       <option value="Music">Music</option>
       <option value="Games">Games</option>
      </select>
      <button class="generalButton" id="makePostbtn">Post</button>
      <h5><?php echo $errorText; ?></h5>
    </form>
  </div>

  <?php echo $posts; ?>

  <script>
    //display box:
    var userName = "<?php echo $userName; ?>";
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
    //post status
    //document.getElementById("makePostbtn").addEventListener("click",makePost);
    function makePost(){
      var htmlCode = '';
      document.getElementById("posts").innerHTML = htmlCode + document.getElementById("posts").innerHTML;
      document.getElementById("makePostInput").value = "";
      //setting up buttons again
      var emotiBtns = document.getElementsByClassName("emotiBtns");
      for(let i = 0; i < emotiBtns.length; i++){
        emotiBtns[i].addEventListener("click",addPoint);
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
    }
    //Switching images:
    let i = 1;
    document.getElementById("postImage1").addEventListener("click",flipImage);
    function flipImage(){
      i++;
      document.getElementById("postImage1").src = "pics/" + i + ".jpg";
      if(i>5){i=0;}
    }
  </script>
</body>

</html>
