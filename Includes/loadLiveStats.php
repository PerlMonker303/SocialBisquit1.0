<?php
  require 'dbh.php';

  //use statements


  //selecting data for container 4
  $number_online = 0;
  $sql_online = "SELECT * FROM tbl_users WHERE is_active=1";
  $result_online = $conn->query($sql_online);
  $number_online = $result_online->num_rows;

  $number_users = 0;
  $sql_users = "SELECT * FROM tbl_users";
  $result_users = $conn->query($sql_users);
  $number_users = $result_users->num_rows;

  $number_posts = 0;
  $sql_posts = "SELECT * FROM tbl_posts";
  $result_posts = $conn->query($sql_posts);
  $number_posts = $result_posts->num_rows;

  $number_shares = 0;
  $sql_shares = "SELECT * FROM tbl_shares";
  $result_shares = $conn->query($sql_shares);
  $number_shares = $result_shares->num_rows;

  $number_likes = 0;
  $sql_likes = "SELECT * FROM tbl_likes";
  $result_likes = $conn->query($sql_likes);
  $number_likes = $result_likes->num_rows;
  $sql_likes_pictures = "SELECT * FROM tbl_likes_pictures";
  $result_likes_pictures = $conn->query($sql_likes_pictures);
  $number_likes += $result_likes_pictures->num_rows;

  $number_comments = 0;
  $sql_comments = "SELECT * FROM tbl_comments";
  $result_comments = $conn->query($sql_comments);
  $number_comments = $result_comments->num_rows;
  $sql_comments_pictures = "SELECT * FROM tbl_comments_pictures";
  $result_comments_pictures = $conn->query($sql_comments_pictures);
  $number_comments += $result_comments_pictures->num_rows;


  echo '
    <span class="left-span">Online users:</span><span class="right-span"> '.$number_online.'</span>
    <span class="left-span">Accounts created:</span><span class="right-span"> '.$number_users.'</span>
    <span class="left-span">Posts made:</span><span class="right-span"> '.$number_posts.'</span>
    <span class="left-span">Articles shared:</span><span class="right-span"> '.$number_shares.'</span>
    <span class="left-span">Likes given:</span><span class="right-span"> '.$number_likes.'</span>
    <span class="left-span">Comments added:</span><span class="right-span"> '.$number_comments.'</span>
  ';

 ?>
