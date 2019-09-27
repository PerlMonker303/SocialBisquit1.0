<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $idNotif = htmlentities($_POST['_idNotif']);

    //delete the notification
    $sql_del = "DELETE FROM tbl_notifications WHERE idNotification = '$idNotif'";
    if(!mysqli_query($conn, $sql_del)){
      header("Location: ../main.php?error=sqlerror".mysqli_error($conn));
      exit();
    }
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }

 ?>
