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

		echo json_encode($stmt->fetchAll());
	});

	$app->get('/insert.json/:description&:repeats&:weight', function($description, $repeats, $weight) {

			$sql = "INSERT INTO exercicio (description, repeats, weight) values('$description', '$repeats', $weight)";
			$conn = getConn();
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			echo json_encode("OK");
	});

	$app->post('/exercicios.json', function() use($app)
	{
		$dadoJson = $app->request()->getBody();
		echo parse_str($dadoJson);
		// echo $app;
		// $description=$dadoJson[0]->description;
		// $repeats=$dadoJson[0]->repeats;
		// $weight=$dadoJson[0]->weight;

		// $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
		// $txt = "" . $dadoJson;
		// fwrite($myfile, $txt);
		// fclose($myfile);

		// $sql = "INSERT INTO exercicio (description, repeats, weight) values('oii', '$dadoJson', 12)";
		// $conn = getConn();
		// $stmt = $conn->prepare($sql);
		// $stmt->execute();

		// $id = $conn->lastInsertId();
		// $data = date('Y-m-d');
		//
		// $sql = "INSERT INTO modificacao (progressdate, id_exercise, weight) values('$data', $id, '$weight')";
		// $stmt = $conn->prepare($sql);
		// $stmt->execute();

		echo json_encode("OK");
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
