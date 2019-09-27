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
  <link rel="stylesheet" href="Styles/register.css" type="text/css">
  <script src="Scripts/register.js" type="text/javascript"></script>
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
  <div id="container-wrapper-register">
    <form id="container-register" action="Includes/signup.php" method="post">
      <h3>Create an account <u>for free</u></h3>
      <span class="registerSpans">First name</span>
      <input type="text" class="inputReg" name="_regFname">
      <span class="registerSpans">Last name</span>
      <input type="text" class="inputReg" name="_regLname">
      <span class="registerSpans">Email address</span>
      <input type="text" class="inputReg" name="_regEmail">
      <span class="registerSpans">Password</span>
      <input type="password" class="inputReg" name="_regPass">
      <span class="registerSpans">Confirm Password</span>
      <input type="password" class="inputReg" name="_regRePass">
      <span id="registerError">-error message-</span>
      <input type="button" value="Register" id="btnReg">
      <button type="submit" id="btnRegHidden">Register</button>
      <p>Already have an account? Click <a href="login.php">here</a></p>
    </form>

    <?php 
      if(isset($_GET['error'])){
        echo '<div id="container-message">';
        if($_GET['error'] == "invalidemailnames"){
          echo '
              <span>Please use only valid characters for email and names fields</span>
          ';
        }else if($_GET['error'] == "invalidemail"){
          echo '
              <span>Please use only valid characters for the email field</span>
          ';
        }else if($_GET['error'] == "invalidlname"){
          echo '
              <span>Please use only valid characters for name fields</span>
          ';
        }else if($_GET['error'] == "passwordcheck"){
          echo '
              <span>Passwords do not match.</span>
          ';
        }else if($_GET['error'] == "passwordshort"){
          echo '
              <span>The password is too weak.</span>
          ';
        }else if($_GET['error'] == "sqlerror"){
          echo '
              <span>Sql error. Please try again at a later time.</span>
          ';
        }
        else if($_GET['error'] == "emailtaken"){
          echo '
              <span>The email address is already taken. Please try a different one.</span>
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
</html>
