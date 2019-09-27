$(document).ready( function(){
  //load scroll events
  document.addEventListener("touchmove", showHiddenTopBanner, false);
  $(document).scroll(showHiddenTopBanner);
  //load resize events
  $(window).resize(changeViewMode);
  changeViewMode();
  //load click events
  let btn = document.getElementById('container-top-banner').getElementsByTagName('button')[3];
  btn.addEventListener('click', toggleMenu);
  btn = document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[3];
  btn.addEventListener('click', toggleMenu);
  //add events for buttons
  $('[name=btnLogo],[name=btnMenu1]').on('click', function(){
    location.reload();
  });
  $('[name=btn1],[name=btnMenu2]').on('click', function(){
    window.location = "register.php";
  });
  $('[name=btn2],[name=btnMenu3]').on('click', function(){
    window.location = "login.php";
  });
});

//function for showing the top topBanner
function showHiddenTopBanner(){
  if(document.body.scrollTop > 20 || document.documentElement.scrollTop > 20){
    //visible
    document.getElementById('container-top-banner-hidden').style.top = "0px";
    document.getElementById('container-top-banner-menu').style.top = "-53px";
  }else{
    //hidden
    document.getElementById('container-top-banner-hidden').style.top = "-49px";
  }
}

function changeViewMode(){
  //changing top banner
  if(document.body.clientWidth < 990){
    document.getElementById('container-top-banner').getElementsByTagName('button')[3].style.right = "0px";
    document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[3].style.right = "0px";
    document.getElementById('container-top-banner').getElementsByTagName('button')[0].style.top = "-100px";
    document.getElementById('container-top-banner').getElementsByTagName('button')[1].style.top = "-100px";
    document.getElementById('container-top-banner').getElementsByTagName('button')[2].style.top = "-100px";
    document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[0].style.top = "-100px";
    document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[1].style.top = "-100px";
    document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[2].style.top = "-100px";
    //for fixing a bug
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
      document.getElementsByName('btnMenu')[0].style.right = "-20px";
    }
    document.getElementsByName('btnMenu')[0].style.top = "0px";
  }else{
    document.getElementById('container-top-banner').getElementsByTagName('button')[3].style.right = "-100px";
    document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[3].style.right = "-100px";
    document.getElementById('container-top-banner').getElementsByTagName('button')[0].style.top = "0px";
    document.getElementById('container-top-banner').getElementsByTagName('button')[1].style.top = "0px";
    document.getElementById('container-top-banner').getElementsByTagName('button')[2].style.top = "0px";
    document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[0].style.top = "0px";
    document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[1].style.top = "0px";
    document.getElementById('container-top-banner-hidden').getElementsByTagName('button')[2].style.top = "0px";
    //for fixing a bug
    if(!( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )) {
      document.getElementsByName('btnMenu')[0].style.right = "0px";
    }
    //hide menu
    document.getElementById('container-top-banner-menu').style.top = "-43px";
    document.getElementsByName('btnMenu')[0].style.top = "-49px";
  }
}

//function for toggling the menu
function toggleMenu(){
  let menu = document.getElementById('container-top-banner-menu');
  if(document.getElementById('container-top-banner-menu').style.top != "45px"){
    //menu is closed - we're opening it
    if(document.getElementById('container-top-banner-hidden').style.top < "0px"){
      menu.style.top = "45px";
    }else{
      console.log('showing menu - shown');
      menu.style.top = "-3px";
    }
  }else{
    if(document.getElementById('container-top-banner-hidden').style.top <= "0px"){
      menu.style.top = "-3px";
    }else{
      console.log('closing menu - shown');
      menu.style.top = "-43px";
    }
  }
}
