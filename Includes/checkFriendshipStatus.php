<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $userId = $_SESSION['userId'];
    $friendId = htmlentities($_POST['_friendId']);
    $zero = 0;

    $lastId = 0;

    $sql_check = "SELECT * FROM tbl_contacts WHERE idUser = ? AND idFriend = ?";
    $stmt_check = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_check, $sql_check)){
        header("Location: ../main.php?error=sqlerror");
        exit();
    }else{
        $lid = $_SESSION['userId'];
        mysqli_stmt_bind_param($stmt_check, 'ii', $friendId , $lid);
        mysqli_stmt_execute($stmt_check);
        $res_check = mysqli_stmt_get_result($stmt_check);
        $row_check = mysqli_fetch_assoc($res_check);
        if($row_check['idUser']){
            echo '<span>Friend request accepted</span>';
        }else{
            echo '<span>Friend request sent</span>';
        }
    }

    
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
?>
