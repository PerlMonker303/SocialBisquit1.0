<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
    $postId = htmlentities($_POST['_sharePostId']);
    $shareContent = htmlentities($_POST['_shareContent']);

    //selecting share count
    $sql_sel = "SELECT shareCount FROM tbl_posts WHERE idPost=?";
    $stmt_sel = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_sel, $sql_sel);
    mysqli_stmt_bind_param($stmt_sel, "i", $postId);
    mysqli_stmt_execute($stmt_sel);
    $result_sel = mysqli_stmt_get_result($stmt_sel);
    $row_sel = mysqli_fetch_assoc($result_sel);
    $local_var = $row_sel['shareCount'];
    $local_var = $local_var+1;
    //adding user to tbl_shares
    $sql_add = "INSERT INTO tbl_shares(idPost,idUser,content,dateShared) VALUES (?, ?, ?, NOW())";
    $stmt_add = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_add, $sql_add);
    mysqli_stmt_bind_param($stmt_add, "iis", $postId, $userId, $shareContent);
    mysqli_stmt_execute($stmt_add);

    //checking who will see the notification
    $sql_check1 = "SELECT * FROM tbl_posts WHERE idPost = ?";
    $stmt_check1 = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_check1, $sql_check1);
    mysqli_stmt_bind_param($stmt_check1, 'i', $postId);
    mysqli_stmt_execute($stmt_check1);
    $result_check1 = mysqli_stmt_get_result($stmt_check1);
    $row_check1 = mysqli_fetch_assoc($result_check1);
    $userToSeeId = $row_check1['idUser'];

    //adding event to notification table
    $typeOf = "sharedPost";
    $content = " shared your <i name=\"_newsNotificationClickPost\" onclick=\"clickNewsNotificationPost($postId)\">post.</i>";
    $zero = 0;
    $sql_add1 = "INSERT INTO tbl_notifications(idUser, idPost, idUserToSee, typeOf, content, dateOf, isRead) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    $stmt_add1 = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_add1, $sql_add1);
    mysqli_stmt_bind_param($stmt_add1, "iiissi", $userId, $postId, $userToSeeId, $typeOf, $content, $zero);
    mysqli_stmt_execute($stmt_add1);
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
?>
