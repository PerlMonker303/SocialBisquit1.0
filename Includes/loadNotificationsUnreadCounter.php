<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $loggedUserId = $_SESSION['userId'];

    //checking unread news from user
    $sql_check = "SELECT * FROM tbl_notifications WHERE idUserToSee = ? AND isRead = 0";
    $stmt_check = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_check, $sql_check)){
      header("Location: ../main.php?error=sqlerror");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt_check, 'i', $loggedUserId);
      mysqli_stmt_execute($stmt_check);
      $result_check = mysqli_stmt_get_result($stmt_check);
      $unreadCounter = mysqli_num_rows($result_check);
    }

    //checking friend requests as well
    $sql_check1 = "SELECT * FROM tbl_frequests WHERE idFriend = ?";
    $stmt_check1 = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_check1, $sql_check1)){
      header("Location: ../main.php?error=sqlerror");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt_check1, 'i', $loggedUserId);
      mysqli_stmt_execute($stmt_check1);
      $result_check1 = mysqli_stmt_get_result($stmt_check1);
      $unreadCounter += mysqli_num_rows($result_check1);
    }
    if($unreadCounter != 0)
      echo '<span>'.$unreadCounter.'</span>';
    else{
      echo '0';
    }

  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }

 ?>
