<?php
  session_start();
  require 'Includes/dbh.php';
  if(!isset($_SESSION['userId'])){
    header("Location: index.php");
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
  <link rel="stylesheet" href="Styles/main.css" type="text/css">
  <link rel="stylesheet" href="Styles/setupPage.css" type="text/css">
  <link rel="stylesheet" href="Styles/profile.css" type="text/css">
  <link rel="stylesheet" href="Styles/notificationsPage.css" type="text/css">
  <script src="Scripts/main.js" type="text/javascript"></script>
  <script src="Scripts/notificationsPage.js" type="text/javascript"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="description" content="This is full-out social networking platform that mimics facebook and social dating websites, providing users with an ad-free environment.">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

  <div id="container-top-banner">
    <button class="main-buttons" name="btnLogoMain">Social Bisquit</button>
    <button class="main-buttons" name="btnFreq"><img src="Icons/peopleMatWhite.png"></button>
    <button class="main-buttons" name="btnNotif"><img src="Icons/notificationsMatWhite.png">
      <div name="btnNotifCounter">2</div>
    </button>
    <button class="main-buttons" name="btnProfile"><img src="Icons/profileMatWhite.png"></button>
    <button class="main-buttons" name="btnMenu"><img src="Icons/menuMatWhite.png"></button>
  </div>
  <button id="logoButtonMobile" class="main-buttons">Social Bisquit</button>
  <div id="container-top-banner-mobile">
    <button class="main-buttons" name="btnFreqMobile"><img src="Icons/peopleMatWhite.png"></button>
    <button class="main-buttons" name="btnNotifMobile"><img src="Icons/notificationsMatWhite.png"></button>
    <button class="main-buttons" name="btnProfileMobile"><img src="Icons/profileMatWhite.png"></button>
    <button class="main-buttons" name="btnMenuMobile"><img src="Icons/menuMatWhite.png"></button>
  </div>

  <div id="container-content-wrapper">
    <div id="container-makepost">
      <div id="makePostUserImgDiv"></div>      
      <textarea id="makePostTextarea" placeholder="What's on your mind today?" maxlength="2000"></textarea>
      <span id="makePostCharacterCounter"><span>0</span><br>/2000</span>
      <button id="makePostSendButton" class="main-buttons"><img src="Icons/createMatWhite.png"></button>
      <span id="makePostMessage">-notification text-</span>
    </div>
    <div id="content">

    </div>
  </div>
  
  <div id="darkMode">
    <button class="main-buttons"><img src="Icons/contrastMat.svg"></button>
  </div>

  <div id="container-profile-message">
    <span>-message-</span>
  </div>
  
</body>


</html>