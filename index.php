<?php
  //php functions
  include 'phpconnect.php';
  //user settings class
  include 'includes\php\userClass.php';
  session_start();
  //connect to DB
  $conn = connectToDB();
  $userObj = new userClass($conn);
  $whatHappened = "";

  if ($_POST){
    //Login handling
    if(isset($_POST["loginButton"])){
      $loginResult = $userObj->loginUser($_POST["loginUsername"],$_POST["loginPassword"]);
      if($loginResult == 0){
        $whatHappened = "<b class='inputLabel'>Username or password are wrong!</b>";
      }
    }
    //registration handling
    else if(isset($_POST["registerButton"])){
      $regResult = $userObj->registerUser($_POST["registerUsername"], $_POST["registerEmail"], $_POST["registerPassword1"], $_POST["registerPassword2"]);
      if($regResult == 0){
        $whatHappened = "<b class='inputLabel'>Make sure both passwords are the same!</b>";
      } else if($regResult == 1){
        $whatHappened = "<b class='inputLabel'>That username is taken!</b>";
      }
    }
    //password reset handling
    else if(isset($_POST['sendEmailBtn'])){
      $testResult = $userObj->checkWithEmail($_POST['oldUserName'],$_POST['oldEmail']);
      if($testResult == 0){
        $whatHappened = "<b class='inputLabel'>That username/email combo does not exist.</b>";
      }else if($testResult == 1){
        $whatHappened = "<b class='inputLabel'>Vertification Email Sent</b>";
        $emailResult = $userObj->sendResetEmail($_POST['oldEmail']);
      }else if($testResult == 2){
        $whatHappened = "<b class='inputLabel'>That username does not match that Email.</b>";
      }
    }
  }

  //theme variables:
  if(!isset($_SESSION['primaryTheme'])){
    $_SESSION['primaryTheme'] = 0;
  } if (!isset($_SESSION['secondaryTheme'])){
    $_SESSION['secondaryTheme'] = 0;
  }

  //include page html
  include "includes/index.html";
  //Closing the DB
  $conn->close();
?>
