<?php
  session_start();
  require 'Includes/dbh.php';
  if(isset($_SESSION['userId'])){
    //if user is logged in, he has no business in here
    header('Location: ./main.php');
    exit();
  }
?>
<html>
<head>
  <title>Social Bisquit</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <link rel="stylesheet" href="Styles/topBanner.css" type="text/css">
  <link rel="stylesheet" href="Styles/mainPage.css" type="text/css">
  <script src="Scripts/topBanner.js" type="text/javascript"></script>
  <script src="Scripts/mainPage.js" type="text/javascript"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="description" content="This is full-out social networking platform that mimics facebook and social dating websites, providing users with an ad-free environment.">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body><!--Style of page body is in mainPage.css-->
  
  <div id="top-banner">
    <div id="container-top-banner">
      <button class="main-buttons" name="btnLogo" (click)="reloadPage()">SocialBisquit</button>
      <button class="main-buttons" name="btn1">Register</button>
      <button class="main-buttons" name="btn2">Log in</button>
      <button class="main-buttons" name="btnMenu"><img src="Icons/menuMatWhite.png"></button>
    </div>

    <div id="container-top-banner-hidden">
        <button class="main-buttons" name="btnLogo" (click)="reloadPage()">SocialBisquit</button>
        <button class="main-buttons" name="btn1">Register</button>
        <button class="main-buttons" name="btn2">Log in</button>
        <button class="main-buttons" name="btnMenuHidden"><img src="Icons/menuMatWhite.png"></button>
        <div id="container-top-banner-menu">
            <span class="menu-buttons" name="btnMenu1" (click)="reloadPage()">SocialBisquit</span>
            <span class="menu-buttons" name="btnMenu2">Register</span>
            <span class="menu-buttons" name="btnMenu3">Log in</span>
        </div>
    </div>
  </div>

  <div id="main-page">
    <div id="main-page-section1">
      <h2>Welcome to</h2>
      <h1>Social Bisquit</h1>
      <h3>The right place to be in the 21st century</h3>
      <button id="btnGetStarted">Get started</button>
    </div>
    <div id="main-page-section2">
      <h2>WHAT is this?</h2>
      <p>An ad-free social-networking platform</p>
      <h2>WHY choose us?</h2>
      <p>Our platform offers both services for networking
          as well as online dating for free</p>
      <h2>WHAT is <em>the catch?</em></h2>
      <p>There is none, seriously (trust us)</p>
      <h2>WILL I find <em>love</em> here?</h2>
      <p>You'll surely do, so what are you waiting for? <br>Hop on and find out for yourself (you won't be dissapointed)</p>
    </div>
    <div id="main-page-section3">
      <h1>Not convinced yet?</h1>
      <h4>Consult these <span style="color:#d63e34;">live</span> statistics</h4>
      <div id="live-statistics">Loading live stats...</div>
    </div>
    <div id="main-page-section4">
      <h2>SO, what are you waiting for?</h2>
      <button id="btnReg">Join today</button>
      <p>Already have an account? Click <a href="./register.php">here</a></p>
      <!--Join today - button; or enter with an existing account-->
    </div>
    <div id="main-page-section5">
      <h2>Latest updates</h2>
      <h3>Version 1.0 - 9/27/19</h3>
      <ul>
        <li>-I finally managed to rewrite the whole platform almost from scratch thanks to previous feedback (thank you)</li>
        <li>-Improved the whole platform front-end as well as back-end system and functionalities</li>
        <li>-Coming soon: live messaging</li>
      </ul>
    </div>
    <div id="footer">
      <span>&#169;AlexandrescuAR-2019</span>
      <div id="links">
        <ul>
          <li><a href="https://www.facebook.com/AlexandrescuAndreiRobert3" target="_blank"><span class="fa fa-facebook"></span></a></li>
          <li><a href="https://www.linkedin.com/in/andrei-robert-alexandrescu-189aa7192/" target="_blank"><span class="fa fa-linkedin"></span></a></li>
          <li><a href="https://www.youtube.com/channel/UC-rg6fYJZv6mXh5_4udemXw" target="_blank"><span class="fa fa-youtube-play"></span></a></li>
        </ul>
      </div>
    </div>
  </div>


  <?php
    //error handling
    if(isset($_GET['error'])){
      echo '<div id="container-notification-frame" style="display: block;">';
      if($_GET['error'] == "sqlerror"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">Sql error.</p>';
      }else if($_GET['error'] == "l_blockeduser"){
        echo '<h2 id="container-notification-title">Access forbidden</h2>
        <p id="container-notificatiion-content">This account has been blocked.</p>';
      }else if($_GET['error'] == "upd_success"){
        echo '<h2 id="container-notification-title">Success</h2>
        <p id="container-notificatiion-content">You have succesfully updated your profile.</p>';
      }else if($_GET['error'] == "post_success"){
        echo '<h2 id="container-notification-title">Success</h2>
        <p id="container-notificatiion-content">Your article has been added to the website.</p>';
      }else if($_GET['error'] == "comm_success"){
        echo '<h2 id="container-notification-title">Success</h2>
        <p id="container-notificatiion-content">Your comment has been added to this post.</p>';
      }else if($_GET['error'] == "feedback_success"){
        echo '<h2 id="container-notification-title">Thank you</h2>
        <p id="container-notificatiion-content">Your feedback has been registered.</p>';
      }else if($_GET['error'] == "passwordcheck"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The passwords do not match. Try again.</p>';
      }else if($_GET['error'] == "passwordshort"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The password is too short (min 6 characters). Try again.</p>';
      }else if($_GET['error'] == "invalidemailnames"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The name and email format are wrong. Try again.</p>';
      }else if($_GET['error'] == "invalidlname"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The last name format is wrong. Try again.</p>';
      }else if($_GET['error'] == "invalidemail"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The email format is wrong. Try again.</p>';
      }else if($_GET['error'] == "emailtaken"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The email is taken. Try another one.</p>';
      }else if($_GET['error'] == "fileProfilePicWrongFormat"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">Choose a valid picture.</p>';
      }else if($_GET['error'] == "fileProfilePicSize"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The picture exceeds the maximum file size.</p>';
      }else if($_GET['error'] == "fileProfilePicFailedUpload"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The picture has failed to upload to the database.</p>';
      }else if($_GET['error'] == "wrongDate"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">The chosen birth date must be a past date (before today).</p>';
      }else if($_GET['error'] == "wrongLength"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">Some inputs are too long.</p>';
      }else if($_GET['error'] == "captchafailed"){
        echo '<h2 id="container-notification-title">Error</h2>
        <p id="container-notificatiion-content">Complete the captcha code.</p>';
      }
      echo '</div>';
    }
    ?>

</body>
</html>
