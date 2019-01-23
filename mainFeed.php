<?php
  session_start();
  if(!isset($_SESSION['userID'])){
    header('location: index.php');
    die();
  } else {
    $userName = $_SESSION['userID'];
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
    <h1><b class="siteTitle">Who</b> Says?</h1>
    <h3>Welcome <?php echo $userName; ?></h3>
    <nav>
      <button class="navButton">General</button>
      <button class="navButton">Sports</button>
      <button class="navButton">Film/TV</button>
      <button class="navButton">Funny</button>
      <button class="navButton">Music</button>
      <button class="navButton">Games</button>
    </nav>
  </header>

  <div class="postOpenBtn">
    <button class="generalButton" id="postBtn">Make a Post</button>
  </div>

  <div class="postHolder" id="makePostBox">
    <form method="POST">
      <textarea rows="10" cols="30" class="textInput" id="makePostInput"></textarea>
      <select class="catagorySelect" id="catagorySelect">
       <option value="General">General</option>
       <option value="Sports">Sports</option>
       <option value="Film/TV">Film/TV</option>
       <option value="Funny">Funny</option>
       <option value="Music">Music</option>
       <option value="Games">Games</option>
      </select>
      <button class="generalButton" id="makePostbtn">Post</button>
    </form>
  </div>

  <div id="posts">
    <div class="postHolder">
            <div class="postTitle">
              <div class="postUserImageSpace">
                <img class="postUserImage" src="userImages/user0.gif">
              </div>
              <h2>Mahmoud Posted:</h2>
              <h3 class="catagoryLabelInPost">Funny<h3>
              <h3>Today 12:34pm<h3>
            </div>
        <div class="postText">
          <p>You need to see a phsycotherapist.</p>
        </div>
        <div class="buttonHolder">
            <button class="emotiBtns">👍<sup class="emotiBtnText">42</sup></button>
            <button class="emotiBtns">🤬<sup class="emotiBtnText">12</sup></button>
            <button class="emotiBtns">🙊<sup class="emotiBtnText">4</sup></button>
            <button class="emotiBtns"> 💀<sup class="emotiBtnText">32</sup></button>
        </div>
      </div>
    <div class="postHolder">
          <div class="postTitle">
            <div class="postUserImageSpace">
              <img class="postUserImage" src="userImages/user1.jpg">
            </div>
            <h2>Joe Posted:</h2>
            <h3 class="catagoryLabelInPost">Film/TV<h3>
            <h3>Today 11:34am<h3>
          </div>
          <div class="postText">
            <p>unpopular opinion time: the godfather is over rated!</p>
          </div>
          <div class="buttonHolder">
              <button class="emotiBtns">👍<sup class="emotiBtnText">0</sup></button>
              <button class="emotiBtns">🤬<sup class="emotiBtnText">25</sup></button>
              <button class="emotiBtns">🙊<sup class="emotiBtnText">0</sup></button>
              <button class="emotiBtns"> 💀<sup class="emotiBtnText">12</sup></button>
          </div>
        </div>
    <div class="postHolder">
            <div class="postTitle">
              <div class="postUserImageSpace">
                <img class="postUserImage" src="userImages/user3.jpg">
              </div>
              <h2>Label Posted:</h2>
              <h3 class="catagoryLabelInPost">Sports<h3>
              <h3>Today 1:34am<h3>
            </div>
            <div class="postText">
              <p>I really like footballs. they're' so round.</p>
            </div>
            <div class="buttonHolder">
                <button class="emotiBtns">👍<sup class="emotiBtnText">152</sup></button>
                <button class="emotiBtns">🤬<sup class="emotiBtnText">0</sup></button>
                <button class="emotiBtns">🙊<sup class="emotiBtnText">0</sup></button>
                <button class="emotiBtns"> 💀<sup class="emotiBtnText">0</sup></button>
            </div>
          </div>
  </div>

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
      var htmlCode = '<div class="postHolder"><div class="postTitle"><div class="postUserImageSpace"><img class="postUserImage" src="userImages/user2 .jpg"></div><h2> ' + userName + ' Posted:</h2><h3>Today 12:34pm<h3></div><div class="postText"><p>' + document.getElementById("makePostInput").value + '</p></div><div class="buttonHolder"><button class="emotiBtns">👍<sup class="emotiBtnText">0</sup></button><button class="emotiBtns">🤬<sup class="emotiBtnText">0</sup></button><button class="emotiBtns">🙊<sup class="emotiBtnText">0</sup></button><button class="emotiBtns"> 💀<sup class="emotiBtnText">0</sup></button></div></div>';
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
