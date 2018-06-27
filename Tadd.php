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
	
	<div id="selectC">
		<div class="toCenter"> Select A Course to Add A Task To: </div>
		<table id="tableCourses">
		</table>
	</div>
</div>

<div id="formContainer">
	<h2>Add a Task!</h2>
	<form onsubmit="addCourseTask(); return false;">
	<span style="margin-left: 35px;">Course:</span> <input type="text" id="course" readonly>
	<br><br>
	<span style="margin-left: 35px;">Title:</span> <input type="text" id="title" required> 
	<br><br>
	<label style="margin-left: 35px;" for="desc">Description: </label>
	<textarea style="margin-left: 35px;" rows="5" cols="50" id="desc" required></textarea>
	<br>
	<span style="margin-left: 35px;">Due Date: </span><input type="date" id="due">
	<br><br>
	<span style="margin-left: 35px;">Link (optional): </span><input type="text" id="link">
	<br><br>
	<div id="butt">
		<input id="addTButt" type="submit" value="Add">
	</div>
	<br>
	<div id="success" style="text-align:center;">
	</div>
</form>
</div>


<script>

window.onload = function() {
	showCourses();
}

function addCourseTask() {
	var course = document.getElementById("course");
	var title = document.getElementById("title");
	var desc = document.getElementById("desc");
	var date = document.getElementById("due"); //Need to split up the value of this
	var link = document.getElementById("link");
	var toChange = document.getElementById("success");

	//Checking that a class was selected
	if (course.value == "") {
		toChange.innerHTML = "Please select a course!";
		return
	}

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
	ajax.send("addCourseTask=1&title="+title.value+"&desc="+desc.value+"&month="+mon
			+ "&day="+day + "&year="+year+"&link="+link.value + "&course_name="+course.value);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = ajax.responseText; //for testing
			
			title.value = "";
			desc.value = "";
			date.value= "";
			link.value = "";
			course.value = "";
			toChange.innerHTML = "Task Successfully Added!";
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
				<td><button onclick='showOnForm(\"" + results[i]["course_name"] + "\")'>Select</button></td></tr>";
			}
		}
	}
}

function showOnForm(course_id) {
	var toChange = document.getElementById("course");
	toChange.value = course_id;
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