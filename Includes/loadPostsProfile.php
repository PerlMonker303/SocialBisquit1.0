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

    //header("Location: ../main.php?asd=".$loggedUser);

    $sql0 = "SELECT * FROM tbl_posts WHERE idUser = ? ORDER BY datePosted DESC";
    $stmt0 = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt0, $sql0);
    mysqli_stmt_bind_param($stmt0, 'i', $userId);

    mysqli_stmt_execute($stmt0);
    mysqli_stmt_store_result($stmt0);
    $resultCheck = mysqli_stmt_num_rows($stmt0);
    if($resultCheck > 0){
      //showing posts
        $sql = "SELECT * FROM tbl_posts WHERE idUser=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)){
          header("Location: ../index.php?error=sqlerror");
          exit();
        }else{
          mysqli_stmt_bind_param($stmt, "i", $userId);
          mysqli_stmt_execute($stmt);
          //post local id
          $localId = 1;
          $result = mysqli_stmt_get_result($stmt);
          while($row = mysqli_fetch_assoc($result)){
            //selecting userName
            $sql1 = "SELECT fName,lName FROM tbl_users WHERE idUser=?";
            $stmt1 = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt1,$sql1)){
              exit();
            }
            mysqli_stmt_bind_param($stmt1, "i", $userId);
            mysqli_stmt_execute($stmt1);
            $result1 = mysqli_stmt_get_result($stmt1);
            if($row1 = mysqli_fetch_assoc($result1)){
              $userName = $row1['fName'].' '.$row1['lName'];
            }

            //shortening content if it is too long
            $fullText = $row['content'];
            $postId = $row['idPost'];
            if(strlen($row['content'])>250){
              $row['content'] = substr($row['content'],0,250)."...";
            }

            //selecting profile pic for post user from tbl_information
            $sql2 = "SELECT nameProfilePic FROM tbl_information WHERE idUser=?";
            $stmt2 = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt2, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $row['idUser']);
            mysqli_stmt_execute($stmt2);
            $result2 = mysqli_stmt_get_result($stmt2);
            $row2 = mysqli_fetch_assoc($result2);
            $postImage = $row2['nameProfilePic'];
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
      $your_date = strtotime($row['datePosted']);
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

            //adding the posts
            $newName = "profl_".$localId;
            echo
            '<div class="container-main-frame-outer">
            <div class="container-main-frame" name="'.$newName.'">
              <div class="container-header-close-button" onclick="shrinkPost(\''.$newName.'\')">
                <button><img src="Icons/closeMatWhite.png"></button>
              </div>
              <div class="container-header-content">
            ';
              if($loggedUser == true){
                echo '
                <img src="Icons/hammerMatWhite.png" class="post-option-button" onclick="togglePostOptionMenu('.($localId-1).')"></img>
                <span style="display: none;" class="post-option-text" onclick="deletePostOption('.($localId-1).','.$row['idPost'].')">Delete post</span>';
              }
                
            echo '
                </br>
                <h3><img src="'.$postImage.'"></img> <span>'.$userName.'</span> <span name="_postAddedDiff">posted '; 
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
                echo'</span>
                  <span style="display: none;">'.$postImage.'</span>
                </h3>


                </div>';
            $localId++;
            echo '
              <div class="container-text-content"">
                <p>'.$row['content'].'</p>
                <p style="display: none;">'.$fullText.'</p>
                <p style="display: none;">'.$postId.'</p>
              </div>
            ';
            echo '
              <div class="container-text-readmore">
                <p onclick="expandPost(\''.$newName.'\')">Read More...</p>
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
            ';
          }
        }
    }else{
      echo '<span>There are no posts made yet.</span>';
    }
}else{
    header("Location: ../main.php?error=not_set");
    exit();
}

  ?>
