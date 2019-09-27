<?php

session_start();
  
if(isset($_SESSION['userId'])){

  require 'dbh.php';

  $userId = $_SESSION['userId'];

  $sql = "SELECT * FROM tbl_posts ORDER BY datePosted DESC";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt,$sql)){
    header("Location: ../main.php?error=sqlerror");
    exit();
  }else{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    //post local id
    $localId = 1;
    while($row = mysqli_fetch_assoc($result)){
      //check if author of this post is blocked by current user - coming soon when you implement the blocked list
      //selecting userName
      //first from the tbl_users
      $sql1 = "SELECT fName,lName FROM tbl_users WHERE idUser=?";
      $stmt1 = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt1,$sql1)){
        header("Location: ../main.php?error=sqlerror");
        exit();
      }
      mysqli_stmt_bind_param($stmt1, "i", $row['idUser']);
      mysqli_stmt_execute($stmt1);
      $result1 = mysqli_stmt_get_result($stmt1);
      if($row1 = mysqli_fetch_assoc($result1)){
        $userName = $row1['fName'].' '.$row1['lName'];
      }
      $fullText = $row['content'];
      $postId = $row['idPost'];
      $global_post_id = $postId;
      //shortening content if it is too long
      if(strlen($row['content'])>250){
        $row['content'] = substr($row['content'],0,250)."...";
      }

      //selecting profile pic for post user from tbl_information
      $sql2 = "SELECT nameProfilePic FROM tbl_information WHERE idUser=?";
      $stmt2 = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt2, $sql2)){
        header("Location: ../main.php?error=sqlerror");
        exit();
      }
      mysqli_stmt_bind_param($stmt2, "i", $row['idUser']);
      mysqli_stmt_execute($stmt2);
      $result2 = mysqli_stmt_get_result($stmt2);
      $row2 = mysqli_fetch_assoc($result2);
      $postImage = $row2['nameProfilePic'];
      if(!$postImage || $postImage == "empty")
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

      //adding the posts
      $newName = "local_".$localId;
      $datePosted = $row['datePosted'];
      $newDate = date('d-m-Y', strtotime($datePosted));

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

      echo
      '<div class="container-main-frame-outer">
      <div class="container-main-frame" name="'.$newName.'">
        <div class="container-header-close-button" onclick="shrinkPost(\''.$newName.'\')">
          <button><img src="Icons/closeMatWhite.png"></button>
        </div>
        <div class="container-header-content">
          <h3><img src="'.$postImage.'" onclick="openAProfile(\''.$row['idUser'].'\')"></img> <span onclick="openAProfile(\''.$row['idUser'].'\')">'.$userName.'</span> <span name="_postAddedDiff">posted '.intval($datediff);
          if($type==1){
            if(intval($datediff)==1)
              echo ' day ago';
            else
              echo ' days ago';
          }else if($type==2){
            if(intval($datediff)==1)
              echo ' hour ago';
            else
              echo ' hours ago';
          }else if($type==3){
            if(intval($datediff)==1)
              echo ' minute ago';
            else
              echo ' minutes ago';
          }else if($type==4){
            echo ' less then a minute ago';
          }
          echo '</span>
            <span style="display: none;">'.$postImage.'</span>
          </h3>

          </div>';
      echo '
        <div class="container-text-content">
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
    </div>';
      $localId++;
    }

    echo '<div id="overlay">
   </div>';
  }
}else{
  header("Location: ../main.php?error=not_set");
  exit();
}  
?>
