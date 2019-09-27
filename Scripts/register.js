$(document).ready(function(){
  //add events for top topBanner

  $('[name=btnLogo],[name=btnMenuReg1]').on('click', function(){
    window.location = "index.php";
  });
  $('[name=btn1],[name=btnMenuReg2]').on('click', function(){
    location.reload();
  });
  $('[name=btn2],[name=btnMenuReg3]').on('click', function(){
    window.location = "login.php";
  });
  $('[name=btnMenu]').on('click', toggleMenu);
  $('#btnReg').on('click', registerFunction);

  //load resize events
  $(window).resize(changeViewMode);
  changeViewMode();
});

function changeViewMode(){
  //changing top banner
  if(document.body.clientWidth < 990){
    console.log('hidden');
    document.getElementsByName('btnLogo')[0].style.top = "-49px";
    document.getElementsByName('btn1')[0].style.top = "-49px";
    document.getElementsByName('btn2')[0].style.top = "-49px";
    document.getElementsByName('btnMenu')[0].style.right = "0px";
    //for fixing a bug
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
      document.getElementsByName('btnMenu')[0].style.right = "-20px";
    }
  }else{
    console.log('full');
    document.getElementsByName('btnLogo')[0].style.top = "0px";
    document.getElementsByName('btn1')[0].style.top = "0px";
    document.getElementsByName('btn2')[0].style.top = "0px";

    //for fixing a bug
    if(!( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )) {
      document.getElementsByName('btnMenu')[0].style.right = "0px";
    }

    document.getElementsByName('btnMenu')[0].style.right = "-100px";
    //hide menu
    document.getElementById('container-top-banner-register-menu').style.top = "-49px";
  }
}

function toggleMenu(){
  if(document.getElementById('container-top-banner-register-menu').style.top >= "0px"){
    //hide it
    document.getElementById('container-top-banner-register-menu').style.top = "-49px";
  }else{
    //show it
    document.getElementById('container-top-banner-register-menu').style.top = "0px";
  }
}

function registerFunction(){
  let fNameContainer = document.getElementsByName('_regFname')[0];
  fNameContainer.style.backgroundColor = "#f8f8f8";
  let lNameContainer = document.getElementsByName('_regLname')[0];
  lNameContainer.style.backgroundColor = "#f8f8f8";
  let emailContainer = document.getElementsByName('_regEmail')[0];
  emailContainer.style.backgroundColor = "#f8f8f8";
  let passContainer = document.getElementsByName('_regPass')[0];
  passContainer.style.backgroundColor = "#f8f8f8";
  let rePassContainer = document.getElementsByName('_regRePass')[0];
  rePassContainer.style.backgroundColor = "#f8f8f8";
  
  var proceed = true;
  var error_type = 0;
  if(!$.trim(fNameContainer.value).length){
    proceed = false;
    error_type = 1;
		fNameContainer.style.backgroundColor = "salmon";
  }
  if(!$.trim(lNameContainer.value).length){
    proceed = false;
    error_type = 1;
		lNameContainer.style.backgroundColor = "salmon";
  }
  if(!$.trim(emailContainer.value).length){
    proceed = false;
    error_type = 1;
		emailContainer.style.backgroundColor = "salmon";
  }
  if(!$.trim(passContainer.value).length){
    proceed = false;
    error_type = 1;
		passContainer.style.backgroundColor = "salmon";
  }
  if(!$.trim(rePassContainer.value).length){
    proceed = false;
    error_type = 1;
		rePassContainer.style.backgroundColor = "salmon";
  }
  if(passContainer.value != rePassContainer.value){
    proceed = false;
    error_type = 2;
    passContainer.style.backgroundColor = "salmon";
    rePassContainer.style.backgroundColor = "salmon";
  }
  let passCheck = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
  if(!passContainer.value.match(passCheck)){
    proceed = false;
    error_type = 3;
    passContainer.style.backgroundColor = "salmon";
  }

  if(proceed == true){
    //add here log in code
    setTimeout(function(){ $('#btnRegHidden').click()}, 100);
    
	}else{
    let errorMessage = document.getElementById('registerError');
    if(error_type == 1){
      errorMessage.innerHTML = "All spaces must be filled."
    }else if(error_type == 2){
      errorMessage.innerHTML = "Passwords do not match."
    }else if(error_type == 3){
      errorMessage.innerHTML = "Password must be between 6 and 30 characters, contain at least one numeric digital, one uppercase and one lowercase letter."
    }
    errorMessage.style.display = "block";
    setTimeout(function(){
      $(errorMessage).fadeOut(1000, function(){
        fNameContainer.style.backgroundColor = "#f8f8f8";
        lNameContainer.style.backgroundColor = "#f8f8f8";
        emailContainer.style.backgroundColor = "#f8f8f8";
        passContainer.style.backgroundColor = "#f8f8f8";
        rePassContainer.style.backgroundColor = "#f8f8f8";
      });
    },5000);
  }
}
