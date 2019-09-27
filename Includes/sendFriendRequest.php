<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $userId = $_SESSION['userId'];
    $friendId = htmlentities($_POST['_friendId']);
    $zero = 0;

    $lastId = 0;

    //select the last id
    $sql_sel = "SELECT idReq FROM tbl_frequests ORDER BY idReq DESC LIMIT 1";
    $stmt_sel = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_sel, $sql_sel)){
      header("Location: ../main.php?error=sqlerror");
      exit();
    }else{
      mysqli_stmt_execute($stmt_sel);
      $res = mysqli_stmt_get_result($stmt_sel);
      $row = mysqli_fetch_assoc($res);
      $lastId = $row['idReq'];
    }

    $lastId++;

    //check if friend already sent a frequest - accept it if yes, send one if no

    $sql_check = "SELECT * FROM tbl_frequests WHERE idUser = ? AND idFriend = ?";
    $stmt_check = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_check, $sql_check)){
      header("Location: ../main.php?error=sqlerror");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt_check, 'ii', $friendId, $userId);
      mysqli_stmt_execute($stmt_check);
      $result_check = mysqli_stmt_get_result($stmt_check);
      $row_check = mysqli_fetch_assoc($result_check);
      if($row_check['idUser']){
        //friend already sent a frequest, accept friendship


        $lastId = 0;
        //select last id and increment it
        $sql_last = "SELECT idContact FROM tbl_contacts";
        $result_last = mysqli_query($conn, $sql_last);
        if(mysqli_num_rows($result_last) > 0){
          while($row = mysqli_fetch_assoc($result_last)){
            $lastId = $row['idContact'];
          }
        }
        $lastId++;
        //user adds friend
        $sql_adding = "INSERT INTO tbl_contacts (idContact, idUser, idFriend, since, is_ignored, is_blocked) VALUES ('$lastId','$userId','$friendId',NOW(),'$zero','$zero')";
        if(mysqli_query($conn,$sql_adding)){
          //adding inverted
          $lastId++;
          $sql_inv = "INSERT INTO tbl_contacts (idContact, idUser, idFriend, since, is_ignored, is_blocked) VALUES ('$lastId','$friendId','$userId',NOW(),'$zero','$zero')";
          if(mysqli_query($conn,$sql_inv)){
            //deleting frequest

            $sql_del = "DELETE FROM tbl_frequests WHERE idUser = ? AND idFriend = ?";
            $stmt_del = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt_del, $sql_del)){
              header("Location: ../main.php?error=sqlerror".mysqli_error($conn));
              exit();
            }else{
              mysqli_stmt_bind_param($stmt_del, "ii", $friendId, $userId);
              mysqli_stmt_execute($stmt_del);
              mysqli_stmt_close($stmt_del);

              //add new notification

              //first select the last ID from db notifications
              $lastId1 = 0;
              $sql_sel1 = "SELECT idNotification FROM tbl_notifications";
              $result_last1 = mysqli_query($conn, $sql_sel1);
              if(mysqli_num_rows($result_last1) > 0){
                while($row = mysqli_fetch_assoc($result_last1)){
                  $lastId1 = $row['idNotification'];
                }
              }

              $lastId1++;
              $zero = 0;
              $typeOf = 'acceptedFrequest';
              $content = ' accepted your friend request';
              $sql_notif = "INSERT INTO tbl_notifications (idNotification, idUser, idPost, idUserToSee, typeOf, content, dateOf, isRead) VALUES ('$lastId1','$userId','$zero','$friendId','$typeOf',' $content',NOW(),'$zero')";
              if(mysqli_query($conn,$sql_notif)){
                echo '<h3>Accept request</h3>';
              }else{
                header("Location: ../main.php?error=sqlerror".mysqli_error($conn));
                exit();
              }
            }

        }else{
          header("Location: ../main.php?error=sqlerror".mysqli_error($conn));
          exit();
        }

      }else{
        header("Location: ../main.php?error=sqlerror".mysqli_error($conn));
        exit();
      }
        
      }else{
        //send frequest
        $sql_send = "INSERT INTO tbl_frequests(idReq, idUser, idFriend, dateSent) VALUES(?,?,?,NOW())";
        $stmt_send = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt_send, $sql_send)){
          header("Location: ../main.php?error=sqlerror");
          exit();
        }else{
          mysqli_stmt_bind_param($stmt_send, "iii", $lastId, $userId, $friendId);
          mysqli_stmt_execute($stmt_send);

        }
      }
    }

    
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
?>
