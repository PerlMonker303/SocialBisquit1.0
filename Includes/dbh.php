<?php

//--for local debugging
$local = 1;
if($local==1){
	$servername = "localhost";
	$dBUsername = "root";
	$dBPassword = "";
	$dBName = "socialcrushrdb";
}else{
	$servername = "us-cdbr-iron-east-02.cleardb.net";
	$dBUsername = "b99d4cf3d9ee42";
	$dBPassword = "c2cb13bc44bac12";
	$dBName = "heroku_fb33c9fd9909483";
}

$conn = mysqli_connect($servername,$dBUsername,$dBPassword,$dBName);

if(!$conn){
	echo 'Error :(';
  die("Connection failed: ".mysqli_connect_error());
}
