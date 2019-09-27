function toggleNotificationsPage(){
    //hide makePost
    document.getElementById('container-makepost').style.display = "none";
    //shrink posts from main page
    expandedId = -1;
    //load notifications page
    $('#content').load('Includes/loadNotificationsPage.php', function(){
        
        //add events for buttons
    });
}

function deleteNotification(idNotif){
    $.ajax({
		type: "POST",
		url: "Includes/deleteNotification.php",
		data: {
			_idNotif: idNotif
		},
		success: function(){
			//reload notificationsPage
            toggleNotificationsPage();
            //show success message
            let successMessage = document.getElementById('container-profile-message');
            successMessage.innerHTML = "<span>Notification deleted successfully</span>";
            successMessage.style.display = "block";
            setTimeout(function(){$(successMessage).fadeOut(1000);},2000);
		}
	});
}

function markNotificationAsRead(idNotif){
    $.ajax({
		type: "POST",
		url: "Includes/markNotificationAsRead.php",
		data: {
			_idNotif: idNotif
		},
		success: function(){
			//reload news notifications		

            //reload unread counter
            /*
			$('[name=_newNewsCounter]').load(
				"Includes/loadNewsUnreadCounter.php",
				{
					_loggedUserId: document.getElementsByName('_loggedUserId')[0].value
				}, function(){
					if(document.getElementsByName('_newNewsCounter')[0].innerHTML == "<span>0</span>"){
						document.getElementsByName('_newNewsCounter')[0].style.display = "none";
					}else{
						document.getElementsByName('_newNewsCounter')[0].style.display = "block";
					}
				}
			);*/

			//reload notifications page
			toggleNotificationsPage();
			

		}
	});
}

//for showing post from notifications
function clickNewsNotificationPost(idPost){
	//instantiate a div with the post and stuff - only if it doesn't exist
	if(!document.getElementById('container-specific-post')){
		var div = document.createElement("div");
		div.id = "container-specific-post";

		let parentElement = document.getElementById("container-notifications-page");
		parentElement.insertBefore(div, parentElement.children[2]);

	}

	$('#container-specific-post').load(
		'Includes/loadPostSpecific.php',
		{
			_idPost: idPost
		}, function(){
			//success
			let specificContainer = document.getElementById('container-specific-post');
			specificContainer.style.display = "block";
			specificContainer.getElementsByClassName('container-main-frame')[0].style.height = "auto";

			//show reaction bar
			specificContainer.getElementsByClassName('container-reaction-bar')[0].style.display = "block";

			let growth = specificContainer.getElementsByClassName('container-text-content')[0].getElementsByTagName('p')[1].offsetHeight;
			if(growth > 50){
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
					specificContainer.style.height = (growth+180)+"px";
				}else{
					specificContainer.style.height = (growth+130)+"px";
				}
			}
			let origHeight = specificContainer.getElementsByClassName('container-text-content')[0].offsetHeight;
			specificContainer.getElementsByClassName('container-text-content')[0].style.height = (origHeight+40) + "px";

			//go to top
			document.body.scrollTop = 0; // For Safari
 			document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

			//animate info about post
			document.getElementsByClassName('container-info-likes')[globalTransitionendId].style.bottom = "35px";
			document.getElementsByClassName('container-info-comments')[globalTransitionendId].style.bottom = "35px";
			document.getElementsByClassName('container-info-shares')[globalTransitionendId].style.bottom = "35px";
			document.getElementsByClassName('container-info-bar')[globalTransitionendId].style.opacity = "1";

			commentCount=0;
			//load comments
			loadComments(idPost);
		}

	);
}

function closeSpecificPost(){
	document.getElementById('container-specific-post').style.display = "none";
}
