<?php
  require 'dbh.php';

  $shareId = htmlentities($_POST['idOfShare']);

  $sql = "DELETE FROM tbl_shares WHERE idShare=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, 'i', $shareId);
  mysqli_stmt_execute($stmt);

 ?>
