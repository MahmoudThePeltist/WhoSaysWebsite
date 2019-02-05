<?php
class userClass {

  public $conn;
  public $loginFail;

  public function __construct($connToDB){
    $this->conn = $connToDB;
  }

  public function loginUser($loginUsername,$loginPassword){
    $passObject = $this->conn->query("SELECT `Password` FROM `usertable` WHERE `Username` = '$loginUsername'");
    $pass = $passObject->fetch_assoc();
    $hashedPassword = $pass['Password'];
    if(password_verify($loginPassword, $hashedPassword)){
      //set user id for this session and go to mainfeed
      $_SESSION['userID'] = $loginUsername;
      header('location: mainFeed.php');
      exit();
    } else {
      $this->loginFail = True;
      return 0;
    }
  }

  public function registerUser($registerUsername,$registerEmail,$registerPassword1,$registerPassword2){
    $defaultPicture = 'userImages/userDefault.png';
    $userCheckObject = $this->conn->query("SELECT `Username` FROM `usertable` WHERE `Username` = '$registerUsername'");
    $userCheck = $userCheckObject->fetch_assoc();
    if(!isset($userCheck)){
      if ($registerPassword1 == $registerPassword2){
        $hashedPassword = password_hash($registerPassword1, PASSWORD_DEFAULT);
        $this->conn->query("INSERT INTO `usertable`(`ID`, `Username`, `Email`, `Password`,`userImage`,`Premissions`) VALUES (NULL,'$registerUsername','$registerEmail','$hashedPassword','$defaultPicture','0')");
        //create default profile
        $registerIdObj = $this->conn->query("SELECT `ID` from `usertable` WHERE `username` = '$registerUsername'");
        $registerIdFetch = $registerIdObj->fetch_assoc();
        $registerId = $registerIdFetch['ID'];
        $this->conn->query("INSERT INTO `userprofiletable`(`userId`, `profileText`) VALUES ('$registerId','Welcome to my profile!')");
        //set user id for this session and go to mainfeed
        $_SESSION['firstLaunch'] = 1;
        $_SESSION['userID'] = $registerUsername;
        header('location: mainFeed.php');
        exit();
      } else {
        //Make sure both passwords are the same!
        return 0;
      }
    } else {
      //That username is taken!
      return 1;
    }
  }

  public function checkWithEmail($oldUserName, $oldEmail){
    //check user using the username/email combo
    $getObj = $this->conn->query("SELECT `Username` FROM `usertable` WHERE  `Email` = '$oldEmail'");
    $getFetch = $getObj->fetch_assoc();
    //check if a username exists for that email
    if(isset($getFetch['Username'])){
      if($getFetch['Username'] == $oldUserName){
        //That username/email combo exists
        return 1;
      } else {
        //That username does not match that Email.
        return 2;
      }
    } else {
      //That username/email combo does not exist.
      return 0;
    }
  }

  public function sendResetEmail($oldEmail){
    //get the current encoded password to include in the message
    $emailObj = $this->conn->query("SELECT `Password` FROM `usertable` WHERE `Email` = '$oldEmail'");
    $emailFetch = $emailObj->fetch_assoc();
    $password = $emailFetch['Password'];
    //message text
    $message = "Your email reset code: ".$password;
    //other email variables
    $subject = "WhoSays password reset";
    $headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    //Send email using php
    mail($oldEmail, $subject, $message, $headers);
    return 1;
  }

  public function logOut(){
    //log out
    session_start();
    $_SESSION['userID'] = NULL;
    header('location: index.php');
  }
}
?>
