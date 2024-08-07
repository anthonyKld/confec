<?php

$id = 5;
try {
  $conn = new PDO('mysql:host=localhost;dbname=meuBancoDeDados', $username, $password);
  $stmt = $conn->prepare('SELECT * FROM minhaTabela WHERE id = :id');
  $stmt->execute(array('id' => $id));

  $result = $stmt->fetchAll();

  if ( count($result) ) {
    foreach($result as $row) {
      print_r($row);
    }
  } else {
    echo "Nennhum resultado retornado.";
  }
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

 ?>