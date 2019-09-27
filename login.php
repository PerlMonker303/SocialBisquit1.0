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
  <link rel="stylesheet" href="Styles/login.css" type="text/css">
  <script src="Scripts/login.js" type="text/javascript"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="description" content="This is full-out social networking platform that mimics facebook and social dating websites, providing users with an ad-free environment.">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div id="top-banner">
    <div id="container-top-banner-register">
      <button class="main-buttons" name="btnLogo" (click)="reloadPage()">SocialBisquit</button>
      <button class="main-buttons" name="btn1">Register</button>
      <button class="main-buttons" name="btn2">Log in</button>
      <button class="main-buttons" name="btnMenu">Menu</button>
    </div>

    <div id="container-top-banner-register-menu">
        <span class="menu-buttons-register" name="btnMenuReg1" (click)="reloadPage()">SocialBisquit</span>
        <span class="menu-buttons-register" name="btnMenuReg2">Register</span>
        <span class="menu-buttons-register" name="btnMenuReg3">Log in</span>
    </div>
  </div>
  <div id="container-wrapper-login">
    <form id="container-login" action="Includes/login.php" method="post">
      <h3>Enter with an existing account</h3>
      <span class="loginSpans">Email address</span>
      <input type="text" class="inputLog" name="_loginEmail">
      <span class="loginSpans">Password</span>
      <input type="password" class="inputLog" name="_loginPass">
      <span id="loginError">-error message-</span>
      <button type="submit" id="btnLogHidden">Log in</button>
      <input type="button" value="Log in" id="btnLog">
      
      <p>Don't have an account yet? Click <a href="register.php">here</a></p>
    </form>

    <?php 
      if(isset($_GET['error'])){
        echo '<div id="container-message">';
        if($_GET['error'] == "sgn_success"){
          echo '
              <span>Account created successfully</span>
          ';
        }else if($_GET['error'] == "l_wrongpassword" || $_GET['error'] == "l_nouser" || $_GET['error'] == "l_mistake"){
          echo '
              <span>Wrong username or password</span>
          ';
        }else if($_GET['error'] == "l_blockeduser"){
          echo '
              <span>This account has been banned</span>
          ';
        }else if($_GET['error'] == "sqlerror"){
          echo '
              <span>Sql error. Please try again at a later time.</span>
          ';
        }

        echo '</div>';
      }
    ?>
    
  
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

  
</body>
