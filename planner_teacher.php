<?php
//Author: Brian Loi
// THIS PAGE IS FOR TEACHER VIEW
session_start ();
//FOR TESTING:
if (isset($_SESSION['user'])) {
	// logged in !
	//echo user_name($_SESSION['user']);
	$str = "<div>";
	$str .= "You are logged in right now";
	$str .= "</div>";
	echo $str;
}
#$user_name = $_SESSION['user'];
#echo "<body onload=logged('". $user_name . "')>";
?>

<body>
<h1>LOGIN SUCCESS!</h1>

<button onclick="logout()">Logout</button>

<div id="pstatus">You are a teacher</div>

<script>

/*
var pstatus = document.getElementById("pstatus");

function logged(username) {
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("mode=logged&user="+username); 
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var stat = ajax.responseText;
			if (stat == "0") {
				pstatus.innerHTML = "<br>You are a student!";
			}
			else {
				pstatus.innerHTML = "<br>You are a teacher!";
			}
		}
	}
}*/

function logout() {
			var ajax = new XMLHttpRequest();
			ajax.open("POST", "controller.php", true);
			ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			ajax.send("logout=1");
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					window.location.replace("index.php");
				}
			}
		}
</script>
</body>