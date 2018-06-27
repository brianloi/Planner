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
<link href="https://fonts.googleapis.com/css?family=Karla:700|Shrikhand" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="planner.css" />
<title>Planner</title>
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

<div id="left-col">

	<div id="calendar">
	</div>
	
	<div id="yourCourses">
		<h2>Courses</h2>
		<ul id="courses">
		</ul>
	</div>

</div>

<div id="mid">
	<h2>To Do:</h2>
	<div id="toDo">
	</div>
</div>

<div id="right-col">
	<h2>Details</h2>
	<div id="details">
		Click on the details of a task
		to get more info on it!
	</div>
</div>


<script>

window.onload = function() {
	showCourses();
	getDate();
	getDates();
}

function getDates() {
	var toChange = document.getElementById("toDo");
	var months = ['Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec'];

	var now = new Date()
	var d = now.getDate(); //Number date
	var mon = now.getMonth();
	var year = now.getFullYear();

	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("showToDo=1");
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var dates = JSON.parse(ajax.responseText); //Need to get array

			dates.sort(
					  function(a, b) {
					    // compare year
					    if (parseInt(a.year) < parseInt(b.year))
					      return -1;
					    else if (parseInt(a.year) > parseInt(b.year))
					      return 1;

					    // years were equal, try month
					    if (parseInt(a.month) < parseInt(b.month))
					      return -1;
					    else if (parseInt(a.month) > parseInt(b.month))
					      return 1;

					 // month were equal, try day
					    if (parseInt(a.day) < parseInt(b.day))
					      return -1;
					    else if (parseInt(a.day) > parseInt(b.day))
					      return 1;

					    return 0;
					  }
					);


			toChange.innerHTML = "";
			for (var i=0; i<dates.length; i++) {

				//Three Ifs: Prevents ToDo list from showing "expired" dates!
				if (year > dates[i]["year"]) {
					continue;
				}
				if (year == dates[i]["year"] && mon > dates[i]["month"]) {
					continue;
				}
				if (mon == dates[i]["month"] && d > dates[i]["day"]) {
					continue;
				}

				if (dates[i]["course_name"]) {
					toChange.innerHTML += ("<div> <h3 style='display:inline;'>" + dates[i]["course_name"]
					+ "</h3>"+ " "+ dates[i]["title"] + "<span class='dateAndButt'> Due: " + months[dates[i]["month"]-1]
					 + " "+ dates[i]["day"] + " "
					 + "<button onclick=showDetails('" + dates[i]["id"] + "')>Details</button>"+"</span></div>");
				}
				else { // Personal Task:
					toChange.innerHTML += ("<div> <h3 style='display:inline;'>" + "Personal"
					+ "</h3>"+ " "+ dates[i]["title"] + "<span class='dateAndButt'> Due: " + months[dates[i]["month"]-1]
					 + " "+ dates[i]["day"] + " "
					 + "<button onclick=showPDetails('" + dates[i]["id"] + "')>Details</button>"+"</span></div>");
				}
				
			}
		}
	}	
}

function showPDetails(dateId) {
	var toChange = document.getElementById("details");
	var months = ['Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec'];
	
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("getPDetail="+dateId);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = JSON.parse(ajax.responseText); //Need to get array

			toChange.innerHTML = ("Personal" 
					+ "<br><br>Title: " + results[0]["title"]
					+ "<br><br>Description: " + results[0]["description"]
					+ "<br><br>Due: " + months[results[0]["month"]-1] + " "+ results[0]["day"]
					+ ", " + results[0]["year"]
					);

			if (results[0]["link"]) {
				toChange.innerHTML += ("<br>Link: " + "<a href="+ results[0]["link"] +">More Info</a>");
			}
		}
	}
}

function showDetails(dateId) {
	var toChange = document.getElementById("details");
	var months = ['Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec'];
	
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "controller.php", true);
	ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajax.send("getDetail="+dateId);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var results = JSON.parse(ajax.responseText); //Need to get array

			console.log(results);
			if (results[0]["course_name"]) { //If NOT PERSONAL
				toChange.innerHTML = (results[0]["course_name"] + "<br>"
					+ "Teacher: " + results[0]["first_name"] + " " + results[0]["last_name"]
					+ "<br><br>Title: " + results[0]["title"]
					+ "<br><br>Description: " + results[0]["description"]
					+ "<br><br>Due: " + months[results[0]["month"]-1] + " "+ results[0]["day"]
					+ ", " + results[0]["year"]
					);
			}
			else { // PERSONAL TASK!
				
			}

			if (results[0]["link"]) {
				toChange.innerHTML += ("<br>Link:"
										+ "<a href="+ results[0]["link"] +">"
										+ "</a>"
										);
				alert("work?");
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

function getDate() {
	var toChange = document.getElementById("calendar");
	var now = new Date()
	var d = now.getDate();
	var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
	var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

	var day = days[ now.getDay() ];
	var month = months[ now.getMonth() ];

	toChange.innerHTML = "<time class='icon'> \
	<em>"+ day +"</em> \
	<strong>"+ month +"</strong> \
	<span>"+ d +"</span> \
	</time>";
		
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