<?php
  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $loggedUser = false;
    if(htmlentities($_POST['_userId']) == 0 || htmlentities($_POST['_userId']) == $_SESSION['userId']){
      $loggedUser = true;  
      $userId = $_SESSION['userId'];
    }else{
      $loggedUser = false;
      $userId = htmlentities($_POST['_userId']);
    }

  
  //selecting all shares for current user
  $sql0 = "SELECT * FROM tbl_shares WHERE idUser = ?";
  $stmt0 = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt0, $sql0);
  mysqli_stmt_bind_param($stmt0, 'i', $userId);
  mysqli_stmt_execute($stmt0);
  $result0 = mysqli_stmt_get_result($stmt0);
  if(mysqli_num_rows($result0) > 0){
    //echo '<h3 id="profile-special-posts">Your shares:</h3>';
    //showing shares
    $localId = 1;
    while($row0 = mysqli_fetch_assoc($result0)){
      $sql1 = "SELECT * FROM tbl_posts WHERE idPost=?";
      $stmt1 = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt1,$sql1);
      mysqli_stmt_bind_param($stmt1, "i", $row0['idPost']);
      mysqli_stmt_execute($stmt1);
      $result1 = mysqli_stmt_get_result($stmt1);
      $row1 = mysqli_fetch_assoc($result1);

      //getting user info
      $sql2 = "SELECT * FROM tbl_users WHERE idUser=?";
      $stmt2 = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt2,$sql2);
      mysqli_stmt_bind_param($stmt2, "i", $userId);
      mysqli_stmt_execute($stmt2);
      $result2 = mysqli_stmt_get_result($stmt2);
      $row2 = mysqli_fetch_assoc($result2);

      $fullText = $row1['content'];
      //check whats happening here
      $postId = $row1['idPost'];
      if(strlen($row1['content'])>250){
        $row1['content'] = substr($row1['content'],0,250)."...";
      }

      $userName = $row2['fName'].' '.$row2['lName'];

      //selecting profile pic for post user from tbl_information
      $sql_img = "SELECT nameProfilePic FROM tbl_information WHERE idUser=?";
      $stmt_img = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt_img, $sql_img);
      mysqli_stmt_bind_param($stmt_img, "i", $userId);
      mysqli_stmt_execute($stmt_img);
      $result_img = mysqli_stmt_get_result($stmt_img);
      $row_img = mysqli_fetch_assoc($result_img);
      $postImage = $row_img['nameProfilePic'];
      if(!$postImage)
        $postImage = 'https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg';


     //selecting likes,comments,shares counts
     $sql3 = "SELECT * FROM tbl_likes WHERE idPost = ?";
     $stmt3 = mysqli_stmt_init($conn);
     if(!mysqli_stmt_prepare($stmt3, $sql3)){
       header("Location: ../main.php?error=sqlerror");
       exit();
     }
     mysqli_stmt_bind_param($stmt3, "i", $postId);
     mysqli_stmt_execute($stmt3);
     mysqli_stmt_store_result($stmt3);
     $likeCount = mysqli_stmt_num_rows($stmt3);

     $sql4 = "SELECT * FROM tbl_comments WHERE idPost = ?";
     $stmt4 = mysqli_stmt_init($conn);
     if(!mysqli_stmt_prepare($stmt4, $sql4)){
       header("Location: ../main.php?error=sqlerror");
       exit();
     }
     mysqli_stmt_bind_param($stmt4, "i", $postId);
     mysqli_stmt_execute($stmt4);
     mysqli_stmt_store_result($stmt4);
     $commentCount = mysqli_stmt_num_rows($stmt4);

     $sql5 = "SELECT * FROM tbl_shares WHERE idPost = ?";
     $stmt5 = mysqli_stmt_init($conn);
     if(!mysqli_stmt_prepare($stmt5, $sql5)){
       header("Location: ../main.php?error=sqlerror");
       exit();
     }
     mysqli_stmt_bind_param($stmt5, "i", $postId);
     mysqli_stmt_execute($stmt5);
     mysqli_stmt_store_result($stmt5);
     $shareCount = mysqli_stmt_num_rows($stmt5);

     //check if post has been liked by this user
     $likePath = "Icons/likeMatBlack.png";
     $sql6 = "SELECT * FROM tbl_likes WHERE idPost = ? AND idUser = ?";
     $stmt6 = mysqli_stmt_init($conn);
     if(!mysqli_stmt_prepare($stmt6, $sql6)){
       header("Location: ../main.php?error=sqlerror");
       exit();
     }
     mysqli_stmt_bind_param($stmt6, "ii", $postId, $userId);
     mysqli_stmt_execute($stmt6);
     mysqli_stmt_store_result($stmt6);
     if(mysqli_stmt_num_rows($stmt6)>0){
       //liked
       $likePath = "Icons/dislikeMatWhite.png";
     }
      //calculating date
    $now = time(); // or your date as well
    $your_date = strtotime($row0['dateShared']);
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

      //adding the shares
      $newName = "_profileShare_".$localId;
      //add share content

      echo '
      <div class="container-main-frame-outer">
        <div class="container-main-frame-shares" name="'.$newName.'">
            <div class="container-header-close-button" onclick="shrinkPost(\''.$newName.'\')">
              <button><img src="Icons/closeMatWhite.png"></button>
            </div>
          <div class="container-share-frame">
            <h3><img src="Icons/shareMatWhite.png"></img> '.$row0['content'].'</h3>
          </div>
          <div class="container-header-content">';
          if($loggedUser == true){
            echo '
            <img src="Icons/hammerMatWhite.png" class="post-option-button" onclick="togglePostOptionMenu('.($localId-1).')"></img>
            <span style="display: none;" class="post-option-text" onclick="deletePostOption('.($localId-1).','.$row0['idShare'].')">Delete post</span>';
          }
            echo '<h3><img src="'.$postImage.'"></img> <span>'.$userName.'</span>posted <span>';
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
            <span style="display: none;">'.$postImage.'</span>
            </h3>
            </div>';
        $localId++;
        echo '
          <div class="container-text-content"">
            <p>'.$row1['content'].'</p>
            <p style="display: none;">'.$fullText.'</p>
            <p style="display: none;">'.$row1['idPost'].'</p>
          </div>
        ';
        echo '
          <div class="container-text-readmore">
            <p onclick="expandShare(\''.$newName.'\')">Read More...</p>
          </div>


          <div class="container-reaction-bar">
          <button class="main-buttons" onclick="likePostFunction('.$postId.')"><img src="'.$likePath.'" class="_icon_like"></button>
          <button class="main-buttons" onclick="toggleCommentTool('.$postId.')"><img src="Icons/commentMat.png" class="_icon_comment"></button>
          <button class="main-buttons" onclick="toggleShareTool('.$postId.')"><img src="Icons/shareMat.svg" class="_icon_share"></button>
          <div class="container-info-bar">
            <div class="container-info-likes">
              <span class="_likeCount">'.$likeCount.'</span>
            </div>
            <div class="container-info-comments">
              <span class="_commentCount">'.$commentCount.'</span>
            </div>
            <div class="container-info-shares">
              <span class="_shareCount">'.$shareCount.'</span>
            </div>
          </div>
          <div class="container-add-comment">
          <div class="makeCommentUserImgDiv">
            <img src="https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg"> 
          </div>
          <textarea class="makeCommentTextarea" placeholder="Write your comment..." maxlength="500"></textarea>
          <span class="makeCommentCharacterCounter"><span>0</span><br>/500</span>
          <button class="makeCommentSendButton main-buttons"><img src="Icons/createMatWhite.png"></button>
        </div>
        <div class="container-add-share">
          <div class="makeShareUserImgDiv">
            <img src="https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg"> 
          </div>
          <textarea class="makeShareTextarea" placeholder="Add a message to your share..." maxlength="500"></textarea>
          <span class="makeShareCharacterCounter"><span>0</span><br>/500</span>
          <button class="makeShareSendButton main-buttons"><img src="Icons/createMatWhite.png"></button>
        </div>

        <div class="container-full-comment-section">
          <span name="secret-post-id">'.$postId.'</span>
        </div>
        </div>

        
          
        </div>
      </div>';
    }
  }else{
    echo '<span>There are no shares made yet.</span>';
  }
}else{
  header("Location: ../main.php?error=not_set");
    exit();
}

  ?>
