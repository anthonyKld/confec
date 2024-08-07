<?php

$id = 5;
$username = "u953711579_user";
$password = "Personal_2@2@";
try {
  $conn = new PDO('mysql:host=localhost;dbname=u953711579_database', $username, $password);
  $stmt = $conn->prepare('SELECT * FROM Tabela WHERE id = 1');
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
