<?php
  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $loggedUser = false;
    if(htmlentities($_POST['_userId']) == 0 || htmlentities($_POST['_userId']) == $_SESSION['userId']){
      $loggedUser = true;  
      $userId = $_SESSION['userId'];
    }else{
      $loggedUser = false;
      $userId = htmlentities($_POST['_userId']);
    }

    echo 'Coming soon.';
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
 ?>
