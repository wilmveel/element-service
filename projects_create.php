<?php
	session_start();	
	header('Content-type: application/json');	

	$name = $_GET['name']; 
	
	file_put_contents("./data/" . $name . ".ui", "[]");
	
	echo "OK";

?>