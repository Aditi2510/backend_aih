<?php
include "config.php";
try{
	$conn = new PDO(DBINFO,USER,PASS);
	//$sql = "select * from userinfo";

	//$sql = "INSERT INTO userinfo (name,email) VALUES (:name,:email)";

	$sql = "Select * from userinfo where email = :email and password = :password";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
	$stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$row = json_encode($result);
	if($row == "false"){
		echo "Email not found";
	}
	else{
		echo "true";
	}
	/*if($row == "false"){
		$message = "false";
	}
	else{
		$_SESSION['email'] = $_POST['email'];
		$message = "true";
	}*/
	//echo $result;
}
catch(PDOException $pe){
	echo "false";
	die("Could not connect to the database :".$pe->getMessage());
}
?>