$(document).ready(function(){

  //add event for click on getStarted button
  $('#btnGetStarted').on('click',function(){
    window.location = "login.php";
  });

  //load live statistics
  $('#live-statistics').load(
    "Includes/loadLiveStats.php"
  );

  //for loading stats every 5 seconds
	setInterval(function(){
		$('#live-statistics').load(
			"Includes/loadLiveStats.php"
		); // this will run after every 5 seconds
	}, 5000);
});
