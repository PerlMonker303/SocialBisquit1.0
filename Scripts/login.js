$(document).ready(function(){
  //add events for top topBanner

  $('[name=btnLogo],[name=btnMenuReg1]').on('click', function(){
    window.location = "index.php";
  });
  $('[name=btn1],[name=btnMenuReg2]').on('click', function(){
    window.location = "register.php";
  });
  $('[name=btn2],[name=btnMenuReg3]').on('click', function(){
    location.reload();
  });
  $('[name=btnMenu]').on('click', toggleMenu);
  $('#btnLog').on('click', loginFunction);

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

function loginFunction(){
  //checking if inputs are empty
  let emailContainer = document.getElementsByName('_loginEmail')[0];
	emailContainer.style.backgroundColor = "#f8f8f8";
	let passContainer	= document.getElementsByName('_loginPass')[0];
  passContainer.style.backgroundColor = "#f8f8f8";
  
  var proceed = true;
  var error_type = 0;
	if(emailContainer.value == ""){
    proceed = false;
    error_type = 1;
		emailContainer.style.backgroundColor = "salmon";
  }
  if((passContainer.value).length < 1){
    proceed = false;
    error_type = 2;
    passContainer.style.backgroundColor = "salmon";
  }
	if(passContainer.value == ""){
    proceed = false;
    error_type = 1;
		passContainer.style.backgroundColor = "salmon";
  }
  
	if(proceed == true){
    //add here log in code
    setTimeout(function(){ $('#btnLogHidden').click()}, 100);
    
	}else{
    //show error message
    let errorMessage = document.getElementById('loginError');
    if(error_type == 1){
      errorMessage.innerHTML = "All spaces must be filled."
    }else if(error_type == 2){
      errorMessage.innerHTML = "Password is too short."
    }
    errorMessage.style.display = "block";
    setTimeout(function(){
      $(errorMessage).fadeOut(1000, function(){
        emailContainer.style.backgroundColor = "#f8f8f8";
        passContainer.style.backgroundColor = "#f8f8f8";
      });
    },2000);
	}
}