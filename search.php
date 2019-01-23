<?php
  include 'phpconnect.php';

  //connect to DB
  $conn = connectToDB();

  //Getting value of "search" variable from "script.js".
  if (isset($_POST['search'])) {
     $Name = $_POST['search'];

     $ExecQuery = $conn->query("SELECT Username FROM usertable where Username LIKE '%$Name%' LIMIT 5");
     //Creating unordered list to display result.
     echo '<ul class="searchList">';
     //Fetching result from database.
     while ($Result = $ExecQuery->fetch_array()) {
       //Creating unordered list items that connect to js function to fill the search bar.
       echo '
       <li class="searchListItem" onclick="fill(\''.$Result['Username'].'\')">
        <a >'.$Result['Username'].'</a>
       </li>';
    }
  }
  echo '</ul>';
?>
