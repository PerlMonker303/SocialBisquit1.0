<?php
  session_start();

  require 'dbh.php';

  if(isset($_SESSION['userId'])){

    $commentNewCount = htmlentities($_POST['commentNewCount']);
    $commentsPostId = htmlentities($_POST['commentPostId']);


    echo '<span name="secret-post-id">'.$commentsPostId.'</span>';

    $sql = "SELECT * FROM tbl_comments WHERE idPost=? ORDER BY datePosted DESC";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt,$sql);
    mysqli_stmt_bind_param($stmt, "i", $commentsPostId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result)>0){
      $userName = "";
      $currentIndex = 0;
      while($row = mysqli_fetch_assoc($result)){
        if($currentIndex < $commentNewCount){
          $currentIndex++;
          //selecting user name by the id
          $userIdComment = $row['idUser'];
          $sql1 = "SELECT * FROM tbl_users WHERE idUser=$userIdComment";
          $result1 = mysqli_query($conn,$sql1);
          $row1 = mysqli_fetch_assoc($result1);
          $userName = $row1['fName']." ".$row1['lName'];

          $sql2 = "SELECT * FROM tbl_information WHERE idUser=$userIdComment";
          $result2 = mysqli_query($conn,$sql2);
          $row2 = mysqli_fetch_assoc($result2);
          $profileImagePath = $row2['nameProfilePic'];
          if($profileImagePath == "")
            $profileImagePath = "https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg";

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
          
          echo '
          <div class="container-comment">
            <div class="comment-header">
              <img src="'.$profileImagePath.'" onclick="openAProfile('.$row['idUser'].')"></img>
              <h4 onclick="openAProfile('.$row['idUser'].')">'.$userName.'</h4>
              <h5> posted '; 
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
              echo '</h5>
            </div>
            <div class="comment-content">
              <span>'.$row['content'].'</span>
            </div>
            <div class="comment-bar">
              <div name="comment-bar-like" onclick="likeComment()">
                <img src="Icons/likeMatBlack.png"></img>
              </div>
              <div name="comment-bar-comment" onclick="commentOnComment()">
                <img src="Icons/commentMat.png"></img>
              </div>
              <div name="comment-bar-report"  onclick="reportComment()">
                <img src="Icons/alertMat.svg"></img>
              </div>
            </div>
          </div>';
        }
      }
      if($currentIndex < mysqli_num_rows($result))
        echo '<button class="show-more-comments" onclick="loadComments()">Show more comments</button>';
    }else{
      echo '<p>Be the first to comment on this post.</p>';
    }
  }else{
    header("Location: ../main.php?error=not_set");
    exit();
  }
 ?>
