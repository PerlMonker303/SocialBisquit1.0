<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $postId = htmlentities($_POST['_postId']);

    $sql_sel = "SELECT * FROM tbl_comments WHERE idPost=?";
    $stmt_sel = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_sel, $sql_sel);
    mysqli_stmt_bind_param($stmt_sel, "i", $postId);
    mysqli_stmt_execute($stmt_sel);
    $result_sel = mysqli_stmt_get_result($stmt_sel);
    $commCount = mysqli_num_rows($result_sel);

    //actual loading
    echo $commCount;
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
 ?>
