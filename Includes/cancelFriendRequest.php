<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $userId = $_SESSION['userId'];
    $friendId = htmlentities($_POST['_friendId']);


    $sql_del = "DELETE FROM tbl_frequests WHERE idUser = ? && idFriend = ?";
    $stmt_del = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_del, $sql_del)){
      header("Location: ../main.php?error=sqlerror");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt_del, "ii", $userId, $friendId);
      mysqli_stmt_execute($stmt_del);

    }
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
?>
