<?php

  session_start();
  
  include 'dbh.php';

  if(isset($_SESSION['userId'])){
    $postId = 0;
    $userId = $_SESSION['userId'];
    $content = "";
    $parent = 0;

    $postId = htmlentities($_POST['_commentPostId']);
    $content = htmlentities($_POST['_commentContent']);
    $likeCount = 0;
    $parent = htmlentities($_POST['_commentParent']);

    if($content!=""){
      //adding comment into DB
      $sql = "INSERT INTO tbl_comments(idPost,idUser,datePosted,content,likeCount,parent) VALUES (?,?,NOW(),?,?,?)";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt,$sql);
      mysqli_stmt_bind_param($stmt, "iisii", $postId,$userId,$content,$likeCount,$parent);
      mysqli_stmt_execute($stmt);

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
      $typeOf = "commentedPost";
      $content = " commented on your <i name=\"_newsNotificationClickPost\" onclick=\"clickNewsNotificationPost($postId)\">post.</i>";
      $zero = 0;
      $sql_add1 = "INSERT INTO tbl_notifications(idUser, idPost, idUserToSee, typeOf, content, dateOf, isRead) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
      $stmt_add1 = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt_add1, $sql_add1);
      mysqli_stmt_bind_param($stmt_add1, "iiissi", $userId, $postId, $userToSeeId, $typeOf, $content, $zero);
      mysqli_stmt_execute($stmt_add1);

    }else{
      echo 'You can\'t add an empty comment.';
      }
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
?>
