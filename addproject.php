<?php
include "config.php";
try{
	$conn = new PDO(DBINFO,USER,PASS);
	$sql = "INSERT INTO projects (title,duration, startyear, description, tags,email) VALUES (:title,:duration, :startyear, :description, :tags,:email)";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':title', $_POST['title'],PDO::PARAM_STR);
	$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
	$stmt->bindParam(':duration', $_POST['duration'], PDO::PARAM_STR);
	$stmt->bindParam(':startyear', $_POST['startyear'], PDO::PARAM_STR);
	$stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
	$stmt->bindParam(':tags', $_POST['tags'], PDO::PARAM_STR);

	
	$stmt->execute();

	$url = "https://gateway.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2017-02-27";
	$headers = array('Content-Type: application/json');
	$features = new \stdClass();
	$categories = new \stdClass();
	$postdata = new \stdClass();
	$ty = new \stdClass();
	$categories->categories = $ty;
	$postdata->features = $categories;

	$postdata->text = $_POST['title'].".".$_POST['description'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERPWD, "4661d928-b5f8-4554-a596-832f29d5ba5c:qYzkLMZgiHM4");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
	$result = curl_exec($ch);
    if ($result === FALSE) {
        die('Send Error: ' . curl_error($ch));
    }
	curl_close($ch);
	echo $result;
	$decoded = json_decode($result);

	//for($i=0; $i<sizeof($decoded->categories); $i++){
		//$conn2 = new PDO(DBINFO,USER,PASS);
	$sql = "INSERT INTO keywords (keyword,confidence) VALUES (:keyword,:confidence)";
	$stmt = $conn->prepare($sql);
	$a = 'asdfds';
	// $stmt->bindValue(':keyword', $decoded->categories[0]->label);
	// $stmt->bindValue(':confidence', $decoded->categories[0]->score);
	$stmt->bindValue(':keyword', $a->label);
	$stmt->bindValue(':confidence', $a->score);
	$stmt->execute();
	//}
}
catch(PDOException $pe){
	die("Could not connect to the database :".$pe->getMessage());
}
?>
