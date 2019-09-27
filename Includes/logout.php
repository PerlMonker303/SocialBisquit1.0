<?php

require 'dbh.php';

session_start();

$sql = "UPDATE tbl_users SET is_active=? WHERE idUser=?";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)){
  header("Location: ../index.php?error=sqlerror");
  exit();
}else{
  $false = 0;
  $userId = htmlentities($_SESSION['userId']);
  mysqli_stmt_bind_param($stmt, "ii", $false, $userId);
  mysqli_stmt_execute($stmt);

  session_unset();
  session_destroy();
  header("Location: ../index.php?error=lo_success");
}
