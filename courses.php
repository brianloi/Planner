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

<div id="c-left-col">
	
	<div>
		<h2>Current Courses</h2>
		<ul id="courses">

		</ul>
	</div>

</div>

<div id="c-mid">
	<h2>Search</h2>
	<br>
	<div style="text-align:center;">
		<form onsubmit="findCourse(); return false;">
			<input type="text" id="search" size="50" required>
			<input type="submit" value="Search">
			<br>
			<input type="radio" id="byName" name="by" required> By Course Name	
			<input type="radio" id="byId" name="by" > By Course ID	
		</form>
	</div>
	<div id="addErr" style="text-align:center;"></div>
	<div id="results">
	</div>
</div>

<div id="right-col">
	<h2>Remove Course</h2>
	<div id="showRemove">
	</div>
</div>


<script>

window.onload = function() {
	showCourses();
	showRemCourses();
}

function showRemCourses() {
	var toChange = document.getElementById("showRemove");
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("showCourses=1");
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = JSON.parse(ajax.responseText); //Need to get array
			toChange.innerHTML = ""
			for (var i=0; i<results.length; i++) {
				toChange.innerHTML += "<div>" + results[i]["course_name"] + "&nbsp;<button onclick=\
				'removeClass("+ results[i]["id"] + ")'>Remove</button> </div>";
			}
		}
	}
}

function removeClass(class_id) {
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("remClass="+class_id);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = JSON.parse(ajax.responseText); //Need to get array
			window.location.replace("courses.php");
		}
	}
}

function findCourse() {
	var toChange = document.getElementById("results");
	var toRem = document.getElementById("addErr");
	var search = document.getElementById("search");
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	if (document.getElementById("byName").checked) {
		ajax.send("findCourse="+search.value);
	}
	if (document.getElementById("byId").checked) {
		ajax.send("findCourseById="+search.value);
	}
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = JSON.parse(ajax.responseText); //Need to get array
			toRem.innerHTML = "";
			toChange.innerHTML = "";
			for (var i=0; i<results.length; i++) {
				toChange.innerHTML += "<div> ID: " + results[i]["id"] + " \
				 	| Course Name: " +results[i]["course_name"] + "\
				 	| Teacher: " + results[i]["first_name"] +" "+ results[i]["last_name"] + "\
				 	" + "<button class='addButt' onclick='addCourse(this.id)' id="+ results[i]["id"] +">Add</button>" + "</div>";
			}

			if (results.length == 0) {
				toChange.innerHTML = "<div>No Results</div>";
			}
		}
	}
}

function addCourse(course_id) {
	var toChange = document.getElementById("addErr");
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("addCourse="+course_id);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var rep = ajax.responseText;
			if (rep == '1') {
				toChange.innerHTML = "Course already added<br><br>"
			}
			if (rep == '0') {
				window.location.replace("courses.php");
			}
		}
	}
}

function showCourses() {
	var toChange = document.getElementById("courses");
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("showCourses=1");
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = JSON.parse(ajax.responseText); //Need to get array
			toChange.innerHTML = "";
			for (var i=0; i<results.length; i++) {
				toChange.innerHTML += "<li>" + results[i]["course_name"] + "</li>";
			}
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