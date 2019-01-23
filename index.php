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
  include "includes/index.html";
  //Closing the DB
  $conn->close();
?>
