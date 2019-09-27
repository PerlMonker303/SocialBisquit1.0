<?php
  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){
    
    $idUser = $_SESSION['userId'];
    $file_url = htmlentities($_POST['_url']);

    $sql = "UPDATE tbl_information SET nameProfilePic = ? WHERE idUser = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt, "si", $file_url, $idUser);
      mysqli_stmt_execute($stmt);
    }

    $oldUrl = $file_url;
    $typeOf = 'profile';
    //adding old img url to tbl_profilepics
    $sql_add = "INSERT INTO tbl_pictures (idUser, typeOf, imgPath, addedOn) VALUES (?,?,?,NOW())";
    $stmt_add = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_add, $sql_add)){
      header("Location: ../index.php?error=sqlerror");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt_add, 'iss', $idUser, $typeOf, $oldUrl);
      mysqli_stmt_execute($stmt_add);
    }


  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }

?>
