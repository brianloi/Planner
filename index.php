<?php 
//Author: Brian Loi
session_start ();
?>
<head>
<title>Planner</title>
<link href="https://fonts.googleapis.com/css?family=Karla:700|Shrikhand" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="index.css" />
</head>


<body>
	<header>
		<h1>Planner</h1>
		
		<form id="loginForm" onsubmit="login(); return false">
		        <div class="loginInput">
		            <label for="uName">Username:</label>
		            <div>
		                <input id="uName" value="" type="text" pattern=".{4,}" 
		                title="Must be at least 4 characters" required/>
		            </div>
		        </div>
		
		        <div class="loginInput">
		            <label for="password">Password:</label>
		            <div>
		                <input id="password" value="" type="password" pattern=".{4,}"  
		                title="Must be at least 4 characters" required/>
		            </div>
		        </div>
		        
		        <input id="logButt" type="submit" value="Login">
		        <div id="loginError"> </div>
		        
		</form>

	</header>
	
	<div id="description">
		<p>Planner is website for both students and teachers to easily keep
		track of assignments and events together.</p>
		<ul>
			<li>&#10003; Become more organized!</li>
			<li>&#10003; Don't lose track of assignments!</li>
			<li>&#10003; An easy way to manage your time! </li>
		</ul>
	</div>
	
	<div id="signUp">
		<h2>Sign Up</h2>
		<form onsubmit="register(); return false">
			<fieldset>
		        <div class="regInput">
		            <label for="firstName">First Name:</label>
		            <div>
		                <input name="firstName" id="firstName" pattern="[A-Z a-z]*"  value="" type="text" size="25" 
		                	title="Must be letters only" required/>
		            </div>
		        </div>
		
		        <div class="regInput">
		            <label for="lastName">Last Name:</label>
		            <div>
		                <input name="lastName" id="lastName" pattern="[A-Z a-z]*"  value="" size="25" type="text"
		                title="Must be letters only" required/>
		            </div>
		        </div>
		        <br><br>
		        <div class="formInput">
		            <label for="username">Username:</label>
		                <input name="username" id="username" value="" type="text" 
		                pattern=".{4,}" title="Must be at least 4 characters" required/>
		        </div>
		        <br>
		        <div class="formInput">
		            <label for="password">Password:&nbsp;</label>
		                <input name="password" id="pw" value="" type="password" 
		                pattern=".{4,}" title="Must be at least 4 characters" required/>
		        </div>
		        <br>
		        <input type="radio" id="student" name="status" value="Student" required> Student
				
				<input type="radio" id="teacher" name="status" value="Teacher"> Teacher
				
		
		        <br /><br />
		        <input type="submit" id="submit" value="Sign Up">
		        
		        <div id="regError"></div>
		
	    	</fieldset>
	    </form>
	</div>
	
	
	
	<script>
		var divToChange = document.getElementById("regError");
		var fname = document.getElementById("firstName");
		var lname = document.getElementById("lastName");
		var user = document.getElementById("username");
		var pw = document.getElementById("pw");

		function register() {
			var ajax = new XMLHttpRequest();
			ajax.open("POST", "controller.php", true);
			ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			if (document.getElementById("student").checked) {
				ajax.send("mode=register&fname="+fname.value+ "&lname=" + lname.value+ "&user="+user.value+"&pw="+pw.value); 
			}
			if (document.getElementById("teacher").checked) { //Must be a teacher so...
				ajax.send("mode=regTeach&fname="+fname.value+ "&lname=" + lname.value+ "&user="+user.value+"&pw="+pw.value); 
			}
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
		//If failed, echo back an error, else if succeed, use window.location?
					var error = ajax.responseText;
					if (error == 1) {
						var str = "Error: Username already exists";
						divToChange.innerHTML = str;
						fname.value="";
						lname.value="";
						user.value = "";
						pw.value = "";
					}
					else {
						window.location.replace("index.php");
					}
				}
			}
		}


		var uName = document.getElementById("uName");
		var password = document.getElementById("password");
		var divChange = document.getElementById("loginError");
		function login() {
			var ajax = new XMLHttpRequest();
			ajax.open("POST", "controller.php", true);
			ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			ajax.send("mode=login&user="+uName.value+"&pw="+password.value); 
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
		//If failed, echo back an error, else if succeed, use window.location?
					var error = ajax.responseText;
					if (error == "1") {
						var str = "Invalid Username or Password";
						divChange.innerHTML = str;
						uName.value = "";
						password.value = "";
					}
					if (error == "2") {
						window.location.replace("planner.php");
						//window.location.href = "planner.php";
					}
					if (error == "3") {
						window.location.replace("Tplanner.php");
						//window.location.href = "planner.php";
					}
				}
			}
		}


</script>
	
</body>