<?php
//Author: Brian Loi
// THIS PAGE IS FOR STUDENT VIEW
session_start ();
//FOR TESTING:
/*
if (isset($_SESSION['user'])) {
	// logged in !
	//echo user_name($_SESSION['user']);
	$str = "<div>";
	$str .= "You are logged in right now";
	$str .= "</div>";
	echo $str;
}
*/
#$user_name = $_SESSION['user'];
#echo "<body onload=logged('". $user_name . "')>";
?>

<head>
<title>Planner</title>
<link href="https://fonts.googleapis.com/css?family=Karla:700|Shrikhand" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="add.css" />
</head>

<body>

<h1>Planner</h1>

<nav>
	<ul>
		<li>
			<a href="planner.php">Home</a>
		</li>
		<li>
			<a href="courses.php">Courses</a>
		</li>
		<li>
			<a href="add.php">Add</a>
		</li>
		<li>
			<a onclick="logout()" href="">Logout</a>
		</li>
	</ul>
</nav>

<h2>Add a Personal Task!</h2>

<form onsubmit="addTask(); return false;">
	<span style="margin-left: 35px;">Title:</span> <input type="text" id="title" required> 
	<br><br>
	<label style="margin-left: 35px;" for="desc">Description: </label>
	<textarea style="margin-left: 35px;" rows="5" cols="60" id="desc" required></textarea>
	<br>
	<span style="margin-left: 35px;">Due Date: </span><input type="date" id="due">
	<br><br>
	<span style="margin-left: 35px;">Link (optional): </span><input type="text" id="link">
	<br><br>
	<div id="butt">
		<input type="submit" value="Add">
	</div>
	<div style="text-align: center;" id="success"></div>
</form>

<script>

function addTask() {
	var title = document.getElementById("title");
	var desc = document.getElementById("desc");
	var date = document.getElementById("due"); //Need to split up the value of this
	var link = document.getElementById("link");
	var toChange = document.getElementById("success");

	//SPLIT DATE VALUE HERE
	var year = date.value.slice(0,4); // worked
	var mon = date.value.slice(5,7);
	if (mon[0] == '0')
		mon = mon[1];
	var day = date.value.slice(8,10);
	if (day[0] == '0')
		day = day[1];

	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("addTask=1&title="+title.value+"&desc="+desc.value+"&month="+mon
			+ "&day="+day + "&year="+year+"&link="+link.value);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = ajax.responseText; //Need to get array

			title.value = "";
			desc.value = "";
			date.value= "";
			link.value = "";
			toChange.innerHTML = "Task Successfully Added!";
		}
	}
	
}

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