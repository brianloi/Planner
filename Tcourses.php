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
<link rel="stylesheet" type="text/css" href="planner.css" />
</head>

<body>

<h1>Planner</h1>

<nav>
	<ul>
		<li>
			<a href="Tplanner.php">Home</a>
		</li>
		<li>
			<a href="Tcourses.php">Courses</a>
		</li>
		<li>
			<a href="Tadd.php">Add</a>
		</li>
		<li>
			<a onclick="logout()" href="">Logout</a>
		</li>
	</ul>
</nav>

<div id="left-col">
	
	<div id="yourCourses">
		<h2>Your Courses:</h2>
		<table id="tableCourses">
		</table>
	</div>
	
	<div id="warning">Warning! <br>Deleting a Course will destroy all records associated with the
course! Including any tasks assigned to them
</div>

</div>

<div id="formContainer">
	<h2>Create a Course!</h2>
	<form onsubmit="createCourse(); return false;">
		<div class="toCenter">
		Course Name: &nbsp;&nbsp;<input id="courseName" required>
		</div>
		<br>
		<div style="text-align: center;">
			<input type="submit" value="Create">
		</div>
	</form>
	<div id="newCourseInfo">
	</div>
</div>


<script>

window.onload = function() {
	showCourses();
}

function createCourse() {
	var toChange = document.getElementById("newCourseInfo");
	var name = document.getElementById("courseName");
	
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("createCourse=" + name.value);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var info = JSON.parse(ajax.responseText);
			console.log(info);
			name.value = "";

			toChange.innerHTML = "<p>Course Successfully Created!</p>";
			toChange.innerHTML += "Course ID: "+ info[0]["id"];
			toChange.innerHTML += " &nbsp;&nbsp; Course Name: "+info[0]["course_name"];

			showCourses();
		}
	}
}

function showCourses() {
	var toChange = document.getElementById("tableCourses");
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("showTCourses=1");
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = JSON.parse(ajax.responseText); //Need to get array
			toChange.innerHTML = "<tr><th>ID</th><th>Course Name</th><th></th></tr>";
			for (var i=0; i<results.length; i++) {
				toChange.innerHTML += "<tr><td>" + results[i]["id"] + "</td><td>" + results[i]["course_name"] + "</td> \
				<td><button onclick='delCourse(" + results[i]["id"]  + ")'>Delete</button></td></tr>";
				
			}
		}
	}
}

function delCourse(course_id) {
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("delCourse="+ course_id);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			showCourses();
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