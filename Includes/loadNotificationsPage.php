<?php

  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $id = $_SESSION['userId'];
    $zero = 0;
    $notZero = 1;
    $localId = 0;
    $localIdFrequest = 0;

    echo '<div id="container-notifications-page">
      <h2>Notifications</h2>
    ';

    //this script loads every notification this user has in #content area
    //before anything we load friend requests
    $sql_load_frequest = "SELECT * FROM tbl_frequests WHERE idFriend = '$id' ORDER BY dateSent DESC";
    $result_load_frequest = mysqli_query($conn, $sql_load_frequest);
    if($result_load_frequest){
      if(mysqli_num_rows($result_load_frequest) > 0){
        echo '<div id="container-notifications-page-frequest">
          <h3><img src="Icons/addPersonMat.svg"><i>Friend requests</i></h3>
        ';
        while($row_frequest = mysqli_fetch_assoc($result_load_frequest)){
          //selecting name
          $sql_sel_name_frequest = "SELECT fName, lName FROM tbl_users WHERE idUser = ?";
          $stmt_sel_name_frequest = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt_sel_name_frequest, $sql_sel_name_frequest);
          mysqli_stmt_bind_param($stmt_sel_name_frequest, 'i', $row_frequest['idUser']);
          mysqli_stmt_execute($stmt_sel_name_frequest);
          $result_sel_name_frequest = mysqli_stmt_get_result($stmt_sel_name_frequest);
          $row_sel_name_frequest = mysqli_fetch_assoc($result_sel_name_frequest);
          $fullName = $row_sel_name_frequest['fName'].' '.$row_sel_name_frequest['lName'];
          //selecting image
          $sql_sel_img_frequest = "SELECT nameProfilePic FROM tbl_information WHERE idUser = ?";
          $stmt_sel_img_frequest = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt_sel_img_frequest, $sql_sel_img_frequest);
          mysqli_stmt_bind_param($stmt_sel_img_frequest, 'i', $row_frequest['idUser']);
          mysqli_stmt_execute($stmt_sel_img_frequest);
          $result_sel_img_frequest = mysqli_stmt_get_result($stmt_sel_img_frequest);
          $row_sel_img_frequest = mysqli_fetch_assoc($result_sel_img_frequest);
          $imagePath = $row_sel_img_frequest['nameProfilePic'];

          echo '<div class="container-notifications-page-frame-freq">
            <span><img src="'.$imagePath.'" name="_newsNotificationClickable" onclick="openAProfile('.$row_frequest['idUser'].')"></img><i name="_newsNotificationClickable" onclick="openAProfile('.$row_frequest['idUser'].')">'.$fullName.'</i> sent you a friend request.<span name="_newsNotificationClickable" style="display: none;">'.$row_frequest['idUser'].'</span></span>
            <button class="formButtonNews" onclick="acceptFriendRequest('.$row_frequest['idReq'].')">Accept</button>
            <button class="formButtonNews" onclick="rejectFriendRequest('.$row_frequest['idReq'].')">Reject</button>
          </div>';
          $localIdFrequest++;
        }
        echo '<hr>
        </div>';
      }
    }else{
      header("Location: ../main.php?error=sqlerror'.$lastId1.'-".mysqli_error($conn));
      exit();
    }


    echo '<h4 name="_successFriendRequestFromNotifications" style="display: none;">Friend request accepted succesfully.</h4>';


    //first we load the unread Notifications
    $sql_load_unread = "SELECT * FROM tbl_notifications WHERE idUserToSee = '$id' AND isRead = '$zero' ORDER BY dateOf DESC";
    $result_load_unread = mysqli_query($conn, $sql_load_unread);
    if($result_load_unread){
      if(mysqli_num_rows($result_load_unread) > 0){

        echo '
          <div id="container-notifications-page-unread">

          <h3><img src="Icons/mailUnreadMat.svg"><i>Unread</i></h3>
        ';
        while($row_unread = mysqli_fetch_assoc($result_load_unread)){
          //selecting name
          $sql_sel_name_unread = "SELECT fName, lName FROM tbl_users WHERE idUser = ?";
          $stmt_sel_name_unread = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt_sel_name_unread, $sql_sel_name_unread);
          mysqli_stmt_bind_param($stmt_sel_name_unread, 'i', $row_unread['idUser']);
          mysqli_stmt_execute($stmt_sel_name_unread);
          $result_sel_name_unread = mysqli_stmt_get_result($stmt_sel_name_unread);
          $row_sel_name_unread = mysqli_fetch_assoc($result_sel_name_unread);
          $fullName = $row_sel_name_unread['fName'].' '.$row_sel_name_unread['lName'];
          //selecting image
          $sql_sel_img_unread = "SELECT nameProfilePic FROM tbl_information WHERE idUser = ?";
          $stmt_sel_img_unread = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt_sel_img_unread, $sql_sel_img_unread);
          mysqli_stmt_bind_param($stmt_sel_img_unread, 'i', $row_unread['idUser']);
          mysqli_stmt_execute($stmt_sel_img_unread);
          $result_sel_img_unread = mysqli_stmt_get_result($stmt_sel_img_unread);
          $row_sel_img_unread = mysqli_fetch_assoc($result_sel_img_unread);
          $imagePath = $row_sel_img_unread['nameProfilePic'];

          //calculating date
          $now = time(); // or your date as well
          $your_date = strtotime($row_unread['dateOf']);
          $datediff = $now - $your_date;

          $type = 0;

          if($datediff/86400<1){
            //less than a day
            if($datediff/3600<1){
              //less than an hour
              if($datediff/60<1){
                //less than a minute
                $type = 4;
              }else{
                $datediff/=60;
                $type = 3; //minutes ago
              }
            }else{
              $datediff/=3600;
              $type = 2; //hours ago
            }
          }else{
            $datediff/=86400;
            $type = 1; //days ago
          }

          echo '
            <div class="container-notifications-page-frame">
              <span>
                <img src="'.$imagePath.'" name="notifUserImage" class="_newsNotificationClickable" onclick="openAProfile('.$row_unread['idUser'].')"></img>
                <i class="_newsNotificationClickable" onclick="openAProfile('.$row_unread['idUser'].')">'.$fullName.'</i>'.$row_unread['content'].'
                <span name="_notifDateOf">'; 
                if($type==1){
                  if(intval($datediff)==1)
                    echo intval($datediff).' day ago';
                  else
                    echo intval($datediff).' days ago';
                }else if($type==2){
                  if(intval($datediff)==1)
                    echo intval($datediff).' hour ago';
                  else
                    echo intval($datediff).' hours ago';
                }else if($type==3){
                  if(intval($datediff)==1)
                    echo intval($datediff).' minute ago';
                  else
                    echo intval($datediff).' minutes ago';
                }else if($type==4){
                  echo ' less then a minute ago';
                }
                echo '</span>
                <span name="_newsNotificationClickable" style="display: none;">'.$row_unread['idUser'].'</span>
                <img src="Icons/hammerMatWhite.png" name="_notificationsOptionsButton" onclick="deleteNotification('.$row_unread['idNotification'].')"></img>
                <img src="Icons/bookMatWhite.png" name="_notificationsReadButton" onclick="markNotificationAsRead('.$row_unread['idNotification'].')"></img>
              </span>
            </div>
          ';
          $localId++;
        }
        echo '<hr></div>';
      }
    }else{
      header("Location: ../main.php?error=sqlerror'.$lastId1.'-".mysqli_error($conn));
      exit();
    }

    //then we load the read Notifications
    $sql_load = "SELECT * FROM tbl_notifications WHERE idUserToSee = '$id' AND isRead = '$notZero' ORDER BY dateOf DESC ";
    $result_load = mysqli_query($conn, $sql_load);
    if($result_load){

      if(mysqli_num_rows($result_load) > 0){
        echo '<div id="container-notifications-page-read">
        <h3><img src="Icons/mailOpenMat.svg"><i>Read</i></h3>';
        while($row = mysqli_fetch_assoc($result_load)){
          //selecting name
          $sql_sel_name = "SELECT fName, lName FROM tbl_users WHERE idUser = ?";
          $stmt_sel_name = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt_sel_name, $sql_sel_name);
          mysqli_stmt_bind_param($stmt_sel_name, 'i', $row['idUser']);
          mysqli_stmt_execute($stmt_sel_name);
          $result_sel_name = mysqli_stmt_get_result($stmt_sel_name);
          $row_sel_name = mysqli_fetch_assoc($result_sel_name);
          $fullName = $row_sel_name['fName'].' '.$row_sel_name['lName'];
          //selecting image
          $sql_sel_img = "SELECT nameProfilePic FROM tbl_information WHERE idUser = ?";
          $stmt_sel_img = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt_sel_img, $sql_sel_img);
          mysqli_stmt_bind_param($stmt_sel_img, 'i', $row['idUser']);
          mysqli_stmt_execute($stmt_sel_img);
          $result_sel_img = mysqli_stmt_get_result($stmt_sel_img);
          $row_sel_img = mysqli_fetch_assoc($result_sel_img);
          $imagePath = $row_sel_img['nameProfilePic'];

          //calculating date
          $now = time(); // or your date as well
          $your_date = strtotime($row['dateOf']);
          $datediff = $now - $your_date;

          $type = 0;

          if($datediff/86400<1){
            //less than a day
            if($datediff/3600<1){
              //less than an hour
              if($datediff/60<1){
                //less than a minute
                $type = 4;
              }else{
                $datediff/=60;
                $type = 3; //minutes ago
              }
            }else{
              $datediff/=3600;
              $type = 2; //hours ago
            }
          }else{
            $datediff/=86400;
            $type = 1; //days ago
          }

          echo '
            <div class="container-notifications-page-frame">
              <span>
                <img src="'.$imagePath.'" name="notifUserImage" class="_newsNotificationClickable" onclick="openAProfile('.$row['idUser'].')"></img>
                <i class="_newsNotificationClickable" onclick="openAProfile('.$row['idUser'].')">'.$fullName.'</i>'.$row['content'].'
                <span name="_notifDateOf">';
                if($type==1){
                  if(intval($datediff)==1)
                    echo intval($datediff).' day ago';
                  else
                    echo intval($datediff).' days ago';
                }else if($type==2){
                  if(intval($datediff)==1)
                    echo intval($datediff).' hour ago';
                  else
                    echo intval($datediff).' hours ago';
                }else if($type==3){
                  if(intval($datediff)==1)
                    echo intval($datediff).' minute ago';
                  else
                    echo intval($datediff).' minutes ago';
                }else if($type==4){
                  echo ' less then a minute ago';
                }
                echo '</span>
                <span name="_newsNotificationClickable" style="display: none;">'.$row['idUser'].'</span>
                <img src="Icons/hammerMatWhite.png" name="_notificationsOptionsButton" onclick="deleteNotification('.$row['idNotification'].')"></img>
              </span>
            </div>
          ';
          $localId++;
        }
        echo '</div>';
      }else if($localId == 0){
        echo '<p>You have 0 notifications</p>';
      }




    }else{
      header("Location: ../main.php?error=sqlerror'.$lastId1.'-".mysqli_error($conn));
      exit();
    }
    echo '</div>';
    echo '<div id="overlay">
    </div>';
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
 ?>
