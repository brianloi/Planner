<?php
//Author: Brian Loi
session_start ();

include 'DatabaseAdapter.php';  // for $theDBA, an instance of DataBaseAdaptor

if	(!empty($_POST["logout"])){
	//DESTROY SESSION
	session_unset();
	session_destroy();
	#header('Location: index.php'); This does not work? But session is still ended
	exit ();
}

if	(!empty($_POST["findCourse"])){
	//Get courses
	$search = $_POST['findCourse'];
	$search = htmlspecialchars($search);
	
	$classes = $theDBA->findCourses($search);
	echo json_encode($classes);
	exit ();
}

if	(!empty($_POST["addTask"])){
	//Get info
	$title = $_POST['title'];
	$title = htmlspecialchars($title);
	$desc = $_POST['desc'];
	$desc = htmlspecialchars($desc);
	$month = $_POST['month'];
	$month = htmlspecialchars($month);
	$day = $_POST['day'];
	$day = htmlspecialchars($day);
	$year = $_POST['year'];
	$year = htmlspecialchars($year);
	$link = $_POST['link'];
	$link = htmlspecialchars($link);
	
	$success = $theDBA->addPTask($_SESSION['user'], $title, $desc, $month, $day, $year, $link);
	echo $success;
	exit ();
}

if	(!empty($_POST["addCourseTask"])){
	//Get info
	$title = $_POST['title'];
	$title = htmlspecialchars($title);
	$desc = $_POST['desc'];
	$desc = htmlspecialchars($desc);
	$month = $_POST['month'];
	$month = htmlspecialchars($month);
	$day = $_POST['day'];
	$day = htmlspecialchars($day);
	$year = $_POST['year'];
	$year = htmlspecialchars($year);
	$link = $_POST['link'];
	$link = htmlspecialchars($link);
	$name = $_POST['course_name'];
	$name = htmlspecialchars($name);
	
	$success = $theDBA->addCourseTask($name, $title, $desc, $month, $day, $year, $link);
	echo $success;
	exit ();
}

if	(!empty($_POST["getDetail"])){
	//Get courses
	$dateId = $_POST['getDetail'];
	$dateId = htmlspecialchars($dateId);
	
	$details = $theDBA->getDetails($dateId);
	echo json_encode($details);
	exit ();
}

if	(!empty($_POST["getPDetail"])){
	//Get courses
	$dateId = $_POST['getPDetail'];
	$dateId = htmlspecialchars($dateId);
	
	$details = $theDBA->getPDetails($dateId);
	echo json_encode($details);
	exit ();
}

if	(!empty($_POST["findCourseById"])){
	//Get courses
	$search = $_POST['findCourseById'];
	$search = htmlspecialchars($search);
	
	$classes = $theDBA->findCoursesById($search);
	echo json_encode($classes);
	exit ();
}

if	(!empty($_POST["showCourses"])){
	//Get courses
	$classes = $theDBA->showCourses($_SESSION['user']);
	echo json_encode($classes);
	exit ();
}

if	(!empty($_POST["showTCourses"])){
	//Get courses
	$classes = $theDBA->showTCourses($_SESSION['user']);
	echo json_encode($classes);
	exit ();
}

if	(!empty($_POST["showToDo"])){
	$dates = $theDBA->getDates($_SESSION['user']);
	echo json_encode($dates);
	exit ();
}

if	(!empty($_POST["showTeacherToDo"])){
	$dates = $theDBA->getTDates($_SESSION['user']);
	echo json_encode($dates);
	exit ();
}

if	(!empty($_POST["createCourse"])){
	$add = $_POST['createCourse'];
	$add = htmlspecialchars($add);
	$temp = $theDBA->createCourse($add, $_SESSION['user']);
	echo json_encode($temp);
	exit ();
}

if	(!empty($_POST["addCourse"])){
	//Get courses
	$add = $_POST['addCourse'];
	$add = htmlspecialchars($add);
	$temp = $theDBA->addCourse($add, $_SESSION['user']);
	echo $temp;
	exit ();
}

if	(!empty($_POST["remClass"])){
	$del = $_POST['remClass'];
	$del = htmlspecialchars($del);
	$temp = $theDBA->remClass($del, $_SESSION['user']);
	echo $temp;
	exit ();
}

if	(!empty($_POST["delCourse"])){
	$del = $_POST['delCourse'];
	$del = htmlspecialchars($del);
	$temp = $theDBA->delCourse($del, $_SESSION['user']);
	echo $temp;
	exit ();
}

if (!empty($_POST["mode"])){
	$mode = $_POST['mode'];
	$mode = htmlspecialchars($mode);
	if ($mode == "register"){
		$fname = $_POST['fname'];
		$fname = htmlspecialchars($fname);
		$lname = $_POST['lname'];
		$lname = htmlspecialchars($lname);
		$user = $_POST['user'];
		$user = htmlspecialchars($user);
		$pw = $_POST['pw'];
		$pw = htmlspecialchars($pw);
		
		$err = $theDBA->register($fname, $lname, $user, $pw);
		echo $err;
		exit ();
	}
	if ($mode == "logged"){
		$user = $_POST['user'];
		$user = htmlspecialchars($user);
		
		$status = $theDBA->logged($user);
		echo $status;
		exit ();
	}
	
	if ($mode == "regTeach"){
		$fname = $_POST['fname'];
		$fname = htmlspecialchars($fname);
		$lname = $_POST['lname'];
		$lname = htmlspecialchars($lname);
		$user = $_POST['user'];
		$user = htmlspecialchars($user);
		$pw = $_POST['pw'];
		$pw = htmlspecialchars($pw);
		
		$err = $theDBA->regTeach($fname, $lname, $user, $pw);
		echo $err;
		exit ();
	}
	if ($mode == "login"){
		$user = $_POST['user'];
		$user = htmlspecialchars($user);
		$pw = $_POST['pw'];
		$pw = htmlspecialchars($pw);
		
		$err = $theDBA->login($user, $pw);
		if ($err == 0) {
			$_SESSION['user'] = $user;
		}
		echo $err;
		exit();
	}
}

?>