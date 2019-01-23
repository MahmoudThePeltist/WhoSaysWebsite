<?php
  function connectToDB(){
    //cridentials
    $dbName = "socialmediadb";
    $name = "Mahmoud";
    $password = "mahmoud1996";
    $connect =  new mysqli("localhost",$name,$password,$dbName);
    if ($connect->connect_error){
      echo "<h2>Database error: " . mysqli_connect_error() . "</h2>";
    }
    return $connect;
  }
?>
