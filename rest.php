<?php

	require 'Slim/Slim.php';
	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();

	function getConn() {

		return new PDO('mysql:host=127.0.0.1;dbname=android', 'root', 'root',
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}

	$app->get('/exercicios.json/:query', function($query) {
		if($query == "@")
		{
			$query = "%%";
		} else {
			$query = "%" . $query . "%";
		}

		$conn = getConn();
		$sql = "SELECT * FROM exercicio WHERE description LIKE '$query'";

		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode($stmt->fetchAll());
	});

	$app->get('/insert.json/:description&:repeats&:weight', function($description, $repeats, $weight) {

			$sql = "INSERT INTO exercicio (description, repeats, weight) values('$description', '$repeats', $weight)";
			$conn = getConn();
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			$id = $conn->lastInsertId();
			$data = date('Y-m-d');

			$sql = "INSERT INTO modificacao (progressdate, id_exercise, weight) values('$data', $id, $weight)";
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			echo json_encode("OK");
	});

	$app->get('/update.json/:id&:description&:repeats&:weight', function($id, $description, $repeats, $weight) {
			$sql = "SELECT weight FROM exercicio WHERE id = $id";
			$conn = getConn();
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			$actualWeight = $stmt->fetch()[0];

			$sql = "UPDATE exercicio SET description = '$description', repeats='$repeats', weight=$weight WHERE id = $id";
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			if($actualWeight != $weight)
			{
				$data = date('Y-m-d');

				$sql = "INSERT INTO modificacao (progressdate, id_exercise, weight) values('$data', $id, $weight)";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
			}

			echo json_encode("OK");
	});

	$app->get('/delete.json/:id', function($id) {

			$sql = "DELETE FROM modificacao WHERE id_exercise = $id";
			$conn = getConn();
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM exercicio WHERE id = $id";
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			echo json_encode("OK");
	});

	$app->get('/modificacoes.json/:id', function($id) {
		$conn = getConn();
		$sql = "SELECT * FROM modificacao where id_exercise = $id";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode($stmt->fetchAll());
	});

	$app->get('/deleteMod.json/:id', function($id)
	{
			$conn = getConn();
			$sql = "SELECT id_exercise FROM modificacao WHERE id = $id";
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			$id_exercise = $stmt->fetch()[0];

			$sql = "DELETE FROM modificacao WHERE id = $id";
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			$sql = "SELECT count(*) as contagem FROM modificacao WHERE id_exercise = $id_exercise";
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			$contagem = $stmt->fetch()[0];

			echo $contagem;
			if($contagem == 0)
			{
				$sql = "UPDATE exercicio SET weight = 0 WHERE id = $id_exercise";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
			}

			echo json_encode("OK");
	});

	$app->run();

?>
