<?php
//Author: Brian Loi

class DatabaseAdaptor {
	// The instance variable used in every one of the functions in class DatbaseAdaptor
	private $DB;
	// Make a connection to the data based named 'imdb_small' (described in project).
	public function __construct() {
		$db = 'mysql:dbname=planner;host=127.0.0.1;charset=utf8';
		$user = 'root';
		$password = '';
		
		try {
			$this->DB = new PDO ( $db, $user, $password );
			$this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			echo ('Error establishing Connection');
			exit ();
		}
	}
	
	public function findCourses($search) {
		$stmt = $this->DB->prepare("SELECT courses.id, courses.course_name,
				teachers.first_name, teachers.last_name FROM courses JOIN
				teachers ON courses.teacher_id=teachers.id WHERE courses.course_name 
				LIKE CONCAT('%', :search, '%')");
		$stmt->bindParam("search", $search);
		
		$stmt->execute();
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function register($fname, $lname, $username, $pw) {
		$stmtt = $this->DB->prepare("SELECT * FROM teachers WHERE username = :username");
		$stmtt->bindParam("username", $username);
		$stmtt->execute();
		
		if($stmtt->rowCount() > 0)
		{
			// row exists.
			echo 1;
			exit ();
		}
		else {
		
		$stmt = $this->DB->prepare("SELECT * FROM students WHERE username = :username");
		$stmt->bindParam("username", $username);
		$stmt->execute();
		
		if($stmt->rowCount() > 0)
		{
			// row exists.
			echo 1;
			exit();
		}
		else {
			$hashed_pw = password_hash($pw, PASSWORD_DEFAULT);
			
			$stmt = $this->DB->prepare("INSERT INTO students (first_name, last_name, username, hash)
    			VALUES(:fname, :lname, :user, :password)");
			$stmt->bindParam("fname", $fname);
			$stmt->bindParam("lname", $lname);
			$stmt->bindParam("user", $username);
			$stmt->bindParam("password", $hashed_pw);
			$stmt->execute();
			
			echo 0;
		}
		}
	}
	
	public function regTeach($fname, $lname, $username, $pw) {

		$stmtx = $this->DB->prepare("SELECT * FROM students WHERE username = :username");
		$stmtx->bindParam("username", $username);
		$stmtx->execute();
		
		if($stmtx->rowCount() > 0)
		{
			// row exists.
			echo 1;
		}
		else {
		
		 $stmt = $this->DB->prepare("SELECT * FROM teachers WHERE username = :username");
		 $stmt->bindParam("username", $username);
		 $stmt->execute();
		 
		 if($stmt->rowCount() > 0)
		 {
		 // row exists.
		 echo 1;
		 exit ();
		 }
		 else {
			$hashed_pw = password_hash($pw, PASSWORD_DEFAULT);
			
			$stmt = $this->DB->prepare("INSERT INTO teachers (first_name, last_name, username, hash)
    			VALUES(:fname, :lname, :user, :password)");
			$stmt->bindParam("fname", $fname);
			$stmt->bindParam("lname", $lname);
			$stmt->bindParam("user", $username);
			$stmt->bindParam("password", $hashed_pw);
			$stmt->execute();
			
			echo 0;
		}
		}
	}
	
	public function logged($username) {
		$stmtx = $this->DB->prepare("SELECT * FROM students WHERE username = :username");
		$stmtx->bindParam("username", $username);
		$stmtx->execute();
		
		if($stmtx->rowCount() > 0)
		{
			// row exists.
			echo 0;
			exit();
		}
		else { #Must?
			echo 1;
			exit();
		}
	}
	
	public function login($username, $pw) {
		$stmt = $this->DB->prepare("SELECT * FROM students WHERE username = :username");
		$stmt->bindParam("username", $username);
		$stmt->execute();
		
		$stmt2 = $this->DB->prepare("SELECT * FROM teachers WHERE username = :username");
		$stmt2->bindParam("username", $username);
		$stmt2->execute();
		
		if($stmt->rowCount() > 0)
		{
			// row exists. STUDENT
			//get hash password
			$stmt = $this->DB->prepare("SELECT * FROM students WHERE username = :username");
			$stmt->bindParam("username", $username);
			$stmt->execute();
			$test = $stmt->fetchAll ( PDO::FETCH_ASSOC );
			$hash = $test[0]['hash'];
			//if password matches hash, echo 0
			if (password_verify($pw, $hash)) {
				echo 2;
			}
			
			else {
				echo 1;
			}
		}
		else if ($stmt2->rowCount() > 0) {
			// row exists. Teacher
			//get hash password
			$test2 = $stmt2->fetchAll ( PDO::FETCH_ASSOC );
			$hash = $test2[0]['hash'];
			//if password matches hash, echo 0
			if (password_verify($pw, $hash)) {
				echo 3;
			}	
			else {
				echo 1;
			}
		}
		else {
			echo 1;
		}
	}
	
	public function findCoursesById($search) {
		$stmt = $this->DB->prepare("SELECT courses.id, courses.course_name,
				teachers.first_name, teachers.last_name FROM courses JOIN
				teachers ON courses.teacher_id=teachers.id WHERE courses.id = :search");
		$stmt->bindParam("search", $search);
		$stmt->execute();
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function addCourse($course_id, $name) {
		$find = $this->DB->prepare("SELECT id FROM students WHERE username = :name");
		$find->bindParam("name", $name);
		$find->execute();
		$test = $find->fetchAll ( PDO::FETCH_ASSOC );
		
		$check = $this->DB->prepare("SELECT * FROM enrolled WHERE student_id= :student_id AND course_id= :course_id");
		$check->bindParam("course_id", $course_id);
		$check->bindParam("student_id", $test[0]['id']);
		$check->execute();
		if($check->rowCount() > 0) {
			//Course already added
			echo 1;
			exit();
		}
		
		$stmt = $this->DB->prepare("INSERT INTO enrolled (student_id, course_id) VALUES ( :student_id, :course_id" .")");
		$stmt->bindParam("course_id", $course_id);
		$stmt->bindParam("student_id", $test[0]['id']);
		$stmt->execute();
		echo 0;
	}
	
	public function showCourses($username) {
		$find = $this->DB->prepare("SELECT id FROM students WHERE username = :username");
		$find->bindParam("username", $username);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		
		$stmt = $this->DB->prepare("SELECT enrolled.student_id, courses.course_name, courses.id FROM enrolled JOIN courses
			ON enrolled.course_id=courses.id WHERE enrolled.student_id=:student_id");
		$stmt->bindParam("student_id", $getId[0]['id']);
		$stmt->execute();
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function showTCourses($username) {
		$find = $this->DB->prepare("SELECT id FROM teachers WHERE username = :username");
		$find->bindParam("username", $username);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		
		$stmt = $this->DB->prepare("SELECT courses.course_name, courses.id FROM
					courses JOIN teachers ON teachers.id=courses.teacher_id WHERE teachers.id=:teacher_id");
		
		$stmt->bindParam("teacher_id", $getId[0]['id']);
		$stmt->execute();
		
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function remClass($course_id, $username) {
		$find = $this->DB->prepare("SELECT id FROM students WHERE username = :username");
		$find->bindParam("username", $username);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		
		$stmt = $this->DB->prepare("DELETE FROM enrolled WHERE student_id= :student_id AND course_id=:course_id");
		$stmt->bindParam("course_id", $course_id);
		$stmt->bindParam("student_id", $getId[0]['id']);
		$stmt->execute();
		echo 0;
	}
	
	public function getDetails($dateId) {
		$stmt = $this->DB->prepare("
		SELECT dates.id, dates.title, dates.description, dates.month, dates.day,
		dates.year, dates.link, teachers.first_name, teachers.last_name,
 		courses.course_name FROM dates JOIN courses ON
		dates.course_id=courses.id JOIN teachers ON teachers.id=courses.teacher_id
		WHERE dates.id = :dateID");
		$stmt->bindParam("dateID", $dateId);
		
		$stmt->execute();
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function getPDetails($dateId) {
		$stmt = $this->DB->prepare("SELECT * FROM dates WHERE id = :dateID");
		$stmt->bindParam("dateID", $dateId);
		$stmt->execute();
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function getDates($username) {
		#$results = array();
		$find = $this->DB->prepare("SELECT id FROM students WHERE username = :username");
		$find->bindParam("username", $username);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		
		$stmt = $this->DB->prepare("SELECT dates.title, dates.description, dates.month, dates.day, dates.year, dates.link,
				courses.course_name, dates.id FROM students JOIN enrolled ON
				students.id=enrolled.student_id JOIN courses ON enrolled.course_id=courses.id
				JOIN dates ON courses.id=dates.course_id WHERE enrolled.student_id=:student_id");
		$stmt->bindParam("student_id", $getId[0]['id']);
		$stmt->execute();
		
		$stmt2 = $this->DB->prepare("SELECT * FROM dates WHERE student_id= :student_id");
		$stmt2->bindParam("student_id", $getId[0]['id']);
		$stmt2->execute();
		
		$results1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		
		$results = array_merge($results1,$results2);
		return $results;
	}
	
	public function getTDates($username) {

		$find = $this->DB->prepare("SELECT id FROM teachers WHERE username = :username");
		$find->bindParam("username", $username);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		
		$stmt = $this->DB->prepare("SELECT dates.title, dates.description, dates.month, dates.day, dates.year, dates.link,
				courses.course_name, dates.id FROM dates JOIN courses ON dates.course_id=courses.id 
				JOIN teachers ON courses.teacher_id=teachers.id WHERE teachers.id=:teacher_id");
		$stmt->bindParam("teacher_id", $getId[0]['id']);
 		$stmt->execute();
		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return $results;
	}
	
	public function addPTask($username, $title, $desc, $month, $day, $year, $link) {
		$find = $this->DB->prepare("SELECT id FROM students WHERE username = :username");
		$find->bindParam("username", $username);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		//student id is $getId[0]['id']
		
		$zero = 0;
		$stmt = $this->DB->prepare("INSERT INTO dates (course_id, title, description, month, day, year, link, student_id) 
				VALUES(:course_id, :title, :desc, :month, :day, :year, :link, :student_id)");
		$stmt->bindParam("course_id", $zero);
		$stmt->bindParam("title", $title);
		$stmt->bindParam("desc", $desc);
		$stmt->bindParam("month", $month);
		$stmt->bindParam("day", $day);
		$stmt->bindParam("year", $year);
		$stmt->bindParam("link", $link);
		$stmt->bindParam("student_id", $getId[0]['id']);
		$stmt->execute();
		
		echo 0; //success
	}
	
	public function addCourseTask($name, $title, $desc, $month, $day, $year, $link) {
		$find = $this->DB->prepare("SELECT id FROM courses WHERE course_name = :name");
		$find->bindParam("name", $name);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		//course id is $getId[0]['id']
		
		$stmt = $this->DB->prepare("INSERT INTO dates (course_id, title, description, month, day, year, link)
				VALUES(:course_id, :title, :desc, :month, :day, :year, :link)");
		$stmt->bindParam("course_id", $getId[0]['id']);
		$stmt->bindParam("title", $title);
		$stmt->bindParam("desc", $desc);
		$stmt->bindParam("month", $month);
		$stmt->bindParam("day", $day);
		$stmt->bindParam("year", $year);
		$stmt->bindParam("link", $link);
		$stmt->execute();
		
		echo 0; //success
	}
	
	public function createCourse($courseName, $username) {
		$find = $this->DB->prepare("SELECT id FROM teachers WHERE username = :username");
		$find->bindParam("username", $username);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		
		$stmt = $this->DB->prepare("INSERT INTO courses (course_name, teacher_id) 
					VALUES(:course_name, :t_id)");
		$stmt->bindParam("course_name", $courseName);
		$stmt->bindParam("t_id", $getId[0]['id']);
		$stmt->execute();
		
		$stmt2 = $this->DB->prepare("SELECT * FROM courses WHERE course_name= :course_name");
		$stmt2->bindParam("course_name", $courseName);
		$stmt2->execute();
		
		$results = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	public function delCourse($courseID, $username) {
		$find = $this->DB->prepare("SELECT id FROM teachers WHERE username = :username");
		$find->bindParam("username", $username);
		$find->execute();
		$getId = $find->fetchAll ( PDO::FETCH_ASSOC );
		// $getId[0]['id'] is TEACHER ID
		
		//DELETE FROM COURSES TABLE
		$stmt = $this->DB->prepare("DELETE FROM courses WHERE id=:course_id
								AND teacher_id=:t_id");
		$stmt->bindParam("course_id", $courseID);
		$stmt->bindParam("t_id", $getId[0]['id']);
		$stmt->execute();
		
		//DELETE FROM ENROLLED TABLE
		$stmt2 = $this->DB->prepare("DELETE FROM enrolled WHERE id=:course_id");
		$stmt2->bindParam("course_id", $courseID);
		$stmt2->execute();
		
		//DELETE FROM DATES TABLE
		$stmt3 = $this->DB->prepare("DELETE FROM dates WHERE course_id=:course_id");
		$stmt3->bindParam("course_id", $courseID);
		$stmt3->execute();
		
		echo 0;
	}
	
	
} // End class DatabaseAdaptor

$theDBA = new DatabaseAdaptor ();
?>