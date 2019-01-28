<?php
  include 'phpconnect.php';
  session_start();
  $whatHappened = "";
  //connect to DB
  $conn = connectToDB();

  if ($_POST){
    //Login and registration handling
    $userObj = new userClass($conn);
    if(isset($_POST["loginButton"])){
      $userObj->loginUser($_POST["loginUsername"],$_POST["loginPassword"]);
    } else if(isset($_POST["registerButton"])){
      $userObj->registerUser($_POST["registerUsername"], $_POST["registerEmail"], $_POST["registerPassword1"], $_POST["registerPassword2"]);
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
