<?php
require 'dbh.php';

$email = htmlentities($_POST['_loginEmail']);
$password = htmlentities($_POST['_loginPass']);

if(empty($email)||empty($password)){
  header("Location: ../login.php?error=emptyfields&loginEmail=".$email);
  exit();
}else{
  $sql = "SELECT * FROM tbl_users WHERE email=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: ../login.php?error=sqlerror");
    exit();
  }else{
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($result)){
      $pwdCheck = password_verify($password, $row['password']);
      if($pwdCheck == false){
        header("Location: ../login.php?error=l_wrongpassword");
        exit();
      }else if($pwdCheck == true){
        //checking if user is blocked
        if($row['is_blocked']==1){
          header("Location: ../login.php?error=l_blockeduser");
          exit();
        }else{
          session_start();
          $_SESSION['userId'] = $row['idUser'];
          $_SESSION['userEmail'] = $row['email'];
          $_SESSION['userFName'] = $row['fName'];
          $_SESSION['userLName'] = $row['lName'];
          $_SESSION['userDOReg'] = $row['dateOfReg'];

          //updating active status
          $sql1 = "UPDATE tbl_users SET is_active=? WHERE idUser=?";
          $stmt1 = mysqli_stmt_init($conn);
          if(!mysqli_stmt_prepare($stmt1, $sql1)){
            header("Location: ../login.php?error=sqlerror");
            exit();
          }else{
            $true = 1;
            mysqli_stmt_bind_param($stmt1, "ii", $true, $row['idUser']);
            mysqli_stmt_execute($stmt1);

            $_SESSION['userActive'] = $row['is_active'];

            header("Location: ../main.php?success");
            exit();
          }
        }
      }else{
        header("Location: ../login.php?error=l_mistake");
        exit();
      }
    }else{
      header("Location: ../login.php?error=l_nouser");
      exit();
    }
  }
}

