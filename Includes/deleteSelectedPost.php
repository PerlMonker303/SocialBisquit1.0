<?php
  require 'dbh.php';

  $postId = htmlentities($_POST['idOfPost']);

  $sql = "DELETE FROM tbl_posts WHERE idPost=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, 'i', $postId);
  mysqli_stmt_execute($stmt);

  //delete likes as well
  $sql1 = "DELETE FROM tbl_likes WHERE idPost=?";
  $stmt1 = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt1, $sql1);
  mysqli_stmt_bind_param($stmt1, 'i', $postId);
  mysqli_stmt_execute($stmt1);

 ?>
