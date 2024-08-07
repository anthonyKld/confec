<?php

$id = 5;
$username = "";
$password = "";
try {
  $conn = new PDO('', $username, $password);
  $stmt = $conn->prepare('SELECT * FROM Tabela');
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
