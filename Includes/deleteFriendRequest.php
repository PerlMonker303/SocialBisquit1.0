<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $idFreq = htmlentities($_POST['_freqId']);

    $sql_del = "DELETE FROM tbl_frequests WHERE idReq =?";
    $stmt_del = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_del, $sql_del)){

    }else{
      mysqli_stmt_bind_param($stmt_del, "i", $idFreq);
    mysqli_stmt_execute($stmt_del);
    }
    
    
  }

 ?>
