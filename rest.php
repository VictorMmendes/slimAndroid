<?php

	require 'Slim/Slim.php';
	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();

	function getConn() {

		return new PDO('mysql:host=127.0.0.1;dbname=android', 'root', 'root',
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}

	$app->get('/exercicios.json', function() {
		$conn = getConn();
		$sql = "SELECT * FROM exercicio";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		// $animais = $stmt->fetchAll();
		// foreach ($animais as $animal) {
		// 	echo $animal['id'] . "<br>";
		// 	echo $animal['name'] . "<br>";
		// 	echo $animal['species'] . "<br>";
		// 	echo $animal['breed'] . "<br>";
		// 	echo $animal['weight'] . "<br>";
		// 	echo $animal['birthdate'] . "<br>";
		// 	echo $animal['size'] . "<br><br>";
		// }
		echo json_encode($stmt->fetchAll());
	});

	$app->get('/modificacoes.json/:id', function($id) {
		$conn = getConn();
		$sql = "SELECT * FROM modificacao where id_exercise = $id";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		// $animais = $stmt->fetchAll();
		// foreach ($animais as $animal) {
		// 	echo $animal['id'] . "<br>";
		// 	echo $animal['name'] . "<br>";
		// 	echo $animal['species'] . "<br>";
		// 	echo $animal['breed'] . "<br>";
		// 	echo $animal['weight'] . "<br>";
		// 	echo $animal['birthdate'] . "<br>";
		// 	echo $animal['size'] . "<br><br>";
		// }
		echo json_encode($stmt->fetchAll());
	});

	$app->post('/', function() use ($app) {
		echo "<h2>[POST] - Framework Slim</h2>";
	});


	$app->put('/', function() use ($app) {
		echo "<h2>[PUT] Framework Slim</h2>";
	});


	$app->delete('/', function() use ($app){
		echo "<h2>[DELETE] Framework Slim</h2>";
	});

	$app->run();

?>
