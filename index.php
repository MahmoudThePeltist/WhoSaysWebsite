<?php
  include 'phpconnect.php';
  session_start();
  $whatHappened = "";
  //connect to DB
  $conn = connectToDB();
  if ($_POST){
    //Login handling
    if(isset($_POST["loginButton"])){
      $loginUsername = $_POST["loginUsername"];
      $loginPassword = $_POST["loginPassword"];
      $passObject = $conn->query("SELECT Password FROM usertable WHERE Username = '$loginUsername'");
      $pass = $passObject->fetch_assoc();
      $hashedPassword = $pass['Password'];
      if(password_verify($loginPassword, $hashedPassword)){
        $whatHappened = "<b class='inputLabel'>Logged in as " . $loginUsername . "</b><br>";
        $_SESSION['userID'] = $loginUsername;
        header('location: mainFeed.php');
        exit();
      } else {
        $whatHappened = "<b class='inputLabel'>Wrong Username or Password.</b><br>";
      }
    }
    //Registration handling
    else if(isset($_POST["registerButton"])){
      $registerUsername = $_POST["registerUsername"];
      $registerEmail = $_POST["registerEmail"];
      $registerPassword1 = $_POST["registerPassword1"];
      $registerPassword2 = $_POST["registerPassword2"];
      $defaultPicture = 'userImages/userDefault.png';
      $userCheckObject = $conn->query("SELECT Username FROM usertable WHERE Username = '$registerUsername'");
      $userCheck = $userCheckObject->fetch_assoc();
      if(!isset($userCheck)){
        if ($registerPassword1 == $registerPassword2){
          $hashedPassword = password_hash($registerPassword1, PASSWORD_DEFAULT);
          $conn->query("INSERT INTO `usertable`(`ID`, `Username`, `Email`, `Password`,`userImage`,`Premissions`) VALUES (NULL,'$registerUsername','$registerEmail','$hashedPassword','$defaultPicture','0')");
          $whatHappened = "<b class='inputLabel'>Registered as " . $registerUsername . "</b>";
        } else {
          $whatHappened = "<b class='inputLabel'>Make sure both passwords are the same!</b>";
        }
      } else {
        $whatHappened = "<b class='inputLabel'>That username is taken!</b>";
      }
    }
  }
  //Closing the DB
  $conn->close();
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
    <h3>Find out what they're talking about!</h3>
  </header>

  <div class="indexMainHolder">
    <div class="indexImageHolder"></div>
    <div class="indexMainForm">

    </div>
  </div>
  <div class="container">
    <div class="row">
    <form class="loginForm" id="loginForm" method="POST">
        <h1 class="loginTitle">Who Says?</h1>
        <h5 class="inputTitle" styles="style="font-family: 'Unlock', cursive"">Sign in</h5>
        <h3 class="formLabel">Name</h3>
        <input type="text" class="loginInput" name="loginUsername" tabindex="1" required>
        <h3 class="formLabel">Password</h3>
        <input type="password" class="loginInput" name="loginPassword" tabindex="3" required><br>
        <div class="indexButtonHolder">
          <button class="loginbutton"  name="loginButton" tabindex="4">Login</button><br>
        </div>
    </form>
    </div>
    <div class="row">
    <form class="loginForm" id="registerForm"  method="POST">
      <h1 class="loginTitle">Who Says?</h1>
      <h5 class="inputTitle" styles="style="font-family: 'Unlock', cursive"">Sign up</h5>
      <h3 class="formLabel">Name</h3>
      <input type="text" class="loginInput" name="registerUsername" tabindex="1" required>
      <h3 class="formLabel">Email</h3>
      <input type="email" class="loginInput" name="registerEmail" tabindex="2" required>
      <h3 class="formLabel">Password</h3>
      <input type="password" class="loginInput" name="registerPassword1" tabindex="3" required>
      <h3 class="formLabel">Confirm Password</h3>
      <input type="password" class="loginInput" name="registerPassword2" tabindex="3" required><br>
      <div class="indexButtonHolder">
        <button class="registerbutton" name="registerButton" tabindex="5">Register</button><br>
      </div>
    </form>
    </div>
      <div class="indexButtonHolder">
        <a id="regToggleButton" onClick="toggleRegistration()">Register</a>
      </div>
      <?php
      if($_POST){
        echo $whatHappened;
      }
      ?>
    </form>
  </div>
  <script>
    var regToggleFlag = 0;
    function toggleRegistration(){
      if(regToggleFlag == 1){
        document.getElementById('registerForm').style.display = 'none';
  			document.getElementById('loginForm').style.display = 'block';
        document.getElementById('regToggleButton').innerHTML = 'Register';
        regToggleFlag = 0;
      } else {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('registerForm').style.display = 'block';
        document.getElementById('regToggleButton').innerHTML = 'Sign In';
        regToggleFlag = 1;
      }
    }
    function closeRegistration(){
    }
    function goToFeed(){
      var url = "mainFeed.php";
      window.open(url,'_self');
    }
  </script>
</body>

</html>
