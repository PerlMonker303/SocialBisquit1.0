<?php
  require 'dbh.php';

  $postId = htmlentities($_POST['_postId']);

  //selecting data
  $sql_sel = "SELECT * FROM tbl_likes WHERE idPost=?";
  $stmt_sel = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt_sel, $sql_sel);
  mysqli_stmt_bind_param($stmt_sel, "i", $postId);
  mysqli_stmt_execute($stmt_sel);
  $result_sel = mysqli_stmt_get_result($stmt_sel);
  $likeCount = mysqli_num_rows($result_sel);

  //actual loading
  echo $likeCount;

 ?>
