<?php

session_start();
  
if(isset($_SESSION['userId'])){

  require 'dbh.php';

  $postId = htmlentities($_POST['_postId']);
  $userId = $_SESSION['userId'];

  //then we check if post is not already liked
  $sql_check = "SELECT * FROM tbl_likes WHERE idUser=? AND idPost=?";
  $stmt_check = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt_check, $sql_check);
  mysqli_stmt_bind_param($stmt_check, "ii", $userId, $postId);
  mysqli_stmt_execute($stmt_check);
  mysqli_stmt_store_result($stmt_check);
  if(mysqli_stmt_num_rows($stmt_check)>0){
    //deleting user from tbl_likes
    $sql_del = "DELETE FROM tbl_likes WHERE idUser=? AND idPost=?";
    $stmt_del = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_del, $sql_del);
    mysqli_stmt_bind_param($stmt_del, "ii", $userId, $postId);
    mysqli_stmt_execute($stmt_del);

    //delete event from notification
    $sql_del1 = "DELETE FROM tbl_notifications WHERE idUser = '$userId' AND idPost = '$postId'";
    mysqli_query($conn, $sql_del1);

  }else{
    //adding user to tbl_likes
    $sql_add = "INSERT INTO tbl_likes(idPost,idUser) VALUES (?, ?)";
    $stmt_add = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_add, $sql_add);
    mysqli_stmt_bind_param($stmt_add, "ii", $postId, $userId);
    mysqli_stmt_execute($stmt_add);

    //checking who will see the notification
    $sql_check1 = "SELECT * FROM tbl_posts WHERE idPost = ?";
    $stmt_check1 = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_check1, $sql_check1)){
      header("Location: ../index.php?error=sqlerror");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt_check1, 'i', $postId);
      mysqli_stmt_execute($stmt_check1);
      $result_check1 = mysqli_stmt_get_result($stmt_check1);
      $row_check1 = mysqli_fetch_assoc($result_check1);
      $userToSeeId = $row_check1['idUser'];
    }

    //adding event to notification table
    $typeOf = "likedPost";
    $content = " liked your <i name=\"_newsNotificationClickPost\" onclick=\"clickNewsNotificationPost($postId)\">post.</i>";
    $zero = 0;
    $sql_add1 = "INSERT INTO tbl_notifications(idUser, idPost, idUserToSee, typeOf, content, dateOf, isRead) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    $stmt_add1 = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_add1, $sql_add1)){
      header("Location: ../index.php?error=sqlerror1");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt_add1, "iiissi", $userId, $postId, $userToSeeId, $typeOf, $content, $zero);
      mysqli_stmt_execute($stmt_add1);
    }
  }

}

?>
