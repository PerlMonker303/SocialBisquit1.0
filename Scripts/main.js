//data for cloudinary
CLOUDINARY_UPLOAD_PRESET = "https://api.cloudinary.com/v1_1/hgfmqcnjc/upload";
CLOUDINARY_URL = "ox8ghzuy";

$(document).ready(function() {
    //load resize events
    $(window).resize(changeViewMode);
    changeViewMode();
    //button events
    $('[name=btnLogo], #logoButtonMobile, [name=btnLogoMain]').on('click', function(){
        window.location = "main.php";
    });
    $('[name=btnFreq], [name=btnProfileFreq]').on('click', function(){
        //still idk what this button does
        alert('Coming soon');
    });
    //check notificationsCounter
    checkNotificationsCounter();
    $('[name=btnNotif], [name=btnProfileNotif], [name=btnNotifMobile]').on('click', function(){
        toggleNotificationsPage();
    });
    $('[name=btnProfile], [name=btnProfileMobile]').on('click',function(){
        toggleProfile(0);
    });

    $('[name=btnMenu], [name=btnMenuMobile]').on('click',toggleMobileMenu);

   

    //dark mode button event
    $('#darkMode').on('click', toggleDarkMode);

    //for makePost
    $('#makePostUserImgDiv').load('Includes/loadUserProfilePic.php',
    function(){
        $('#makePostUserImgDiv').on('click', function(){
            toggleProfile(0);
        });
    });
    //for making a post
    $('#makePostSendButton').on('click', createPost);
    //for updating word count
    $('#makePostTextarea').bind('input propertychange', updateWordCount);


    //load main posts
    $('#content').load(
        'Includes/loadPostsMainPage.php',
         function(){
            //adding the transitioned event
            $('.container-main-frame').bind('transitionend', function(){
                //animate info about post
                document.getElementsByClassName('container-info-likes')[globalTransitionendId].style.bottom = "35px";
                document.getElementsByClassName('container-info-comments')[globalTransitionendId].style.bottom = "35px";
                document.getElementsByClassName('container-info-shares')[globalTransitionendId].style.bottom = "35px";
                document.getElementsByClassName('container-info-bar')[globalTransitionendId].style.opacity = "1";

                //scroll to that element
                let chosenContainer = document.getElementsByClassName('container-main-frame')[globalTransitionendId];
                chosenContainer.scrollIntoView();
                window.scrollBy(0, -50);
                
            });
            //changing height of post containers for mobile
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
                $('.container-main-frame').css("height","320px");
                $('.container-main-frame-shares').css("height","320px");
            }

            //arrays
            for(let i=0;i<1001;i++){
                isPostOptionMenuOpen[i] = false;
                isShareOptionMenuOpen[i] = false;
            }     

            //DELETE THIS - ONLY FOR WORKING ON PROFILE PAGE
            //toggleProfile();

            isProfilePageOpen = false;
            
        }
    );    
});

function checkNotificationsCounter(){
    if(! /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && document.body.clientWidth >= 990){
        let counter = document.getElementsByName('btnNotifCounter')[0];
        setTimeout(function(){
            counter.style.opacity = "1";
            counter.style.top = "45.5px";
        },500);
        $('[name=btnNotifCounter]').load(
            'Includes/loadNotificationsUnreadCounter.php',
            function(){
                console.log('works');
                if(counter.innerHTML == "0"){
                    counter.style.display = "none";
                }
            }
        );
    }
}

function openAProfile(userId){
    toggleProfile(userId);
}

function changeViewMode(){
    //changing top banner
  if(document.body.clientWidth < 990){
    //hide buttons
    document.getElementsByName('btnLogoMain')[0].style.top = "-100px";
    document.getElementsByName('btnFreq')[0].style.top = "-100px";
    document.getElementsByName('btnNotif')[0].style.top = "-100px";
    document.getElementsByName('btnProfile')[0].style.top = "-100px";

    document.getElementsByName('btnMenu')[0].style.right = "0px";
    document.getElementById('logoButtonMobile').style.top = "0px";
  }else{
    //show buttons
    document.getElementsByName('btnLogoMain')[0].style.top = "0px";
    document.getElementsByName('btnFreq')[0].style.top = "0px";
    document.getElementsByName('btnNotif')[0].style.top = "0px";
    document.getElementsByName('btnProfile')[0].style.top = "0px";

    document.getElementsByName('btnMenu')[0].style.right = "-100px";
    document.getElementById('logoButtonMobile').style.top = "-100px";  
  }
  checkNotificationsCounter();
}

var isMobileMenuToggled = false;

function toggleMobileMenu(){
    if(isMobileMenuToggled == true){
        //hide it
        isMobileMenuToggled = false;
        document.getElementById('container-top-banner-mobile').style.top = "-49px";
    }else{
        //show it
        isMobileMenuToggled = true;
        document.getElementById('container-top-banner-mobile').style.top = "0px";
    }
}

var isDarkModeActive = false;

function toggleDarkMode(){
    if(isDarkModeActive == true){
        isDarkModeActive = false;
        document.getElementsByTagName('body')[0].style.background = "#f5f5f5";
        document.getElementById('darkMode').getElementsByTagName('button')[0].getElementsByTagName('img')[0].src = "Icons/contrastMat.svg";
        if(document.getElementById('container-profile-options')){
            document.getElementById('container-profile-options').style.background = "#f5f5f5";
        }
    }else{
        isDarkModeActive = true;
        document.getElementsByTagName('body')[0].style.background = "#282c34";
        document.getElementById('darkMode').getElementsByTagName('button')[0].getElementsByTagName('img')[0].src = "Icons/contrastMatWhite.png";
        if(document.getElementById('container-profile-options')){
            document.getElementById('container-profile-options').style.background = "#282c34";
        }
    }
}

var expandedId = -1;
var globalTransitionendId = 0;
var isProfilePageOpen = false;

function expandPost(local_idContainer){
    let contPos = local_idContainer.substring(6);
    contPos-=1;

    if(expandedId != -1){
        shrinkPost("local_"+(expandedId+1));
    }

    globalTransitionendId = contPos;
    expandedId = contPos;
    let chosenContainer;
    
    chosenContainer = document.getElementsByClassName('container-main-frame')[contPos];
    console.log(chosenContainer.innerHTML);

    if(local_idContainer.substring(0,5)=='local'){
        
    }else{

       // chosenContainer = document.getElementById('container-profile-page').getElementsByClassName('container-main-frame')[contPos];
    }
    
    
    
    //hide readmore button
    chosenContainer.getElementsByClassName('container-text-readmore')[0].style.display = "none";
    //add close button
    chosenContainer.getElementsByClassName('container-header-close-button')[0].style.display = "block";
    //show full content
    chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[0].style.display = "none";
    chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[1].style.display = "block";
    //show reaction bar
    chosenContainer.getElementsByClassName('container-reaction-bar')[0].style.display = "block";

    let growth = chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[1].offsetHeight;
    if(growth > 50){
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
            chosenContainer.style.height = (growth+180)+"px";
        }else{
            chosenContainer.style.height = (growth+130)+"px";
        }
    }

    chosenContainer.scrollIntoView(globalTransitionendId);
    window.scrollBy(0, -50);

    //toggle comments
    commentCount=0;
    loadComments();

            
    chosenContainer.style.width = "80%";
}

function expandShare(local_idContainer){
    let contPos = local_idContainer.substring(14);
    contPos-=1;
    
    if(expandedId != -1){
        shrinkShare("_profileShare_"+(expandedId+1));
    }
    globalTransitionendId = contPos;
    expandedId = contPos;

    let chosenContainer = document.getElementsByClassName('container-main-frame-shares')[contPos];
    //hide readmore button
    chosenContainer.getElementsByClassName('container-text-readmore')[0].style.display = "none";
    //add close button
    chosenContainer.getElementsByClassName('container-header-close-button')[0].style.display = "block";
    //show full content
    chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[0].style.display = "none";
    chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[1].style.display = "block";
    //show reaction bar
    chosenContainer.getElementsByClassName('container-reaction-bar')[0].style.display = "block";

    let growth = chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[1].offsetHeight;
    if(growth > 50){
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
            chosenContainer.style.height = (growth+180)+"px";
        }else{
            chosenContainer.style.height = (growth+130)+"px";
        }
    }

    chosenContainer.scrollIntoView(globalTransitionendId);
    window.scrollBy(0, -50);

    //toggle comments
    commentCount=0;
    loadComments();

            
    chosenContainer.style.width = "80%";
}

var commentCount = 0;

//for loading comments
function loadComments(postId){
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.display = "block";
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.opacity = "1";
    commentCount+=2;
    var commentPostId = document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].getElementsByTagName('span')[0].innerHTML;
	$('.container-full-comment-section:eq('+globalTransitionendId+')').load(
		"Includes/loadComments.php",
		{
			commentNewCount: commentCount,
			commentPostId: commentPostId
		}, function(){
			//success
			$('[name=_commentClickable]').on('click',function(){
				toggleAProfile();
            });

            
		}
	);
}

var isCommentToolOpen = false;

function toggleCommentTool(postId){
    if(isCommentToolOpen == true){
        document.getElementsByClassName('container-add-comment')[globalTransitionendId].style.display = "none";
        isCommentToolOpen = false;
        //delete textarea contents
        document.getElementsByClassName('makeCommentTextarea')[globalTransitionendId].value = "";
        updateWordCountComment();
    }else{
        if(isShareToolOpen == true){
            toggleShareTool(globalTransitionendId);
        }

        //for loading user pic
        $('.makeCommentUserImgDiv:eq('+globalTransitionendId+')').load('Includes/loadUserProfilePic.php',
        function(){
            $('.makeCommentUserImgDiv').on('click', function(){
                toggleProfile(0);
            });
            //for updating word count
            $('.makeCommentTextarea:eq('+globalTransitionendId+')').bind('input propertychange', updateWordCountComment);
        });

        //post comment event
        $('.makeCommentSendButton:eq('+globalTransitionendId+')').on('click', postComment);
        document.getElementsByClassName('container-add-comment')[globalTransitionendId].style.display = "block";
        isCommentToolOpen = true;
    }
}

function postComment(){
    let content = document.getElementsByClassName('makeCommentTextarea')[globalTransitionendId].value;
    if(cstm_isEmpty(content)){
        console.log('error');
    }else{
        //retrieving data
        let postId = document.getElementsByClassName('container-text-content')[globalTransitionendId].getElementsByTagName('p')[2].innerHTML;
        let parent = postId;

        //calling script
        $.ajax({
            type: "POST",
            url: "Includes/comment.php",
            data: {
                _commentPostId: postId,
                _commentContent: content,
                _commentParent: parent
            },
            success: function(){
                if(commentCount >0 ){
                    commentCount-=2;
                }
                loadComments();
                toggleCommentTool();
                //show success message
                let successMessage = document.getElementById('container-profile-message');
                successMessage.innerHTML = "<span>Commend added successfully</span>";
                successMessage.style.display = "block";
                setTimeout(function(){$(successMessage).fadeOut(1000);},2000);

                //update comment counter
                $('._commentCount:eq('+globalTransitionendId+')').load(
                    'Includes/updateCommentCount.php',
                    {
                        _postId: postId
                    }, function(){
                        //success
                    }
                );
            }
        });
    }
}

var isShareToolOpen = false;

function toggleShareTool(postId){
    if(isShareToolOpen == true){
        document.getElementsByClassName('container-add-share')[globalTransitionendId].style.display = "none";
        isShareToolOpen = false;
        //delete textarea contents
        document.getElementsByClassName('makeShareTextarea')[globalTransitionendId].value = "";
        updateWordCountShare();
    }else{
        if(isCommentToolOpen == true){
            toggleCommentTool(globalTransitionendId);
        }

        //for loading user pic
        $('.makeShareUserImgDiv:eq('+globalTransitionendId+')').load('Includes/loadUserProfilePic.php',
        function(){
            $('.makeShareUserImgDiv').on('click', function(){
                toggleProfile(0);
            });
            //for updating word count
            $('.makeShareTextarea:eq('+globalTransitionendId+')').bind('input propertychange', updateWordCountShare);
        });

        //post share event
        $('.makeShareSendButton:eq('+globalTransitionendId+')').on('click', postShare);
        document.getElementsByClassName('container-add-share')[globalTransitionendId].style.display = "block";
        isShareToolOpen = true;
    }
}

function postShare(){
    let content = document.getElementsByClassName('makeShareTextarea')[globalTransitionendId].value;
    if(cstm_isEmpty(content)){
        console.log('error');
    }else{
        //retrieving data
        let postId = document.getElementsByClassName('container-text-content')[globalTransitionendId].getElementsByTagName('p')[2].innerHTML;

        //calling script
        $.ajax({
            type: "POST",
            url: "Includes/share.php",
            data: {
                _sharePostId: postId,
                _shareContent: content,
            },
            success: function(){
                console.log('posted share');
                toggleShareTool();
                //show success message
                let successMessage = document.getElementById('container-profile-message');
                successMessage.innerHTML = "<span>Post shared successfully</span>";
                successMessage.style.display = "block";
                setTimeout(function(){$(successMessage).fadeOut(1000);},2000);

                //update share counter
                $('._shareCount:eq('+globalTransitionendId+')').load(
                    'Includes/updateShareCount.php',
                    {
                        _postId: postId
                    }, function(){
                        //success
                    }
                );
                //WORK ON loadSharesProfile.php
            }
        });
    }
}

function shrinkPost(local_idContainer){
    if(isCommentToolOpen)
        toggleCommentTool(globalTransitionendId);
    if(isShareToolOpen)
        toggleShareTool(globalTransitionendId);
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.display = "none";
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.opacity = "0";
    let contPos = local_idContainer.substring(6);
    contPos-=1;
    expandedId = -1;
    let chosenContainer = document.getElementsByClassName('container-main-frame')[contPos];
    
    //show readmore button
    chosenContainer.getElementsByClassName('container-text-readmore')[0].style.display = "block";
    //hide close button
    chosenContainer.getElementsByClassName('container-header-close-button')[0].style.display = "none";
    //hide full content
    chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[0].style.display = "block";
    chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[1].style.display = "none";
    //hide reaction bar
    chosenContainer.getElementsByClassName('container-reaction-bar')[0].style.display = "none";
    //hide info about post
    document.getElementsByClassName('container-info-likes')[globalTransitionendId].style.bottom = "0px";
    document.getElementsByClassName('container-info-comments')[globalTransitionendId].style.bottom = "0px";
    document.getElementsByClassName('container-info-shares')[globalTransitionendId].style.bottom = "0px";
    document.getElementsByClassName('container-info-bar')[globalTransitionendId].style.opacity = "0";
    //hide full comments
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.display = "none";
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.opacity = "0";

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
        chosenContainer.style.height = "275px";
    }else{
        chosenContainer.style.height = "220px";
    }
        
    chosenContainer.style.width = "70%";
}

function shrinkShare(local_idContainer){
    if(isCommentToolOpen)
        toggleCommentTool(globalTransitionendId);
    if(isShareToolOpen)
        toggleShareTool(globalTransitionendId);
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.display = "none";
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.opacity = "0";
    let contPos = local_idContainer.substring(6);
    contPos-=1;
    expandedId = -1;
    let chosenContainer = document.getElementsByClassName('container-main-frame')[contPos];
    
    //show readmore button
    chosenContainer.getElementsByClassName('container-text-readmore')[0].style.display = "block";
    //hide close button
    chosenContainer.getElementsByClassName('container-header-close-button')[0].style.display = "none";
    //hide full content
    chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[0].style.display = "block";
    chosenContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[1].style.display = "none";
    //hide reaction bar
    chosenContainer.getElementsByClassName('container-reaction-bar')[0].style.display = "none";
    //hide info about post
    document.getElementsByClassName('container-info-likes')[globalTransitionendId].style.bottom = "0px";
    document.getElementsByClassName('container-info-comments')[globalTransitionendId].style.bottom = "0px";
    document.getElementsByClassName('container-info-shares')[globalTransitionendId].style.bottom = "0px";
    document.getElementsByClassName('container-info-bar')[globalTransitionendId].style.opacity = "0";
    //hide full comments
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.display = "none";
    document.getElementsByClassName('container-full-comment-section')[globalTransitionendId].style.opacity = "0";

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
        chosenContainer.style.height = "275px";
    }else{
        chosenContainer.style.height = "220px";
    }
        
    chosenContainer.style.width = "70%";
}

function likePostFunction(idPost) {
    //call script for liking picture
    $.ajax({
        type: "POST",
        url: "Includes/likePost.php",
        data: {
            _postId: idPost
        },
        success: function(){
            //update like count
            let likedEl = document.getElementsByClassName('_likeCount')[globalTransitionendId];
            $(likedEl).load(
                'Includes/updateLikeCount.php',
                {
                    _postId: idPost
                }
            );
            //update button
            if(document.getElementsByClassName('_icon_like')[globalTransitionendId].src.includes("Icons/likeMatBlack.png")){
                document.getElementsByClassName('_icon_like')[globalTransitionendId].src = "Icons/dislikeMatWhite.png";
            }else if(document.getElementsByClassName('_icon_like')[globalTransitionendId].src.includes("Icons/dislikeMatWhite.png")){
                document.getElementsByClassName('_icon_like')[globalTransitionendId].src = "Icons/likeMatBlack.png";
            }
        }
    });

}

//custom function for checking if a string is empty (or contains only white spaces)
function cstm_isEmpty(str){
    return !str.replace(/\s+/, '').length;
}

function createPost(){
    let inputArea = document.getElementById('makePostTextarea');
    let message = document.getElementById('makePostMessage');
    if(cstm_isEmpty(inputArea.value)){
        message.innerHTML = "Your post can not be empty.";
    }else if((inputArea.value).length>=2000){
        message.innerHTML = "Your post can not exceed 2000 characters.";
    }else{
        
        //add post to tbl_posts
        $.ajax({
            type: "POST",
            url: "Includes/publishPost.php",
            data: {
                _textName: inputArea.value
            },
            success: function(){

            }
        });
        
        //load main posts
        $('#content').load(
            'Includes/loadPostsMainPage.php',
            function(){
                //adding the transitioned event
                $('.container-main-frame').bind('transitionend', function(){
                    //animate info about post
                    document.getElementsByClassName('container-info-likes')[globalTransitionendId].style.bottom = "35px";
                    document.getElementsByClassName('container-info-comments')[globalTransitionendId].style.bottom = "35px";
                    document.getElementsByClassName('container-info-shares')[globalTransitionendId].style.bottom = "35px";
                    document.getElementsByClassName('container-info-bar')[globalTransitionendId].style.opacity = "1";

                    //scroll to that element
                    let chosenContainer = document.getElementsByClassName('container-main-frame')[globalTransitionendId];
                    chosenContainer.scrollIntoView();
                    window.scrollBy(0, -50);
                });
                //changing height of post containers for mobile
                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
                    $('.container-main-frame').css("height","320px");
                }

            }
        );
        inputArea.value = "";
        message.innerHTML = "Your post has been added.";
    }
    message.style.opacity = "1";
    setTimeout(function(){
        message.style.opacity = "0";
      },2000);
}


function updateWordCount(){
    let tarea = (document.getElementById('makePostTextarea').value).length;
    let wordCount = document.getElementById('makePostCharacterCounter').getElementsByTagName('span')[0];
    wordCount.innerHTML = tarea;
}

function updateWordCountComment(){
    let tarea = (document.getElementsByClassName('makeCommentTextarea')[globalTransitionendId].value).length;
    let wordCount = document.getElementsByClassName('makeCommentCharacterCounter')[globalTransitionendId].getElementsByTagName('span')[0];
    wordCount.innerHTML = tarea;
}

function updateWordCountShare(){
    let tarea = (document.getElementsByClassName('makeShareTextarea')[globalTransitionendId].value).length;
    let wordCount = document.getElementsByClassName('makeShareCharacterCounter')[globalTransitionendId].getElementsByTagName('span')[0];
    wordCount.innerHTML = tarea;
}

var isLoadingProfilePages = false;

function toggleProfile(userId){
    //hide makePost
    document.getElementById('container-makepost').style.display = "none";
    //shrink posts from main page
    expandedId = -1;
    //load profile in content
    $('#content').load(
        'Includes/loadProfilePage.php',
        {
            _userId: userId
        }, function(){
            //success
            $('[name=btnSetupProfile]').on('click', toggleSetupProfile);

            isProfilePageOpen = true;
            //load posts
            $('#container-profile-content').load(
                'Includes/loadPostsProfile.php',
                {
                    _userId: userId
                },
                function(){
                    //success
                    $('.container-main-frame').bind('transitionend', function(){
                        //animate info about post
                        document.getElementsByClassName('container-info-likes')[globalTransitionendId].style.bottom = "35px";
                        document.getElementsByClassName('container-info-comments')[globalTransitionendId].style.bottom = "35px";
                        document.getElementsByClassName('container-info-shares')[globalTransitionendId].style.bottom = "35px";
                        document.getElementsByClassName('container-info-bar')[globalTransitionendId].style.opacity = "1";
    
                        //scroll to that element
                        let chosenContainer = document.getElementsByClassName('container-main-frame')[globalTransitionendId];
                        chosenContainer.scrollIntoView();
                        window.scrollBy(0, -50);
                    });
                    //changing height of post containers for mobile
                    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
                        $('.container-main-frame').css("height","320px");
                    }

                    //arrays
                    for(let i=0;i<1001;i++){
                        isPostOptionMenuOpen[i] = false;
                        isShareOptionMenuOpen[i] = false;
                    }          

                    //loading logout button functionality
                    $('[name=_logoutBtn]').on('click', function(){
                        $.ajax({
                            type: "POST",
                            url: "Includes/logout.php",
                            success: function(){
                                window.location = "index.php";
                            }
                        });
                    });

                    //loading addfriend button functionality
                    $('[name=_addFriendBtn]').on('click', function(){
                        //checking what operation to do
                        let btnSend = document.getElementsByName('_addFriendBtn')[0].getElementsByTagName('img')[0];
                        if(btnSend.src.includes("addPerson")){
                            //send a frequest
                            $.ajax({
                                type: "POST",
                                url: "Includes/sendFriendRequest.php",
                                data: {
                                    _friendId: document.getElementById('container-profile-information').getElementsByTagName('span')[1].innerHTML
                                },
                                success: function(){
                                    //load suplement - sent frequest or accepted frequest
                                    $('#container-profile-message').load('Includes/checkFriendshipStatus.php',
                                    {
                                        _friendId: document.getElementById('container-profile-information').getElementsByTagName('span')[1].innerHTML
                                    },
                                    function(){
                                        //show success message
                                        let successMessage = document.getElementById('container-profile-message');
                                        successMessage.style.display = "block";
                                        setTimeout(function(){$(successMessage).fadeOut(1000);},2000);
                                        //change pic based on message
                                        if(successMessage.innerHTML == "<span>Friend request sent</span>"){
                                            btnSend.src = "Icons/swapMatWhite.png";
                                        }else{
                                            btnSend.src = "Icons/deletePersonMatWhite.png";
                                        }
                                    });
                                }
                            });
                        }else if(btnSend.src.includes("swap")){
                            //cancel frequest
                            $.ajax({
                                type: "POST",
                                url: "Includes/cancelFriendRequest.php",
                                data: {
                                    _friendId: document.getElementById('container-profile-information').getElementsByTagName('span')[1].innerHTML
                                },
                                success: function(){
                                    //change pic
                                    btnSend.src = "Icons/addPersonMatWhite.png";
                                    let successMessage = document.getElementById('container-profile-message');
                                    successMessage.innerHTML = "<span>Friend request canceled</span>";
                                    successMessage.style.display = "block";
                                    setTimeout(function(){$(successMessage).fadeOut(1000);},2000);
                                }
                            });
                        }else{
                            //unfriend
                            $.ajax({
                                type: "POST",
                                url: "Includes/deleteFriend.php",
                                data: {
                                    _friendId: document.getElementById('container-profile-information').getElementsByTagName('span')[1].innerHTML
                                },
                                success: function(){
                                    //change pic
                                    btnSend.src = "Icons/addPersonMatWhite.png";
                                    let successMessage = document.getElementById('container-profile-message');
                                    successMessage.innerHTML = "<span>Friend deleted</span>";
                                    successMessage.style.display = "block";
                                    setTimeout(function(){$(successMessage).fadeOut(1000);},2000);
                                }
                            });
                        }
                    });

                    //load options button events
                    $('[name=_profileMenuButton1]').on('click', function(){
                        if(isLoadingProfilePages == false){
                            isLoadingProfilePages = true;
                            changeSelectedButton(1);
                            //loading posts on profile
                            $('#container-profile-content').load(
                                "Includes/loadPostsProfile.php",
                                {
                                    _userId: userId
                                },
                                function()
                                {//success function
                                    isLoadingProfilePages = false;
                                    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
                                        $('.container-main-frame').css("height","320px");
                                    }        
                                }
                            );
                        }
                        

                    });
                    $('[name=_profileMenuButton2]').on('click', function(){
                        if(isLoadingProfilePages == false){
                            isLoadingProfilePages = true;
                            changeSelectedButton(2);
                            $('#container-profile-content').load(
                                "Includes/loadSharesProfile.php",
                                {
                                    _userId: userId
                                },
                                function()
                                {//success function
                                    isLoadingProfilePages = false;
                                    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || document.body.clientWidth < 990){
                                        $('.container-main-frame-shares').css("height","350px");
                                    }    
                                }
                            );
                            //..after loading =false
                        }
                    });
                    $('[name=_profileMenuButton3]').on('click', function(){
                        if(isLoadingProfilePages == false){
                            isLoadingProfilePages = true;
                            changeSelectedButton(3);
                            $('#container-profile-content').load(
                                "Includes/loadFriendsProfile.php",
                                {
                                    _userId: userId
                                },
                                function()
                                {//success function
                                    isLoadingProfilePages = false;
                                      
                                }
                            );

                            //..after loading =false
                        }
                    });
                    $('[name=_profileMenuButton4]').on('click', function(){
                        if(isLoadingProfilePages == false){
                            isLoadingProfilePages = true;
                            changeSelectedButton(4);
                            $('#container-profile-content').load(
                                "Includes/loadMessagesProfile.php",
                                {
                                    _userId: userId
                                },
                                function()
                                {//success function
                                    isLoadingProfilePages = false;
                                      
                                }
                            );

                            //..after loading =false
                        }
                    });

                    //change button
                    changeSelectedButton(1);
                }
            );
        }
    );
}

function acceptFriendRequest(idFreq){
    $.ajax({
        type: "POST",
        url: "Includes/acceptFrequestFromNotifPage.php",
        data: {
            _freqId: idFreq
        },
        success: function(){
            //show success message
            let successMessage = document.getElementById('container-profile-message');
            successMessage.innerHTML = "<span>Friend request accepted</span>";
            successMessage.style.display = "block";
            setTimeout(function(){$(successMessage).fadeOut(1000);},2000);
            //reload notifications page
            toggleNotificationsPage();
        }
    });
}

function rejectFriendRequest(idFreq){
    $.ajax({
        type: "POST",
        url: "Includes/deleteFriendRequest.php",
        data: {
            _freqId: idFreq
        },
        success: function(){
            //show success message
            let successMessage = document.getElementById('container-profile-message');
            successMessage.innerHTML = "<span>Friend request rejected</span>";
            successMessage.style.display = "block";
            setTimeout(function(){$(successMessage).fadeOut(1000);},2000);
            //reload notifications page
            toggleNotificationsPage();
        }
    });
}

function changeSelectedButton(buttonIndex){
    let name = "_profileMenuButton"+buttonIndex;
    let locX = document.getElementsByName(name)[0].offsetLeft;
    switch(buttonIndex){
      case 0:
        //nothing
        break;
      case 1:
        document.getElementById('container-profile-options-bottom').style.left = locX+"px";
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
            document.getElementById('container-profile-options-bottom').style.bottom = "34px";
        }else{
            document.getElementById('container-profile-options-bottom').style.bottom = "-2px";
        }
        break;
      case 2:
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
            document.getElementById('container-profile-options-bottom').style.bottom = "34px";
        }else{
            document.getElementById('container-profile-options-bottom').style.bottom = "-2px";
        }
        document.getElementById('container-profile-options-bottom').style.left = locX+"px";
        break;
      case 3:
        document.getElementById('container-profile-options-bottom').style.bottom = "-2px";
        document.getElementById('container-profile-options-bottom').style.left = locX+"px";
        break;
      case 4:
        document.getElementById('container-profile-options-bottom').style.bottom = "-2px";
        document.getElementById('container-profile-options-bottom').style.left = locX+"px";
        break;
      case 5:
        //nothing yet?
      default:
        break;
    }
  }

var isPostOptionMenuOpen = new Array(1001);
var isShareOptionMenuOpen = new Array(1001);

function togglePostOptionMenu(index){
    if(isPostOptionMenuOpen[index] == false){
        isPostOptionMenuOpen[index] = true;
        document.getElementsByClassName('post-option-text')[index].style.display = "block";
      }else{
        isPostOptionMenuOpen[index] = false;
        document.getElementsByClassName('post-option-text')[index].style.display = "none";
      }
}

function deletePostOption(index, id){
    //calling deletion script
    $.ajax({
        type: "POST",
        url: "Includes/deleteSelectedPost.php",
        data: {
            idOfPost: id
        },
        success: function(){
            //show success message
            let successMessage = document.getElementById('container-profile-message');
            successMessage.innerHTML = "<span>Post deleted</span>";
            successMessage.style.display = "block";
            setTimeout(function(){$(successMessage).fadeOut(1000);},2000);
            //scroll to top
                    document.body.scrollTop = 0; //for safari
                    document.documentElement.scrollTop = 0; //for the rest
            //loading posts on profile
            $('#container-profile-content').load(
                "Includes/loadPostsProfile.php",
                {
                    _userId: userId
                },
                function()
                {//success function
                    isLoadingProfilePages = false;    
                }
            );
        }
    });
    togglePostOptionMenu(index);
}

function toggleShareOptionMenu(index){
    if(isShareOptionMenuOpen[index] == false){
        isShareOptionMenuOpen[index] = true;
        document.getElementsByClassName('share-option-text')[index].style.display = "block";
    }else{
        isShareOptionMenuOpen[index] = false;
        document.getElementsByClassName('share-option-text')[index].style.display = "none";
    }
}

function deleteShareOption(index, id){
    //calling deletion script
    $.ajax({
        type: "POST",
        url: "Includes/deleteSelectedShare.php",
        data: {
        idOfShare: id
        },
        success: function(){
        //show success message
        let successMessage = document.getElementById('container-profile-message');
            successMessage.innerHTML = "<span>Share deleted</span>";
        successMessage.style.display = "block";
        setTimeout(function(){$(successMessage).fadeOut(1000);},2000);
        //scroll to top
                document.body.scrollTop = 0; //for safari
                document.documentElement.scrollTop = 0; //for the rest
        //loading shares on profile
        $('#container-profile-content').load(
                "Includes/loadSharesProfile.php",
                function(){//success function
                    isLoadingProfilePages = false;
                }
            );
        }
    });
    toggleShareOptionMenu(index);
}
  

function loadSetupProfilePage(){
    $('#content').load(
        'Includes/loadSetupProfilePage.php',
        {

        }, function(){
            //success
            $('[name=btnSetupProfile]').on('click', toggleSetupProfile);
            $('[name=secretAnswerSwitch]').on('click', function(){
                let buttonContainer = document.getElementsByName('secretAnswerSwitch')[0];
                if(buttonContainer.innerHTML == 'Show'){
                    buttonContainer.innerHTML = 'Hide';
                    document.getElementsByName('_secretAnswer')[0].type = "text";
                }else{
                    buttonContainer.innerHTML = 'Show';
                    document.getElementsByName('_secretAnswer')[0].type = "password";
                }
            });

            $('#nextButton1').on('click', nextStep1);
            $('#nextButton2').on('click', nextStep2);
            $('#nextButton3').on('click', nextStep3);
            $('#nextButton4').on('click', nextStep4);
            $('#nextButton5').on('click', nextStep5);

            //event for changing profile picture
            $('[name=_setupPicLoaded]').change(function(event){
                let input = document.getElementsByName('_setupPicLoaded')[0];

                let file = event.target.files[0];

                console.log(document.getElementsByName('_setupPicLoaded')[0].value);

                if(file.type.includes("image")){

                    //updating images
                    let file = event.target.files[0];
                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('upload_preset', CLOUDINARY_UPLOAD_PRESET);
                    axios({
                    url: CLOUDINARY_URL,
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    data: formData
                    }).then(function(res){
                    console.log(res);

                    //here u call the script to modify the DB as well
                    //bc the upload is done here in js - see above with axios
                    $.ajax({
                        type: "POST",
                        url: "Includes/updatePicture.php",
                        data: {
                        _url: res.data.secure_url,
                        _idUser: document.getElementsByName('_loggedUserId')[0].value
                        }
                    });
                    }).catch(function(err){
                    console.error(err);
                    });

                }else{
                    let errorMessage = document.getElementsByName('_secretAnswerError')[0];
                    errorMessage.innerHTML = "The uploaded file is not a picture";
                    errorMessage.style.display = "block";
                    setTimeout(function(){
                        $(errorMessage).fadeOut(1000);
                    },4000);
                }
                //$('[name=_setupPicLoaded]').trigger('change');
            });
        }
    );
}

function toggleSetupProfile(){
    //hide makePost
    document.getElementById('container-makepost').style.display = "none";
    //load setup profile page in content
    loadSetupProfilePage();    
}

function nextStep1(){
    if(cstm_isEmpty(document.getElementsByName('_secretAnswer')[0].value) || (document.getElementsByName('_secretAnswer')[0].value).length < 6){
        let errorMessage = document.getElementsByName('_secretAnswerError')[0];
        errorMessage.style.display = "block";
        setTimeout(function(){
            $(errorMessage).fadeOut(1000);
        },4000);
    }else{
        //next step
        $.ajax({
            type: "POST",
            url: "Includes/setupProfileStep1.php",
            data: {
                _secQ: document.getElementsByName('_secretSelect')[0].value,
                _secAns: document.getElementsByName('_secretAnswer')[0].value
            },
            success: function(){
                loadSetupProfilePage();
            }
        });
    }
          
}

function nextStep2(){
    if(document.getElementsByName('_setupPicLoaded')[0].value){
        //next step
        $.ajax({
            type: "POST",
            url: "Includes/setupProfileStep2.php",
            data: {
                _secQ: document.getElementsByName('_secretSelect')[0].value,
                _secAns: document.getElementsByName('_secretAnswer')[0].value
            },
            success: function(){
                loadSetupProfilePage();
            }
        });
    }else{
        let errorMessage = document.getElementsByName('_secretAnswerError')[0];
        errorMessage.innerHTML = "Upload a profile picture";
        errorMessage.style.display = "block";
        setTimeout(function(){
            $(errorMessage).fadeOut(1000);
        },4000);
    }
}

function nextStep3(){
    if(cstm_isEmpty(document.getElementsByName('_step3Input1')[0].value)||cstm_isEmpty(document.getElementsByName('_step3Input2')[0].value)||cstm_isEmpty(document.getElementsByName('_step3Input3')[0].value)){
        let errorMessage = document.getElementsByName('_secretAnswerError')[0];
        errorMessage.innerHTML = "All inputs must be filled";
        errorMessage.style.display = "block";
        setTimeout(function(){
            $(errorMessage).fadeOut(1000);
        },4000);
    }else{
        //next step
        $.ajax({
            type: "POST",
            url: "Includes/setupProfileStep3.php",
            data: {
                _input1: document.getElementsByName('_step3Input1')[0].value,
                _input2: document.getElementsByName('_step3Input2')[0].value,
                _input3: document.getElementsByName('_step3Input3')[0].value
            },
            success: function(){
                loadSetupProfilePage();
            }
        });
    }
}

function nextStep4(){
    let genderIs= document.getElementsByName('_genderSelectIs')[0].value;
    let genderLf= document.getElementsByName('_genderSelectLf')[0].value;
    if(!genderIs||!genderLf){
        let errorMessage = document.getElementsByName('_secretAnswerError')[0];
        errorMessage.innerHTML = "Please fill all inputs";
        errorMessage.style.display = "block";
        setTimeout(function(){
            $(errorMessage).fadeOut(1000);
        },4000);
    }else{
        //next step
        $.ajax({
            type: "POST",
            url: "Includes/setupProfileStep4.php",
            data: {
                _input1: genderIs,
                _input2: genderLf
            },
            success: function(){
                loadSetupProfilePage();
            }
        });
    }
}

function nextStep5(){
    if(cstm_isEmpty(document.getElementsByName('_textareaBio')[0].value)){
        let errorMessage = document.getElementsByName('_secretAnswerError')[0];
        errorMessage.innerHTML = "You can not leave it blank mate";
        errorMessage.style.display = "block";
        setTimeout(function(){
            $(errorMessage).fadeOut(1000);
        },4000);
    }else{
        //next step
        $.ajax({
            type: "POST",
            url: "Includes/setupProfileStep5.php",
            data: {
                _input1: document.getElementsByName('_textareaBio')[0].value
            },
            success: function(){
                loadSetupProfilePage();
            }
        });
    }
}

function likeComment(){
    alert('Coming soon');
}

function commentOnComment(){
    alert('Coming soon');
}

function reportComment(){
    alert('Coming soon');
}

