<?php

session_start();

  if(isset($_SESSION['userId'])){

    require 'dbh.php';

    session_start();
    $currentId = htmlentities($_SESSION['userId']);
    $postTitle = "new_website";
    $postContent = htmlentities($_POST['_textName']);
    $observations = "none";

    $sql = "INSERT INTO tbl_posts(idUser,title,datePosted,content,observations) VALUES (?,?,NOW(),?,?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location: ../index.php?error=sqlerror");
        exit();
    }else{
      mysqli_stmt_bind_param($stmt, "isss", $currentId,$postTitle,$postContent,$observations);
      mysqli_stmt_execute($stmt);
    }

    header("Location: ../index.php?error=post_success");
    exit();
  }
  else{
    header("Location: ../index.php?error=sqlerror");
    exit();
  }
 ?>
