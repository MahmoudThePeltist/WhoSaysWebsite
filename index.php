<?php
  //Database login information
  $dbName = "socialmediadb";
  $name = "Mahmoud";
  $password = "mahmoud1996";
  $whatHappened = "";
  //connecting to DB
  $conn =  new mysqli("localhost",$name,$password,$dbName);
  if ($conn->connect_error){
    echo "<h5>Database error: " . mysqli_connect_error() . "</h5>";
  }
  if ($_POST){
    //Login handling
    if(isset($_POST["loginButton"])){
      $loginUsername = $_POST["loginUsername"];
      $loginPassword = $_POST["loginPassword"];
      $passObject = $conn->query("SELECT Password FROM usertable WHERE Username = '$loginUsername'");
      $pass = $passObject->fetch_assoc();
      if($loginPassword == $pass['Password']){
        $whatHappened = "<b class='inputLabel'>Logged in as " . $loginUsername . "</b><br>";
        session_start();
        $_SESSION['userID'] = $loginUsername;
        header('location: mainFeed.php');
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
      $passObject = $conn->query("SELECT Username FROM usertable WHERE Username = '$registerUsername'");
      echo gettype($passObject);
      if ($registerPassword1 == $registerPassword2){
        $conn->query("INSERT INTO `usertable`(`ID`, `Username`, `Email`, `Password`, `Premissions`) VALUES (NULL,'$registerUsername','$registerEmail','$registerPassword2','0')");
        $whatHappened = "<b class='inputLabel'>Registered as " . $registerUsername . "</b>";
      } else {
        $whatHappened = "<b class='inputLabel'>Make sure both passwords are the same!</b>";
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
    <h1><b class="siteTitle">Who</b> Says?</h1>
    <h3>Find out what their talking about!</h3>
  </header>

  <div class="indexMainHolder">
    <div class="indexImageHolder"></div>
    <div class="indexMainForm">
      <h4 class="inputTitle">Login or sign up:</h4>
      <form class="loginForm" id="loginForm" method="POST">
          <p class="inputLabel">Name:</p>
          <input type="text" class="loginInput" name="loginUsername" tabindex="1" required>
          <p class="inputLabel">Password:</p>
          <input type="password" class="loginInput" name="loginPassword" tabindex="3" required><br>
          <div class="indexButtonHolder">
            <button class="loginButton"  name="loginButton" tabindex="4">Login</button><br>
          </div>
      </form>
      <form class="loginForm" id="registerForm"  method="POST">
        <p class="inputLabel">Name:</p>
        <input type="text" class="loginInput" name="registerUsername" tabindex="1" required>
        <p class="inputLabel">Email:</p>
        <input type="email" class="loginInput" name="registerEmail" tabindex="2" required>
        <p class="inputLabel">Password:</p>
        <input type="password" class="loginInput" name="registerPassword1" tabindex="3" required>
        <p class="inputLabel">Confirm Password:</p>
        <input type="password" class="loginInput" name="registerPassword2" tabindex="3" required><br>
        <div class="indexButtonHolder">
          <button class="loginButton" name="registerButton" tabindex="5">Go</button><br>
        </div>
      </form>
        <div class="indexButtonHolder">
          <button class="loginButton otherButton" id="regToggleButton" onClick="toggleRegistration()" tabindex="6">Register</button>
          <button class="loginButton otherButton" onClick="goToFeed()" tabindex="6">Skip>></button>
        </div>
        <?php
        if($_POST){
          echo $whatHappened;
        }
        ?>
      </form>
    </div>
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
